<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenyewaanVidotron;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PenyewaanVidotronController extends Controller
{
    /**
     * Method untuk halaman peminjaman-video (menggunakan view peminjaman-video.blade.php)
     */
    public function peminjamanVideo(Request $request)
    {
        try {
            Log::info('=== ADMIN PEMINJAMAN VIDEO CONTROLLER ACCESSED ===');
            
            // Hitung statistik
            $totalPeminjaman = PenyewaanVidotron::count();
            $disetujuiCount = PenyewaanVidotron::where('status', 'disetujui')->count();
            $menungguCount = PenyewaanVidotron::where('status', 'menunggu')->count();
            $ditolakCount = PenyewaanVidotron::where('status', 'ditolak')->count();
            $selesaiCount = PenyewaanVidotron::where('status', 'selesai')->count();
            $dibatalkanCount = PenyewaanVidotron::where('status', 'dibatalkan')->count();

            // Ambil data yang menunggu persetujuan
            $pendingApprovals = PenyewaanVidotron::with('user')
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'desc')
                ->get();

            // Query untuk daftar semua penyewaan
            $query = PenyewaanVidotron::with('user')
                ->orderBy('created_at', 'desc');

            // Filter berdasarkan status jika ada
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Search jika ada
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('tujuan_pemasangan', 'like', "%{$search}%")
                      ->orWhere('nama_pengusul', 'like', "%{$search}%")
                      ->orWhere('program_studi', 'like', "%{$search}%")
                      ->orWhere('fakultas', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $bookings = $query->paginate(10);

            return view('admin.peminjaman-video', compact(
                'totalPeminjaman',
                'disetujuiCount',
                'menungguCount',
                'ditolakCount',
                'selesaiCount',
                'dibatalkanCount',
                'pendingApprovals',
                'bookings'
            ));

        } catch (\Exception $e) {
            Log::error("ERROR in peminjamanVideo: " . $e->getMessage());
            
            return view('admin.peminjaman-video', [
                'totalPeminjaman' => 0,
                'disetujuiCount' => 0,
                'menungguCount' => 0,
                'ditolakCount' => 0,
                'selesaiCount' => 0,
                'dibatalkanCount' => 0,
                'pendingApprovals' => collect(),
                'bookings' => collect()
            ]);
        }
    }

    public function storePeminjamanVideo(Request $request)
{
    $request->validate([
        'nama_pengusul'      => 'required|string|max:255',
        'nim_nip'           => 'required|string|max:50',
        'fakultas'          => 'required|string|max:100',
        'program_studi'             => 'nullable|string|max:100',
        'email'             => 'required|email',
        'no_telepon'        => 'required|string|max:20',
        'tujuan_pemasangan' => 'required|string|max:255',
        'tanggal_mulai'     => 'required|date',
        'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
        'surat_pengajuan'   => 'nullable|file|mimes:pdf,doc,docx|max:10240',
    ]);

    $penyewaan = new PenyewaanVidotron();

    $penyewaan->user_id = auth()->id();
    $penyewaan->nama_pengusul = $request->nama_pengusul;
    $penyewaan->nim_nidn = $request->nim_nidn;
    $penyewaan->fakultas = $request->fakultas;
    $penyewaan->program_studi = $request->program_studi;
    $penyewaan->email = $request->email;
    $penyewaan->no_telepon = $request->no_telepon;
    $penyewaan->tanggal_mulai = $request->tanggal_mulai;
    $penyewaan->tanggal_selesai = $request->tanggal_selesai;
    $penyewaan->status = 'menunggu';

    if ($request->hasFile('surat_pengajuan')) {

        $file = $request->file('surat_pengajuan');

        $filename = time().'_'.$file->getClientOriginalName();

        $path = $file->storeAs(
            'surat_pengajuan',
            $filename,
            'public'
        );

        $penyewaan->surat_pengajuan = $path;
    }

    $penyewaan->save();

    return redirect()
        ->route('user.peminjaman-video')
        ->with('success', 'Pengajuan vidotron berhasil dikirim.');
}

    /**
     * Method untuk halaman penyewaan-vidotron - GUNAKAN VIEW YANG SAMA
     */
    public function index(Request $request)
    {
        // ARAHKAN KE METHOD peminjamanVideo UNTUK MENGGUNAKAN VIEW YANG SAMA
        return $this->peminjamanVideo($request);
    }

    public function show(PenyewaanVidotron $penyewaanVidotron)
    {
        $penyewaanVidotron->load('user');
        return view('admin.penyewaan-vidotron.show', compact('penyewaanVidotron'));
    }

    public function approve($id)
    {
        try {
            Log::info('=== APPROVE PENYEWAAN VIDOTRON ===');
            Log::info('ID: ' . $id);
            
            $penyewaan = PenyewaanVidotron::findOrFail($id);
            
            Log::info('Current status: ' . $penyewaan->status);
            
            // Periksa apakah status masih "menunggu"
            if ($penyewaan->status !== 'menunggu') {
                Log::warning('Cannot approve non-pending request. Current status: ' . $penyewaan->status);
                return redirect()->back()
                    ->with('error', 'Hanya penyewaan dengan status "Menunggu" yang dapat disetujui.');
            }
            
            $penyewaan->update([
                'status' => 'disetujui',
                'alasan_penolakan' => null
            ]);
            
            Log::info('Penyewaan approved successfully. New status: disetujui');

            return redirect()->back()
                ->with('success', 'Penyewaan vidotron telah disetujui!');

        } catch (\Exception $e) {
            Log::error('Error in approve method: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            Log::info('=== REJECT PENYEWAAN VIDOTRON ===');
            Log::info('ID: ' . $id);
            Log::info('Request data: ', $request->all());
            
            $validated = $request->validate([
                'alasan_penolakan' => 'required|string|max:1000'
            ]);

            $penyewaan = PenyewaanVidotron::findOrFail($id);
            
            Log::info('Current status: ' . $penyewaan->status);
            
            // Periksa apakah status masih "menunggu"
            if ($penyewaan->status !== 'menunggu') {
                Log::warning('Cannot reject non-pending request. Current status: ' . $penyewaan->status);
                return redirect()->back()
                    ->with('error', 'Hanya penyewaan dengan status "Menunggu" yang dapat ditolak.');
            }

            $penyewaan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $validated['alasan_penolakan']
            ]);
            
            Log::info('Penyewaan rejected successfully. New status: ditolak');

            return redirect()->back()
                ->with('success', 'Penyewaan vidotron telah ditolak!');

        } catch (\Exception $e) {
            Log::error('Error in reject method: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $penyewaan = PenyewaanVidotron::findOrFail($id);
            
            if ($penyewaan->surat_pengajuan) {
                Storage::disk('public')->delete($penyewaan->surat_pengajuan);
            }

            $penyewaan->delete();

            return redirect()->back()
                ->with('success', 'Data penyewaan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadSurat($id)
    {
        $penyewaan = PenyewaanVidotron::findOrFail($id);

        if (!$penyewaan->surat_pengajuan) {
            return redirect()->back()->with('error', 'File surat pengajuan tidak ditemukan.');
        }

        if (!Storage::disk('public')->exists($penyewaan->surat_pengajuan)) {
            return redirect()->back()->with('error', 'File surat pengajuan tidak ditemukan.');
        }

        return Storage::disk('public')->download($penyewaan->surat_pengajuan);
    }

    public function detail($id)
    {
        try {
            Log::info('Loading vidotron detail for ID: ' . $id);
            
            $penyewaan = PenyewaanVidotron::with(['user'])->find($id);
            
            if (!$penyewaan) {
                Log::error('Penyewaan vidotron not found for ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data penyewaan vidotron tidak ditemukan'
                ], 404);
            }

            Log::info('Penyewaan found: ' . $penyewaan->id);
            
            $html = view('admin.partials.vidotron-detail', compact('penyewaan'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in detail method: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Cancel penyewaan vidotron (admin side)
     */
    public function cancel($id)
    {
        try {
            $penyewaan = PenyewaanVidotron::findOrFail($id);
            
            if (!in_array($penyewaan->status, ['menunggu', 'disetujui'])) {
                return redirect()->back()->with('error', 'Tidak dapat membatalkan penyewaan dengan status ' . $penyewaan->status . '.');
            }

            $penyewaan->update([
                'status' => 'dibatalkan'
            ]);

            return redirect()->back()
                ->with('success', 'Penyewaan vidotron berhasil dibatalkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}