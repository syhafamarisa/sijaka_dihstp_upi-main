<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\PenyewaanVidotron;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeminjamanRuanganController extends Controller
{
    // User Functions
    public function create(Request $request)
    {
        // Default: tampilkan ruangan yang statusnya tersedia.
        // Nanti filter "dipakai" saat user sudah pilih tanggal & jam dilakukan via checkAvailability.
        $ruangan = Ruangan::where('status', 'tersedia')->get();

        $user = Auth::user();

        return view('user.peminjaman-ruangan', compact('ruangan', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'jenis_pengusul'   => 'required|in:mahasiswa,dosen,staff,tamu',
        'nama_pengusul'    => 'required|string|max:255',
        'nim_nip_nidn'    => 'required|string|max:50',
        'fakultas'        => 'required|string|max:100',
        'program_studi'   => 'nullable|string|max:100',
        'email'           => 'required|email|max:255',
        'no_telepon'      => 'required|string|max:20',
        'ruangan_id'      => 'required|exists:ruangan,id',
        'acara'           => 'required|string|max:255',

        // Interval tanggal
        'tanggal_mulai'   => 'required|date|after_or_equal:today',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',

        'jam_mulai'       => 'required|date_format:H:i',
        'jam_selesai'     => 'required|date_format:H:i|after:jam_mulai',
        'jumlah_peserta'  => 'required|integer|min:1',
        'keterangan'      => 'nullable|string',
        'lampiran_surat'  => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

        // Cek apakah tanggal mulai hari ini dan jam mulai sudah terlewat
    $todayStr = Carbon::today()->format('Y-m-d');
    if ($request->tanggal_mulai === $todayStr) {
        $nowTime = Carbon::now()->format('H:i');
        if ($request->jam_mulai < $nowTime) {
            return back()
                ->withErrors([
                    'jam_mulai' => 'Jam mulai tidak boleh kurang dari waktu sekarang.'
                ])
                ->withInput();
        }
    }

    $ruangan = Ruangan::findOrFail($request->ruangan_id);

    // Cek kapasitas
    if ($request->jumlah_peserta > $ruangan->kapasitas) {
        return back()
            ->withErrors([
                'jumlah_peserta' => 'Jumlah peserta melebihi kapasitas ruangan.'
            ])
            ->withInput();
    }

    // Cek ketersediaan ruangan
    if (!$ruangan->isAvailable(
        $request->tanggal,
        $request->tanggal,
        $request->jam_mulai,
        $request->jam_selesai
    )) {
        return back()
            ->withErrors([
                'ruangan_id' => 'Ruangan tidak tersedia pada jadwal tersebut.'
            ])
            ->withInput();
    }

    $peminjaman = new PeminjamanRuangan();

    $peminjaman->user_id = Auth::id();
    $peminjaman->jenis_pengusul = $request->jenis_pengusul;
    $peminjaman->nama_pengusul = $request->nama_pengusul;
    // field di request/form diseragamkan: nim_nip_nidn
    $peminjaman->nim_nip = $request->nim_nip_nidn ?? $request->nim_nidn ?? $request->nim_nip;
    $peminjaman->fakultas = $request->fakultas;
    $peminjaman->program_studi = $request->program_studi;
    $peminjaman->email = $request->email;
    $peminjaman->no_telepon = $request->no_telepon;
    $peminjaman->ruangan_id = $request->ruangan_id;
    $peminjaman->acara = $request->acara;

        // Karena form sekarang memiliki interval tanggal
    $peminjaman->hari = \Carbon\Carbon::parse($request->tanggal_mulai)->translatedFormat('l');
    $peminjaman->tanggal_mulai = $request->tanggal_mulai;
    $peminjaman->tanggal_selesai = $request->tanggal_selesai;

    $peminjaman->jam_mulai = $request->jam_mulai;
    $peminjaman->jam_selesai = $request->jam_selesai;
    $peminjaman->jumlah_peserta = $request->jumlah_peserta;
    $peminjaman->keterangan = $request->keterangan;
    $peminjaman->status = 'menunggu';

    if ($request->hasFile('lampiran_surat')) {
        $filename = time() . '_' . $request->file('lampiran_surat')->getClientOriginalName();

        $path = $request->file('lampiran_surat')->storeAs(
            'lampiran_surat',
            $filename,
            'public'
        );

        $peminjaman->lampiran_surat = $path;
    }

    $peminjaman->save();

    return redirect()
        ->route('user.peminjaman-ruangan.riwayat')
        ->with('success', 'Peminjaman ruangan berhasil diajukan.');
    }

    // Modifikasi method indexUser untuk 2 tab
    public function indexUser(Request $request)
    {
        $activeTab = $request->get('tab', 'ruangan');

        // Query untuk peminjaman ruangan
        $peminjamanRuangan = PeminjamanRuangan::where('user_id', Auth::id())
            ->with('ruangan')
            ->when($request->has('status') && $request->status != '', function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->has('bulan') && $request->bulan != '', function($query) use ($request) {
                return $query->whereYear('tanggal_mulai', substr($request->bulan, 0, 4))
                            ->whereMonth('tanggal_selesai', substr($request->bulan, 5, 2));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Query untuk penyewaan vidotron
        $penyewaanVidotron = PenyewaanVidotron::where('user_id', Auth::id())
            ->when($request->has('status') && $request->status != '', function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->has('bulan') && $request->bulan != '', function($query) use ($request) {
                return $query->whereYear('created_at', substr($request->bulan, 0, 4))
                            ->whereMonth('created_at', substr($request->bulan, 5, 2));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        return view('user.riwayat', compact('peminjamanRuangan', 'penyewaanVidotron', 'activeTab'));
    }

    // Method untuk detail peminjaman ruangan (AJAX)
    public function getDetailUser($id)
    {
        try {
            Log::info('=== DETAIL PEMINJAMAN RUANGAN ===');
            Log::info('User ID: ' . Auth::id());
            Log::info('Request ID: ' . $id);
            
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak valid'
                ], 400);
            }
            
            // Cari data
            $peminjaman = PeminjamanRuangan::with(['user', 'ruangan'])
                ->where('user_id', Auth::id())
                ->find($id);

            if (!$peminjaman) {
                Log::warning('Data tidak ditemukan. User: ' . Auth::id() . ', ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjaman ruangan tidak ditemukan'
                ], 404);
            }

            Log::info('Data ditemukan: ' . $peminjaman->id);
            
            // Render view
            try {
                // Coba render view yang berbeda
                if (view()->exists('user.peminjaman-ruangan._detail-modal')) {
                    $html = view('user.peminjaman-ruangan._detail-modal', compact('peminjaman'))->render();
                } elseif (view()->exists('user.partials.peminjaman-detail')) {
                    $html = view('user.partials.peminjaman-detail', compact('peminjaman'))->render();
                } else {
                    // Fallback view jika tidak ditemukan
                    $html = $this->generateFallbackDetailHtml($peminjaman);
                }
            } catch (\Exception $e) {
                Log::error('Error rendering view: ' . $e->getMessage());
                $html = $this->generateErrorHtml('Gagal memuat tampilan detail');
            }
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'data' => [
                    'id' => $peminjaman->id,
                    'acara' => $peminjaman->acara,
                    'status' => $peminjaman->status
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
    
    // Helper function untuk generate fallback HTML
    private function generateFallbackDetailHtml($peminjaman)
    {
        $statusColors = [
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            'selesai' => 'bg-gray-100 text-gray-800',
            'dibatalkan' => 'bg-gray-100 text-gray-800'
        ];
        
        return '
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-800">Detail Peminjaman Ruangan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">Ruangan</p>
                    <p class="font-medium">' . ($peminjaman->ruangan->nama_ruangan ?? 'N/A') . '</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">Acara</p>
                    <p class="font-medium">' . $peminjaman->acara . '</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">Tanggal_mulai</p>
                    <p class="font-medium">' . \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d M Y') . '</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">Waktu</p>
                    <p class="font-medium">' . $peminjaman->jam_mulai . ' - ' . $peminjaman->jam_selesai . '</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">Jumlah Peserta</p>
                    <p class="font-medium">' . $peminjaman->jumlah_peserta . ' orang</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded ' . ($statusColors[$peminjaman->status] ?? 'bg-gray-100') . '">
                        ' . ucfirst($peminjaman->status) . '
                    </span>
                </div>
            </div>
            
            ' . ($peminjaman->keterangan ? '
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-sm text-blue-600 font-medium">Keterangan</p>
                <p class="text-gray-700">' . nl2br(e($peminjaman->keterangan)) . '</p>
            </div>' : '') . '
            
            ' . ($peminjaman->status == 'ditolak' && $peminjaman->alasan_penolakan ? '
            <div class="bg-red-50 p-3 rounded">
                <p class="text-sm text-red-600 font-medium">Alasan Penolakan</p>
                <p class="text-gray-700">' . nl2br(e($peminjaman->alasan_penolakan)) . '</p>
            </div>' : '') . '
        </div>';
    }
    
    // Helper function untuk generate error HTML
    private function generateErrorHtml($message)
    {
        return '
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-red-700 mb-2">Terjadi Kesalahan</h4>
            <p class="text-gray-600 mb-4">' . e($message) . '</p>
        </div>';
    }

    public function cancelUser($id)
    {
        $peminjaman = PeminjamanRuangan::where('user_id', Auth::id())->findOrFail($id);
        
        if (!in_array($peminjaman->status, ['menunggu', 'disetujui'])) {
            return back()->with('error', 'Tidak dapat membatalkan peminjaman dengan status ini.');
        }

        $peminjaman->status = 'dibatalkan';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman ruangan berhasil dibatalkan.');
    }

    // Admin Functions
    public function indexAdmin(Request $request)
    {
        $query = PeminjamanRuangan::with(['user', 'ruangan'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('ruangan_id') && $request->ruangan_id != '') {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->where('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai != '') {
            $query->where('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan jenis pengusul
        if ($request->has('jenis_pengusul') && $request->jenis_pengusul != '') {
            $query->where('jenis_pengusul', $request->jenis_pengusul);
        }

        // Filter berdasarkan fakultas
        if ($request->has('fakultas') && $request->fakultas != '') {
            $query->where('fakultas', 'like', '%' . $request->fakultas . '%');
        }

        // Filter berdasarkan nama pengusul
        if ($request->has('nama_pengusul') && $request->nama_pengusul != '') {
            $query->where('nama_pengusul', 'like', '%' . $request->nama_pengusul . '%');
        }

        $peminjaman = $query->get();
        $ruangan = Ruangan::all();

        $stats = [
            'total' => PeminjamanRuangan::count(),
            'menunggu' => PeminjamanRuangan::where('status', 'menunggu')->count(),
            'disetujui' => PeminjamanRuangan::where('status', 'disetujui')->count(),
            'ditolak' => PeminjamanRuangan::where('status', 'ditolak')->count(),
            'selesai' => PeminjamanRuangan::where('status', 'disetujui')
                ->where('tanggal_mulai', '<', today())
                ->orWhere(function($q) {
                    $q->where('status', 'disetujui')
                      ->where('tanggal_selesai', today())
                      ->where('jam_selesai', '<', now()->format('H:i:s'));
                })
                ->count(),
            'dibatalkan' => PeminjamanRuangan::where('status', 'dibatalkan')->count(),
        ];

        return view('admin.peminjaman-ruangan', compact('peminjaman', 'ruangan', 'stats'));
    }

    public function detail($id)
    {
        try {
            Log::info('Loading peminjaman detail for ID: ' . $id);
            
            $peminjaman = PeminjamanRuangan::with(['user', 'ruangan'])->find($id);
            
            if (!$peminjaman) {
                Log::error('Peminjaman not found for ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjaman tidak ditemukan'
                ], 404);
            }

            Log::info('Peminjaman found: ' . $peminjaman->id);
            
            $html = view('admin.partials.peminjaman-detail', compact('peminjaman'))->render();
            
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

    public function approve($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        
        if (!$peminjaman->ruangan->isAvailable($peminjaman->tanggal_mulai, $peminjaman->tanggal_selesai, $peminjaman->jam_mulai, $peminjaman->jam_selesai, $peminjaman->id)) {
            return back()->with('error', 'Ruangan sudah tidak tersedia pada jadwal tersebut.');
        }

        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman ruangan disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        $peminjaman = PeminjamanRuangan::findOrFail($id);
        
        if ($peminjaman->status != 'menunggu') {
            return back()->with('error', 'Hanya peminjaman dengan status menunggu yang dapat ditolak.');
        }

        $peminjaman->status = 'ditolak';
        $peminjaman->alasan_penolakan = $request->alasan_penolakan;
        $peminjaman->save();

        return back()->with('success', 'Peminjaman ruangan ditolak.');
    }

    public function cancel($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        
        if (!in_array($peminjaman->status, ['menunggu', 'disetujui'])) {
            return back()->with('error', 'Tidak dapat membatalkan peminjaman dengan status ini.');
        }

        $peminjaman->status = 'dibatalkan';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman ruangan berhasil dibatalkan.');
    }

    public function updateStatusRealTime()
    {
        return response()->json(['message' => 'Status real-time sudah otomatis diperbarui']);
    }

    public function downloadSurat($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        
        if (!$peminjaman->lampiran_surat) {
            return back()->with('error', 'File lampiran tidak ditemukan.');
        }

        return Storage::disk('public')->download($peminjaman->lampiran_surat);
    }
}