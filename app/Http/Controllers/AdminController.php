<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPegawai = User::where('role', 'pegawai')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        
        // For demo purposes, using static data
        $pendingBookings = 8;
        $activeBookings = 24;

        // Recent activities - using static data for demo
        $recentUsers = [
            (object)[
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'user'
            ],
            (object)[
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'role' => 'pegawai'
            ]
        ];

        $pendingApprovals = [
            (object)[
                'type' => 'room',
                'title' => 'Peminjaman Ruangan A101',
                'user_name' => 'John Doe',
                'date' => '15 Nov 2023'
            ],
            (object)[
                'type' => 'video',
                'title' => 'Peminjaman Video Trone',
                'user_name' => 'Jane Smith',
                'date' => '16 Nov 2023'
            ]
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPegawai',
            'totalAdmin',
            'pendingBookings',
            'activeBookings',
            'recentUsers',
            'pendingApprovals'
        ));
    }

    /**
     * Display account management page (AKA daftar-users)
     */
    public function daftarUsers(Request $request)
    {
        $query = User::query(); // Tampilkan SEMUA user
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
                  // Hapus department dari pencarian
            });
        }
        
        // Filter berdasarkan role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status (aktif/nonaktif)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }


        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'pegawai' => User::where('role', 'pegawai')->count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
        ];

        return view('admin.daftar-users', compact('users', 'stats'));
    }

    /**
     * Get user data for editing (API)
     */
    public function getUserData($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Store new user (from modal)
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user,pegawai,admin',
            'no_telepon' => 'nullable|string|max:15',
            // Hapus validasi untuk department
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_modal', true);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'no_telepon' => $request->no_telepon,
                // Hapus department dari create
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat user: ' . $e->getMessage())
                ->with('show_modal', true)
                ->withInput();
        }
    }

    /**
     * Update user data (from modal)
     */
    public function updateUserData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,pegawai,admin',
            'status' => 'required|in:active,inactive',
            'no_telepon' => 'nullable|string|max:15',
            // Hapus validasi untuk department
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_edit_modal', true)
                ->with('edit_user_id', $id);
        }

        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
                'no_telepon' => $request->no_telepon,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data user: ' . $e->getMessage())
                ->with('show_edit_modal', true)
                ->with('edit_user_id', $id);
        }
    }

    /**
     * Delete user account
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent admin from deleting themselves
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri!');
            }

            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent admin from deactivating themselves
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'Tidak dapat menonaktifkan akun sendiri!');
            }

            $newStatus = $user->status == 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            $statusText = $newStatus == 'active' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "User berhasil $statusText!");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status user: ' . $e->getMessage());
        }
    }

    /**
     * Display employee schedule page
     */
    public function jadwalPegawai()
    {
        // For demo purposes, using static data
        $pegawai = [
            (object)[
                'id' => 1,
                'name' => 'Budi Santoso',
                'position' => 'Staff IT',
                'employee_id' => 'IT001',
                'shift' => 'Pagi (08:00-16:00)',
                'status' => 'Hadir'
            ]
        ];

        $todaySchedule = [
            (object)[
                'name' => 'Budi Santoso',
                'position' => 'Staff IT',
                'shift' => 'Shift Pagi (08:00-16:00)',
                'status' => 'Hadir',
                'check_in' => '07:55'
            ],
            (object)[
                'name' => 'Siti Rahayu',
                'position' => 'Staff HR',
                'shift' => 'Shift Siang (12:00-20:00)',
                'status' => 'Belum Check-in',
                'check_in' => null
            ]
        ];

        return view('admin.jadwal-pegawai', compact('pegawai', 'todaySchedule'));
    }
    
    /**
     * Approve booking
     */
    public function approveBooking($id)
    {
        // For demo purposes
        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui!');
    }

    /**
     * Reject booking
     */
    public function rejectBooking($id)
    {
        // For demo purposes
        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak!');
    }
}