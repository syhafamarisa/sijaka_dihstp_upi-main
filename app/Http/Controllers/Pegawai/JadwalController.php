<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalRequest;
use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Dashboard untuk pegawai
     */
    public function dashboardPegawai()
    {
        try {
            $user = Auth::user();
            $today = Carbon::today();
            $currentTime = Carbon::now();
            
            // Statistik cepat
            $todayJadwal = Jadwal::where('user_id', $user->id)
                ->whereDate('tanggal_mulai', $today)
                ->count();
            
            $totalJadwal = Jadwal::where('user_id', $user->id)->count();
            
            $jadwalBerlangsung = Jadwal::where('user_id', $user->id)
                ->whereDate('tanggal_mulai', $today)
                ->where('waktu_mulai', '<=', $currentTime->format('H:i:s'))
                ->where('waktu_selesai', '>=', $currentTime->format('H:i:s'))
                ->count();
            
            $jadwalAkanDatang = Jadwal::where('user_id', $user->id)
                ->whereDate('tanggal_mulai', $today)
                ->where('waktu_mulai', '>', $currentTime->format('H:i:s'))
                ->count();
            
            // Jadwal hari ini
            $todayJadwalList = Jadwal::with('ruangan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal_mulai', $today)
                ->orderBy('waktu_mulai')
                ->get();
            
            // Jadwal mendatang (3 hari ke depan)
            $threeDaysLater = $today->copy()->addDays(3);
            $upcomingJadwal = Jadwal::with('ruangan')
                ->where('user_id', $user->id)
                ->whereBetween('tanggal_mulai', [$today->copy()->addDay(), $threeDaysLater])
                ->orderBy('tanggal_mulai')
                ->orderBy('waktu_mulai')
                ->limit(5)
                ->get();
            
            // Ringkasan minggu ini
            $startOfWeek = $today->copy()->startOfWeek();
            $endOfWeek = $today->copy()->endOfWeek();
            
            $weeklyStats = Jadwal::where('user_id', $user->id)
                ->whereBetween('tanggal_mulai', [$startOfWeek, $endOfWeek])
                ->selectRaw('DATE(tanggal_mulai) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy(function($item) {
                    return Carbon::parse($item->date)->format('Y-m-d');
                });
            
            // Buat array untuk 7 hari dalam seminggu
            $daysOfWeek = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $daysOfWeek[] = [
                    'date' => $day->format('Y-m-d'),
                    'day_name' => $day->translatedFormat('D'),
                    'count' => isset($weeklyStats[$day->format('Y-m-d')]) ? $weeklyStats[$day->format('Y-m-d')]->count : 0,
                    'is_today' => $day->isToday()
                ];
            }
            
            // Ruangan terpopuler untuk user ini
            $popularRuangan = Ruangan::withCount(['jadwal' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                }])
                ->whereHas('jadwal', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderByDesc('jadwal_count')
                ->limit(5)
                ->get();
            
            return view('pegawai.dashboard', compact(
                'todayJadwal',
                'totalJadwal',
                'jadwalBerlangsung',
                'jadwalAkanDatang',
                'todayJadwalList',
                'upcomingJadwal',
                'daysOfWeek',
                'weeklyStats',
                'popularRuangan'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in dashboardPegawai: ' . $e->getMessage());
            return view('pegawai.dashboard', [
                'todayJadwal' => 0,
                'totalJadwal' => 0,
                'jadwalBerlangsung' => 0,
                'jadwalAkanDatang' => 0,
                'todayJadwalList' => collect([]),
                'upcomingJadwal' => collect([]),
                'daysOfWeek' => [],
                'weeklyStats' => collect([]),
                'popularRuangan' => collect([]),
                'error' => 'Terjadi kesalahan saat memuat dashboard'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $ruangan = Ruangan::where('status', 'tersedia')
    ->orderBy('nama_ruangan', 'asc')
    ->get();
        
        return view('pegawai.buat-jadwal', compact('ruangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalRequest $request)
{
    try {
        $data = $request->validated();

        // Pastikan key sesuai kolom model (tanggal_mulai/tanggal_selesai)
        if ($request->has('tanggal_mulai') && !isset($data['tanggal_mulai'])) {
            $data['tanggal_mulai'] = $request->input('tanggal_mulai');
        }
        if ($request->has('tanggal_selesai') && !isset($data['tanggal_selesai'])) {
            $data['tanggal_selesai'] = $request->input('tanggal_selesai');
        }

        $data['user_id'] = Auth::id();

        Jadwal::create($data);


        return redirect()
            ->route('pegawai.jadwal.index')
            ->with('success', 'Jadwal berhasil dibuat.');
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    /**
     * Check availability of room
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date_format:H:i|after:tanggal_mulai',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'exclude_id' => 'nullable|exists:jadwal,id'
        ]);

        try {
            $ruangan = Ruangan::find($request->ruangan_id);
            
            $isAvailable = $ruangan->isAvailable(
                $request->tanggal_mulai,
                $request->tanggal_selesai, 
                $request->waktu_mulai, 
                $request->waktu_selesai, 
                $request->exclude_id
            );

            return response()->json([
                'available' => $isAvailable,
                'message' => $isAvailable ? 'Ruangan tersedia' : 'Ruangan tidak tersedia'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        $currentMonth = request('month') ? intval(request('month')) : Carbon::now()->month;
        $currentYear = request('year') ? intval(request('year')) : Carbon::now()->year;

        // ===== 1) Ambil Jadwal Kantor (tabel jadwal) =====
// Jadwal kantor: pegawai harus lihat semua (user + admin), bukan hanya milik pegawai
        $jadwalKantor = Jadwal::with(['user', 'ruangan'])
            ->whereMonth('tanggal_mulai', $currentMonth)
            ->whereYear('tanggal_mulai', $currentYear)
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $kegiatanHariIniKantor = $jadwalKantor->filter(function($jadwal) use ($today) {
            return optional($jadwal->tanggal_mulai)->format('Y-m-d') == $today;
        });

        // Format tabel (ambil 10 terbaru untuk kantor dulu, nanti akan digabung)
        $kegiatanTerbaruKantor = $jadwalKantor->take(10)->map(function($jadwal) {
            return [
                'id' => 'kantor_' . $jadwal->id,
                'type' => 'kantor',
                'nama_kegiatan' => $jadwal->nama_kegiatan,
                'deskripsi' => $jadwal->deskripsi ?? '-',
                'tanggal_mulai' => $jadwal->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $jadwal->tanggal_selesai->format('Y-m-d'),
                'waktu_mulai' => $jadwal->waktu_mulai,
                'waktu_selesai' => $jadwal->waktu_selesai,
                'lokasi' => $jadwal->ruangan ? ($jadwal->ruangan->kode_ruangan . ' - ' . $jadwal->ruangan->nama_ruangan) : 'Tidak ditentukan',
                'ruangan_id' => $jadwal->ruangan_id,
                'ruangan_nama' => $jadwal->ruangan ? $jadwal->ruangan->nama_ruangan : null,
                'ruangan_kode' => $jadwal->ruangan ? $jadwal->ruangan->kode_ruangan : null,
                'kapasitas_peserta' => $jadwal->kapasitas_peserta,
                'created_at' => $jadwal->created_at,
                'creator' => $jadwal->user->name ?? 'Staff',
                'model_id' => $jadwal->id,
                'model_type' => 'kantor'
            ];
        });

        // ===== 2) Ambil Peminjaman Ruangan (tabel peminjaman_ruangan) =====
        $peminjamanRuangan = \App\Models\PeminjamanRuangan::with('ruangan', 'user')
            ->where(function($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('tanggal_mulai', $currentMonth)
                      ->whereYear('tanggal_mulai', $currentYear)
                    ->orWhere(function($q) use ($currentMonth, $currentYear) {
                        $q->whereMonth('tanggal_selesai', $currentMonth)
                          ->whereYear('tanggal_selesai', $currentYear);
                    });
            })
            ->where('status', 'disetujui')
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        // Buat ekspansi per hari untuk kalender + list hari ini
        $kegiatanTerbaruRuangan = collect();
        foreach ($peminjamanRuangan as $p) {
            // untuk tabel: tampilkan sekali (pakai tanggal_mulai sebagai representasi)
            $kegiatanTerbaruRuangan->push([
                'id' => 'ruangan_' . $p->id,
                'type' => 'ruangan',
                'nama_kegiatan' => $p->acara ?? 'Peminjaman Ruangan',
                'deskripsi' => $p->keterangan ?? '-',
                'tanggal_mulai' => \Carbon\Carbon::parse($p->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => \Carbon\Carbon::parse($p->tanggal_selesai)->format('Y-m-d'),
                'waktu_mulai' => $p->jam_mulai,
                'waktu_selesai' => $p->jam_selesai,
                'lokasi' => $p->ruangan ? ($p->ruangan->kode_ruangan . ' - ' . $p->ruangan->nama_ruangan) : 'Tidak ditentukan',
                'ruangan_id' => $p->ruangan_id,
                'ruangan_nama' => $p->ruangan ? $p->ruangan->nama_ruangan : null,
                'ruangan_kode' => $p->ruangan ? $p->ruangan->kode_ruangan : null,
                'created_at' => $p->created_at,
                'creator' => optional($p->user)->name ?? 'Staff',
                'model_id' => $p->id,
                'model_type' => 'ruangan'
            ]);
        }

        // Gabungkan list hari ini
        $kegiatanHariIniRuangan = collect();
        foreach ($peminjamanRuangan as $p) {
            $start = \Carbon\Carbon::parse($p->tanggal_mulai)->format('Y-m-d');
            $end = \Carbon\Carbon::parse($p->tanggal_selesai)->format('Y-m-d');
            if ($start <= $today && $end >= $today) {
                $kegiatanHariIniRuangan->push([
                    'id' => 'ruangan_' . $p->id,
                    'type' => 'ruangan',
                    'nama_kegiatan' => $p->acara ?? 'Peminjaman Ruangan',
                    'deskripsi' => $p->keterangan ?? '-',
                    'tanggal_mulai' => $start,
                    'tanggal_selesai' => $end,
                    'waktu_mulai' => $p->jam_mulai,
                    'waktu_selesai' => $p->jam_selesai,
                    'lokasi' => $p->ruangan ? ($p->ruangan->kode_ruangan . ' - ' . $p->ruangan->nama_ruangan) : 'Tidak ditentukan',
                    'created_at' => $p->created_at,
                    'creator' => optional($p->user)->name ?? 'Staff',
                    'model_id' => $p->id,
                    'model_type' => 'ruangan',
                ]);
            }
        }

        // Gabungkan untuk kalender (per hari) agar sama untuk pegawai/admin
        $calendarEvents = [];
        $kegiatanGabunganUntukCalendar = collect();

        // Kantor
        foreach ($jadwalKantor as $j) {
            $kegiatanGabunganUntukCalendar->push([
                'id' => 'kantor_' . $j->id,
                'type' => 'kantor',
                'nama_kegiatan' => $j->nama_kegiatan,
                'lokasi' => $j->ruangan ? ($j->ruangan->kode_ruangan . ' - ' . $j->ruangan->nama_ruangan) : 'Tidak ditentukan',
                'ruangan_nama' => $j->ruangan ? $j->ruangan->nama_ruangan : null,
                'tanggal_mulai' => $j->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $j->tanggal_selesai->format('Y-m-d'),
                'waktu_mulai' => $j->waktu_mulai,
                'waktu_selesai' => $j->waktu_selesai,
            ]);
        }

        // Ruangan (expand per hari)
        foreach ($peminjamanRuangan as $p) {
            $tanggalMulai = \Carbon\Carbon::parse($p->tanggal_mulai);
            $tanggalSelesai = \Carbon\Carbon::parse($p->tanggal_selesai);
            for ($d = $tanggalMulai->copy(); $d->lte($tanggalSelesai); $d->addDay()) {
                if ($d->month == $currentMonth && $d->year == $currentYear) {
                    $calendarEvents[$d->day] = $calendarEvents[$d->day] ?? [];
                    $calendarEvents[$d->day][] = [
                        'id' => 'ruangan_' . $p->id,
                        'type' => 'ruangan',
                        'nama_kegiatan' => $p->acara ?? 'Peminjaman Ruangan',
                        'lokasi' => $p->ruangan ? ($p->ruangan->kode_ruangan . ' - ' . $p->ruangan->nama_ruangan) : 'Tidak ditentukan',
                        'ruangan_nama' => $p->ruangan ? $p->ruangan->nama_ruangan : null,
                    ];
                }
            }
        }

        // Kantor per hari juga (untuk kalender)
        foreach ($jadwalKantor as $j) {
            $tanggalMulai = \Carbon\Carbon::parse($j->tanggal_mulai);
            $tanggalSelesai = \Carbon\Carbon::parse($j->tanggal_selesai);
            for ($d = $tanggalMulai->copy(); $d->lte($tanggalSelesai); $d->addDay()) {
                if ($d->month == $currentMonth && $d->year == $currentYear) {
                    $calendarEvents[$d->day] = $calendarEvents[$d->day] ?? [];
                    $calendarEvents[$d->day][] = [
                        'id' => 'kantor_' . $j->id,
                        'type' => 'kantor',
                        'nama_kegiatan' => $j->nama_kegiatan,
                        'lokasi' => $j->ruangan ? ($j->ruangan->kode_ruangan . ' - ' . $j->ruangan->nama_ruangan) : 'Tidak ditentukan',
                        'ruangan_nama' => $j->ruangan ? $j->ruangan->nama_ruangan : null,
                    ];
                }
            }
        }

        // Merge list untuk tabel (gabung kantor + ruangan) dan ambil 10 teratas berdasarkan tanggal_mulai
        $kegiatanTerbaru = $kegiatanTerbaruKantor->concat($kegiatanTerbaruRuangan)
            ->sortByDesc(function($item) {
                return $item['tanggal_mulai'] ?? '';
            })
            ->take(10)
            ->values();

        // Jadikan kegiatan hari ini dalam format array seperti yang dipakai view
        $kegiatanHariIni = collect();
        foreach ($kegiatanHariIniKantor as $j) {
            $kegiatanHariIni->push([
                'nama_kegiatan' => $j->nama_kegiatan,
                'deskripsi' => $j->deskripsi ?? '-',
                'waktu_mulai' => $j->waktu_mulai,
                'waktu_selesai' => $j->waktu_selesai,
                'lokasi' => $j->ruangan ? ($j->ruangan->kode_ruangan . ' - ' . $j->ruangan->nama_ruangan) : 'Tidak ditentukan',
                'tanggal_mulai' => $j->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $j->tanggal_selesai->format('Y-m-d'),
                'creator' => $j->user->name ?? 'Staff',
                'type' => 'kantor',
                'user' => optional($j->user)->toArray(),
            ]);
        }
        $kegiatanHariIni = $kegiatanHariIni->concat($kegiatanHariIniRuangan);

        $ruanganList = Ruangan::orderBy('kode_ruangan')->get();

        return view('pegawai.jadwal-staff', compact(
            'jadwalKantor',
            'kegiatanHariIni',
            'kegiatanTerbaru',
            'calendarEvents',
            'today',
            'currentMonth',
            'currentYear',
            'ruanganList'
        ));
    }


    /**
     * Menampilkan semua kegiatan dengan filter
     */
    public function semuaKegiatan(Request $request)
    {
        try {
            $currentMonth = $request->get('bulan') ? intval($request->get('bulan')) : null;
            $currentYear = $request->get('tahun') ? intval($request->get('tahun')) : Carbon::now()->year;
            
            // Query jadwal dengan ruangan
            $query = Jadwal::with(['user', 'ruangan'])
                ->whereYear('tanggal_mulai', $currentYear);
            
            if ($currentMonth) {
                $query->whereMonth('tanggal_mulai', $currentMonth);
            }
            
            // Filter ruangan
            $ruanganId = $request->get('ruangan_id');
            if ($ruanganId) {
                $query->where('ruangan_id', $ruanganId);
            }
            
            // Filter pencarian
            $search = $request->get('search');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_kegiatan', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhereHas('ruangan', function($q2) use ($search) {
                          $q2->where('nama_ruangan', 'like', "%{$search}%")
                             ->orWhere('kode_ruangan', 'like', "%{$search}%");
                      });
                });
            }
            
            // Urutkan
            $query->orderBy('tanggal_mulai', 'desc')
                  ->orderBy('waktu_mulai', 'asc');
            
            // Data untuk tabel
            $allKegiatan = $query->get();
            
            $kegiatanTerbaru = $allKegiatan->map(function($jadwal) {
                return [
                    'id' => 'kantor_' . $jadwal->id,
                    'type' => 'kantor',
                    'nama_kegiatan' => $jadwal->nama_kegiatan,
                    'deskripsi' => $jadwal->deskripsi ?? '-',
                    'tanggal_mulai' => $jadwal->tanggal_mulai->format('Y-m-d'),
                    'tanggal_selesai' => $jadwal->tanggal_selesai->format('Y-m-d'),
                    'waktu_mulai' => $jadwal->waktu_mulai,
                    'waktu_selesai' => $jadwal->waktu_selesai,
                    'lokasi' => $jadwal->ruangan ? ($jadwal->ruangan->kode_ruangan . ' - ' . $jadwal->ruangan->nama_ruangan) : 'Tidak ditentukan',
                    'ruangan_id' => $jadwal->ruangan_id,
                    'ruangan_nama' => $jadwal->ruangan ? $jadwal->ruangan->nama_ruangan : null,
                    'ruangan_kode' => $jadwal->ruangan ? $jadwal->ruangan->kode_ruangan : null,
                    'kapasitas_peserta' => $jadwal->kapasitas_peserta,
                    'created_at' => $jadwal->created_at,
                    'creator' => $jadwal->user->name ?? 'Staff',
                    'model_id' => $jadwal->id,
                    'model_type' => 'kantor'
                ];
            });
            
            $totalData = $kegiatanTerbaru->count();
            
            // Data bulan untuk filter
            $months = [
                '' => 'Semua Bulan',
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            
            // Data tahun untuk filter
            $years = [];
            $currentYearValue = Carbon::now()->year;
            for ($i = $currentYearValue - 5; $i <= $currentYearValue + 5; $i++) {
                $years[$i] = $i;
            }
            
            // Data ruangan untuk filter
            $ruanganList = Ruangan::orderBy('nama_ruangan')->get();
            
            return view('pegawai.semua-kegiatan', compact(
                'kegiatanTerbaru', 
                'totalData', 
                'months',
                'years',
                'ruanganList'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in semuaKegiatan: ' . $e->getMessage());
            return view('pegawai.semua-kegiatan')->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        if ($jadwal->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('pegawai.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Pada method edit() di controller, ubah return view:
public function edit($id)
{
    $jadwal = Jadwal::findOrFail($id);
    
    // Cek apakah user memiliki akses
    if ($jadwal->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
        abort(403, 'Anda tidak memiliki izin untuk mengedit jadwal ini.');
    }
    
    // Ambil daftar ruangan dari database
    $ruanganList = Ruangan::where('status', 'tersedia')
        ->orWhere('id', $jadwal->ruangan_id)
        ->orderBy('kode_ruangan')
        ->get();

    // GANTI INI: dari 'pegawai.jadwal.edit' menjadi 'pegawai.jadwal-edit'
    return view('pegawai.jadwal-edit', compact('jadwal', 'ruanganList'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        // Authorization check
        if ($jadwal->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit jadwal ini.'
                ], 403);
            }
            return back()->with('error', 'Anda tidak memiliki izin untuk mengedit jadwal ini.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'ruangan_id' => 'required|exists:ruangan,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kapasitas_peserta' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);


        // Validasi ruangan tersedia
        $ruangan = Ruangan::find($request->ruangan_id);
        if (!$ruangan) {
            return back()->withErrors(['ruangan_id' => 'Ruangan tidak ditemukan'])->withInput();
        }

        // Cek status ruangan (kecuali jika ruangan yang sama)
        if ($ruangan->status != 'tersedia' && $request->ruangan_id != $jadwal->ruangan_id) {
            return back()->withErrors(['ruangan_id' => 'Ruangan tidak tersedia'])->withInput();
        }

        // Cek kapasitas ruangan
        if ($request->kapasitas_peserta && $request->kapasitas_peserta > $ruangan->kapasitas) {
            return back()->withErrors(['kapasitas_peserta' => 'Kapasitas melebihi kapasitas ruangan (Maks: ' . $ruangan->kapasitas . ')'])->withInput();
        }

        // Validasi tanggal tidak boleh di masa lalu
        if (Carbon::parse($request->tanggal) < today()) {
            return back()->withErrors(['tanggal_mulai' => 'Tanggal_mulai tidak boleh di masa lalu'])->withInput();
        }

        // Validasi konflik jadwal
        $konflikJadwal = Jadwal::where('id', '!=', $jadwal->id)
            ->where('ruangan_id', $request->ruangan_id)
            ->where('tanggal_mulai', $request->tanggal_mulai)
            ->where(function($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                      ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai])
                      ->orWhere(function($q) use ($request) {
                          $q->where('waktu_mulai', '<', $request->waktu_mulai)
                            ->where('waktu_selesai', '>', $request->waktu_selesai);
                      });
            })
            ->exists();

        if ($konflikJadwal) {
            return back()->withErrors(['waktu_mulai' => 'Jadwal bertabrakan dengan jadwal lain di ruangan yang sama'])->withInput();
        }

        $jadwal->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui!'
            ]);
        }

        return redirect()->route('pegawai.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        if ($jadwal->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus jadwal ini'
                ], 403);
            }
            abort(403);
        }
        
        try {
            $jadwal->delete();
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal berhasil dihapus!'
                ]);
            }
            
            return redirect()->route('pegawai.jadwal.index')
                ->with('success', 'Jadwal berhasil dihapus!');
                
        } catch (\Exception $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('pegawai.jadwal.index')
                ->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Get jadwal data untuk AJAX
     */
    public function getJadwalData($id)
    {
        $jadwal = Jadwal::with('ruangan')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'jadwal' => $jadwal
        ]);
    }
}