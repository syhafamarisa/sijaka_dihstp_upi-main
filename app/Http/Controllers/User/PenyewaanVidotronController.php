<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PenyewaanVidotron;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PenyewaanVidotronController extends Controller
{
    /**
     * Tampilkan form peminjaman video trone
     * (nonaktif sementara)
     */
    public function create()
    {
        return redirect()->back()->with('error', 'Maaf vidiotron sedang dalam perbaikan.');
    }


    /**
     * Simpan peminjaman video trone
     */
    public function store(Request $request)

    {
        try {
            Log::info('=== PENYEWAAN VIDOTRON STORE ===');
            Log::info('User ID: ' . Auth::id());

            // Validasi data
            $validated = $request->validate([
                'fakultas' => 'required|string|max:255',
                'program_studi' => 'required|string|max:255',
                'jenis_pengusul' => 'required|in:dosen,staff,mahasiswa,organisasi',
                'nama_pengusul' => 'required|string|max:255',
                'nim_nidn' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'no_telepon' => 'required|string|max:20',
                'tujuan_pemasangan' => 'required|string|max:500',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required|after_or_equal:waktu_mulai',
                'jenis_konten' => 'required|in:video,image,text,live_feed',
                'deskripsi_konten' => 'required|string',
                'link_konten' => 'nullable|url',
                'surat_pengajuan' => 'required|file|mimes:pdf,doc,docx|max:5120',
            ]);

            Log::info('Validation passed');

            // Cek apakah tanggal mulai hari ini dan waktu mulai sudah terlewat
            $todayStr = \Carbon\Carbon::today()->format('Y-m-d');
            if ($request->tanggal_mulai === $todayStr) {
                $nowTime = \Carbon\Carbon::now()->format('H:i');
                if ($request->waktu_mulai < $nowTime) {
                    return redirect()->back()
                        ->with('error', 'Waktu mulai tidak boleh kurang dari waktu sekarang.')
                        ->withInput();
                }
            }

            // Upload surat pengajuan
            if ($request->hasFile('surat_pengajuan')) {
                $file = $request->file('surat_pengajuan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('surat_pengajuan_vidotron', $filename, 'public');
                $validated['surat_pengajuan'] = $path;
                Log::info('File uploaded: ' . $path);
            }

            // Tambahkan user_id dan status default
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'menunggu'; // Status default

            // Tambahkan field optional jika ada
            if ($request->has('keterangan')) {
                $validated['keterangan'] = $request->keterangan;
            }

            // Simpan data
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'menunggu';

            $penyewaan = PenyewaanVidotron::create($validated);

            Log::info('Penyewaan created with ID: ' . $penyewaan->id);

            return redirect()->route('user.dashboard')
                ->with('success', 'Pengajuan peminjaman video trone berhasil dikirim! Status: Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            Log::error('Store error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * (dihapus sementara) Simpan peminjaman video trone
     */
    public function storeOld(Request $request)
    {

        try {
            Log::info('=== PENYEWAAN VIDOTRON STORE ===');
            Log::info('User ID: ' . Auth::id());


            // Validasi data
            $validated = $request->validate([
                'fakultas' => 'required|string|max:255',
                'program_studi' => 'required|string|max:255',
                'jenis_pengusul' => 'required|in:dosen,staff,mahasiswa,organisasi',
                'nama_pengusul' => 'required|string|max:255',
                'nim_nidn' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'no_telepon' => 'required|string|max:20',
                'tujuan_pemasangan' => 'required|string|max:500',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required|after_or_equal:waktu_mulai',
                'jenis_konten' => 'required|in:video,image,text,live_feed',
                'deskripsi_konten' => 'required|string',
                'link_konten' => 'nullable|url',
                'surat_pengajuan' => 'required|file|mimes:pdf,doc,docx|max:5120',
            ]);

            Log::info('Validation passed');

            // Cek apakah tanggal mulai hari ini dan waktu mulai sudah terlewat
            $todayStr = \Carbon\Carbon::today()->format('Y-m-d');
            if ($request->tanggal_mulai === $todayStr) {
                $nowTime = \Carbon\Carbon::now()->format('H:i');
                if ($request->waktu_mulai < $nowTime) {
                    return redirect()->back()
                        ->with('error', 'Waktu mulai tidak boleh kurang dari waktu sekarang.')
                        ->withInput();
                }
            }

            // Upload surat pengajuan
            if ($request->hasFile('surat_pengajuan')) {
                $file = $request->file('surat_pengajuan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('surat_pengajuan_vidotron', $filename, 'public');
                $validated['surat_pengajuan'] = $path;
                Log::info('File uploaded: ' . $path);
            }

            // Tambahkan user_id dan status default
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'menunggu'; // Status default

            // Tambahkan field optional jika ada
            if ($request->has('keterangan')) {
                $validated['keterangan'] = $request->keterangan;
            }

            // Simpan data
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'menunggu';

            $penyewaan = PenyewaanVidotron::create($validated);

            Log::info('Penyewaan created with ID: ' . $penyewaan->id);

            return redirect()->route('user.dashboard')
                ->with('success', 'Pengajuan peminjaman video trone berhasil dikirim! Status: Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            Log::error('Store error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan semua penyewaan vidotron
     * (nonaktif sementara)
     */
    public function index()
    {
        return redirect()->back()->with('error', 'Maaf vidiotron sedang dalam perbaikan.');
    }






    /**
     * Tampilkan detail penyewaan
     * (nonaktif sementara)
     */
    public function show($id)
    {
        return redirect()->back()->with('error', 'Maaf vidiotron sedang dalam perbaikan.');
    }






    /**
     * Tampilkan form edit
     */
    public function edit($id)
    {
        return redirect()->back()->with('error', 'Maaf vidiotron sedang dalam perbaikan.');
    }




    /**
     * Update penyewaan vidotron
     */
    public function update(Request $request, $id)
    {
        try {
            $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())->findOrFail($id);

            // Hanya bisa update jika status menunggu
            if ($penyewaan->status != 'menunggu') {
                return back()->with('error', 'Hanya penyewaan dengan status "Menunggu" yang dapat diupdate.');
            }

            $validated = $request->validate([
                'fakultas' => 'required|string|max:255',
                'program_studi' => 'required|string|max:255',
                'jenis_pengusul' => 'required|in:dosen,staff,mahasiswa,organisasi',
                'nama_pengusul' => 'required|string|max:255',
                'nim_nidn' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'no_telepon' => 'required|string|max:20',
                'tujuan_pemasangan' => 'required|string|max:500',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required|after_or_equal:waktu_mulai',
                'jenis_konten' => 'required|in:video,image,text,live_feed',
                'deskripsi_konten' => 'required|string',
                'link_konten' => 'nullable|url',
                'surat_pengajuan' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'keterangan' => 'nullable|string',
            ]);

            // Update file jika ada
            if ($request->hasFile('surat_pengajuan')) {
                // Hapus file lama jika ada
                if ($penyewaan->surat_pengajuan && Storage::disk('public')->exists($penyewaan->surat_pengajuan)) {
                    Storage::disk('public')->delete($penyewaan->surat_pengajuan);
                }

                $file = $request->file('surat_pengajuan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('surat_pengajuan_vidotron', $filename, 'public');
                $validated['surat_pengajuan'] = $path;
            } else {
                unset($validated['surat_pengajuan']);
            }

            $penyewaan->update($validated);

            return redirect()->route('user.penyewaan-vidotron.status')
                ->with('success', 'Penyewaan vidotron berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus penyewaan vidotron
     */
    public function destroy($id)
    {
        try {
            $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())->findOrFail($id);

            // Hanya bisa hapus jika status menunggu
            if ($penyewaan->status != 'menunggu') {
                return back()->with('error', 'Hanya penyewaan dengan status "Menunggu" yang dapat dihapus.');
            }

            // Hapus file jika ada
            if ($penyewaan->surat_pengajuan && Storage::disk('public')->exists($penyewaan->surat_pengajuan)) {
                Storage::disk('public')->delete($penyewaan->surat_pengajuan);
            }

            $penyewaan->delete();

            return redirect()->route('user.penyewaan-vidotron.status')
                ->with('success', 'Penyewaan vidotron berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus penyewaan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan status penyewaan
     */
    public function status()
    {
        $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.penyewaan-vidotron.status', compact('penyewaan'));
    }

    /**
     * Tampilkan riwayat penyewaan
     */
    public function riwayat()
    {
        $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.penyewaan-vidotron.riwayat', compact('penyewaan'));
    }

    /**
     * Tampilkan detail untuk user (AJAX)
     */
    public function getDetailUser($id)
    {
        try {
            Log::info('=== PENYEWAAN VIDOTRON DETAIL DEBUG ===');
            Log::info('User ID: ' . Auth::id());
            Log::info('Request ID: ' . $id);

            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                Log::warning('Invalid ID format: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Format ID tidak valid'
                ], 400);
            }

            // Cari data berdasarkan ID dan user_id
            $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())->find($id);

            if (!$penyewaan) {
                Log::warning('Penyewaan vidotron not found. User: ' . Auth::id() . ', ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data penyewaan vidotron tidak ditemukan'
                ], 404);
            }

            Log::info('Penyewaan found: ' . $penyewaan->id);

            // Render view dengan fallback options
            try {
                if (view()->exists('user.penyewaan-vidotron._detail-modal')) {
                    $html = view('user.penyewaan-vidotron._detail-modal', compact('penyewaan'))->render();
                    Log::info('Using _detail-modal view');
                } elseif (view()->exists('user.partials.vidotron-detail')) {
                    $html = view('user.partials.vidotron-detail', compact('penyewaan'))->render();
                    Log::info('Using partials.vidotron-detail view');
                } else {
                    // Fallback HTML jika view tidak ditemukan
                    $html = $this->generateFallbackDetailHtml($penyewaan);
                    Log::info('Using fallback HTML');
                }
            } catch (\Exception $e) {
                Log::error('View rendering error: ' . $e->getMessage());
                $html = $this->generateErrorHtml('Gagal memuat tampilan detail');
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'data' => [
                    'id' => $penyewaan->id,
                    'tujuan_pemasangan' => $penyewaan->tujuan_pemasangan,
                    'status' => $penyewaan->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CRITICAL ERROR in getDetailUser: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
                'debug' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Batalkan penyewaan vidotron
     */
    public function cancel($id)
    {
        try {
            Log::info('=== CANCEL PENYEWAAN VIDOTRON ===');
            Log::info('User ID: ' . Auth::id());
            Log::info('Penyewaan ID: ' . $id);

            $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())->find($id);

            if (!$penyewaan) {
                Log::warning('Penyewaan not found for cancellation');
                return back()->with('error', 'Penyewaan vidotron tidak ditemukan.');
            }

            if (!in_array($penyewaan->status, ['menunggu', 'disetujui'])) {
                Log::warning('Invalid status for cancellation: ' . $penyewaan->status);
                return back()->with('error', 'Tidak dapat membatalkan penyewaan dengan status ' . $penyewaan->status . '.');
            }

            $penyewaan->status = 'dibatalkan';
            $penyewaan->save();

            Log::info('Penyewaan cancelled successfully');

            return back()->with('success', 'Penyewaan vidotron berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Cancel error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan penyewaan: ' . $e->getMessage());
        }
    }

    /**
     * Download surat pengajuan
     */
    public function downloadSurat($id)
    {
        try {
            $penyewaan = PenyewaanVidotron::where('user_id', Auth::id())->findOrFail($id);

            if (!$penyewaan->surat_pengajuan) {
                return back()->with('error', 'File surat pengajuan tidak ditemukan.');
            }

            if (!Storage::disk('public')->exists($penyewaan->surat_pengajuan)) {
                return back()->with('error', 'File surat pengajuan tidak ada di server.');
            }

            return Storage::disk('public')->download($penyewaan->surat_pengajuan);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload file: ' . $e->getMessage());
        }
    }

    /**
     * Generate fallback HTML untuk detail
     */
    private function generateFallbackDetailHtml($penyewaan)
    {
        $statusColors = [
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            'selesai' => 'bg-gray-100 text-gray-800',
            'dibatalkan' => 'bg-gray-100 text-gray-800'
        ];

        $jenisPengusulLabels = [
            'dosen' => 'Dosen',
            'staff' => 'Staff',
            'mahasiswa' => 'Mahasiswa',
            'organisasi' => 'Organisasi'
        ];

        $jenisKontenLabels = [
            'video' => 'Video',
            'image' => 'Gambar',
            'text' => 'Teks',
            'live_feed' => 'Live Feed'
        ];

        return '
        <div class="space-y-6">
            <div class="border-b pb-4">
                <h2 class="text-xl font-bold text-gray-800">Detail Penyewaan Vidotron</h2>
                <p class="text-gray-600 text-sm mt-1">ID: #'. $penyewaan->id .'</p>
            </div>

            <div class="flex justify-between items-start">
                <div>
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full ' . ($statusColors[$penyewaan->status] ?? 'bg-gray-100 text-gray-800') . '">
                        ' . ucfirst($penyewaan->status) . '
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    ' . $penyewaan->created_at->translatedFormat('d M Y H:i') . '
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tujuan Pemasangan</label>
                    <p class="text-gray-900 font-medium text-lg">' . e($penyewaan->tujuan_pemasangan) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Pengusul</label>
                    <p class="text-gray-900 font-medium">' . e($penyewaan->nama_pengusul) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Pengusul</label>
                    <p class="text-gray-900 font-medium">' . ($jenisPengusulLabels[$penyewaan->jenis_pengusul] ?? ucfirst($penyewaan->jenis_pengusul)) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fakultas/program_studi</label>
                    <p class="text-gray-900 font-medium">' . e($penyewaan->fakultas) . ' - ' . e($penyewaan->program_studi) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Kontak</label>
                    <p class="text-gray-900 font-medium">' . e($penyewaan->no_telepon) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                    <p class="text-gray-900 font-medium">' . e($penyewaan->email) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Periode</label>
                    <p class="text-gray-900 font-medium">
                        ' . \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->translatedFormat('d M Y') . ' - 
                        ' . \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->translatedFormat('d M Y') . '
                    </p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Waktu Harian</label>
                    <p class="text-gray-900 font-medium">' . e($penyewaan->waktu_mulai) . ' - ' . e($penyewaan->waktu_selesai) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Konten</label>
                    <p class="text-gray-900 font-medium">' . ($jenisKontenLabels[$penyewaan->jenis_konten] ?? ucfirst($penyewaan->jenis_konten)) . '</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">NIM/NIDN</label>
                    <p class="text-gray-900 font-medium">' . e($penyewaan->nim_nidn) . '</p>
                </div>
            </div>';

        // Note: bagian detail lainnya sengaja dibiarkan seperti implementasi awal.
    }

    /**
     * Generate error HTML
     */
    private function generateErrorHtml($message)
    {
        return '
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-red-700 mb-2">Terjadi Kesalahan</h4>
            <p class="text-gray-600 mb-4">' . e($message) . '</p>
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                Tutup
            </button>
        </div>';
    }
}

