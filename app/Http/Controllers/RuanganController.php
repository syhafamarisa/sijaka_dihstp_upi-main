<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    /**
     * Menampilkan halaman utama dengan tabel ruangan
     */
    public function index(Request $request)
    {
        try {
            // Ambil parameter pencarian
            $search = $request->input('search');
            $status = $request->input('status');
            
            // Query dasar
            $query = Ruangan::query();
            
            // Filter pencarian
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('kode_ruangan', 'LIKE', "%{$search}%")
                      ->orWhere('nama_ruangan', 'LIKE', "%{$search}%")
                      ->orWhere('fasilitas', 'LIKE', "%{$search}%");
                });
            }
            
            // Filter status
            if ($status && in_array($status, ['tersedia', 'dipinjam', 'maintenance'])) {
                $query->where('status', $status);
            }
            
            // Pagination
            $ruangan = $query->orderBy('created_at', 'desc')->paginate(10);
            
            // Hitung statistik
            $statistics = [
                'total' => Ruangan::count(),
                'tersedia' => Ruangan::where('status', 'tersedia')->count(),
                'dipinjam' => Ruangan::where('status', 'dipinjam')->count(),
                'maintenance' => Ruangan::where('status', 'maintenance')->count(),
            ];
            
            return view('admin.ruangan', compact('ruangan', 'statistics'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Get detail ruangan untuk modal
     */
    public function show($id)
    {
        try {
            $ruangan = Ruangan::findOrFail($id);
            
            // Format tanggal
            $ruangan->tanggal_dibuat = $ruangan->created_at->format('d-m-Y H:i');
            $ruangan->tanggal_diupdate = $ruangan->updated_at->format('d-m-Y H:i');
            
            return response()->json([
                'success' => true,
                'data' => $ruangan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan'
            ], 404);
        }
    }
    
    /**
     * API: Get statistics
     */
    public function getStatistics()
    {
        try {
            $statistics = [
                'total' => Ruangan::count(),
                'tersedia' => Ruangan::where('status', 'tersedia')->count(),
                'dipinjam' => Ruangan::where('status', 'dipinjam')->count(),
                'maintenance' => Ruangan::where('status', 'maintenance')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }
    
    /**
     * API: Store new ruangan
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'kode_ruangan' => 'required|unique:ruangan,kode_ruangan|max:20',
            'nama_ruangan' => 'required|max:100',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:tersedia,dipinjam,maintenance',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('gambar')) {
                $imagePath = $request->file('gambar')->store('ruangan', 'public');
                $data['gambar'] = $imagePath;
            } else {
                $data['gambar'] = null;
            }
            
            $ruangan = Ruangan::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil ditambahkan!',
                'data' => $ruangan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ruangan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: Update ruangan
     */
    public function update(Request $request, $id)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'kode_ruangan' => 'required|unique:ruangan,kode_ruangan,' . $id . '|max:20',
            'nama_ruangan' => 'required|max:100',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:tersedia,dipinjam,maintenance',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $ruangan = Ruangan::findOrFail($id);
            $data = $request->except(['_method', '_token']);
            
            // Handle image upload
            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($ruangan->gambar && Storage::exists('public/' . $ruangan->gambar)) {
                    Storage::delete('public/' . $ruangan->gambar);
                }
                
                $imagePath = $request->file('gambar')->store('ruangan', 'public');
                $data['gambar'] = $imagePath;
            }
            
            $ruangan->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil diperbarui!',
                'data' => $ruangan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui ruangan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: Delete ruangan
     */
    public function destroy($id)
    {
        try {
            $ruangan = Ruangan::findOrFail($id);
            
            // Delete image if exists
            if ($ruangan->gambar && Storage::exists('public/' . $ruangan->gambar)) {
                Storage::delete('public/' . $ruangan->gambar);
            }
            
            $ruangan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ruangan: ' . $e->getMessage()
            ], 500);
        }
    }
}