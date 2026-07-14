@extends('layouts.user')

@section('title', 'Lihat Jadwal - Scheduler')

@section('page-title', 'Lihat Jadwal')

@section('content')
@php
    // Helper functions sebagai array methods
    $helpers = [
        'getKegiatanName' => function($data) {
            if (isset($data->acara)) return $data->acara;
            if (isset($data->tujuan_pemasangan)) return $data->tujuan_pemasangan;
            if (isset($data->nama_kegiatan)) return $data->nama_kegiatan;
            if (isset($data->keperluan)) return $data->keperluan;
            return 'Kegiatan';
        },
        
        'getDeskripsi' => function($data) {
            if (isset($data->keterangan)) return $data->keterangan;
            if (isset($data->deskripsi_konten)) return $data->deskripsi_konten;
            if (isset($data->tujuan_pemasangan)) return $data->tujuan_pemasangan;
            if (isset($data->deskripsi)) return $data->deskripsi;
            return '-';
        },
        
        'getTanggalMulai' => function($data) {
            if (isset($data->tanggal_mulai)) return $data->tanggal_mulai;
            if (isset($data->tanggal)) return $data->tanggal;
            return $data->created_at;
        },
        
        'getTanggalSelesai' => function($data) {
            if (isset($data->tanggal_selesai)) return $data->tanggal_selesai;
            if (isset($data->tanggal_mulai)) return $data->tanggal_mulai;
            return $data->created_at;
        },
        
        'getWaktuMulai' => function($data) {
            if (isset($data->waktu_mulai)) return $data->waktu_mulai;
            if (isset($data->jam_mulai)) return $data->jam_mulai;
            if (isset($data->start_time)) return $data->start_time;
            return null;
        },
        
        'getWaktuSelesai' => function($data) {
            if (isset($data->waktu_selesai)) return $data->waktu_selesai;
            if (isset($data->jam_selesai)) return $data->jam_selesai;
            if (isset($data->end_time)) return $data->end_time;
            return null;
        },
        
        'getLokasiRuangan' => function($data) {
            if (isset($data->ruangan->nama_ruangan)) return $data->ruangan->nama_ruangan;
            if (isset($data->nama_ruangan)) return $data->nama_ruangan;
            if (isset($data->ruangan_id)) return 'Ruangan ' . $data->ruangan_id;
            if (isset($data->lokasi)) return $data->lokasi;
            return 'Ruangan';
        },
        
        'getStatus' => function($data) {
            if (isset($data->status)) return $data->status;
            return 'menunggu';
        },
        
        'getDivisi' => function($data) {
            if (isset($data->fakultas)) return $data->fakultas;
            if (isset($data->program_studi)) return $data->program_studi;
            if (isset($data->divisi)) return $data->divisi;
            if (isset($data->departemen)) return $data->departemen;
            return 'Umum';
        }
    ];

    // Query berdasarkan struktur tabel
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $currentMonth = request('month') ? intval(request('month')) : \Carbon\Carbon::now()->month;
    $currentYear = request('year') ? intval(request('year')) : \Carbon\Carbon::now()->year;
    $dateObj = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
    $prevMonth = $dateObj->copy()->subMonth();
    $nextMonth = $dateObj->copy()->addMonth();
    
    // Ambil data peminjaman ruangan untuk bulan ini
    $peminjamanRuangan = \App\Models\PeminjamanRuangan::with('ruangan')
        ->where(function($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('tanggal_mulai', $currentMonth)
                  ->orWhereMonth('tanggal_selesai', $currentMonth)
                  ->orWhere(function($q) use ($currentMonth, $currentYear) {
                      $startOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
                      $endOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();
                      
                      $q->where('tanggal_mulai', '<=', $startOfMonth)
                        ->where('tanggal_selesai', '>=', $endOfMonth);
                  });
        })
        ->get();

    // Ambil data peminjaman vidotron untuk bulan ini
    $penyewaanVidotron = \App\Models\PenyewaanVidotron::where(function($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('tanggal_mulai', $currentMonth)
                  ->orWhereMonth('tanggal_selesai', $currentMonth)
                  ->orWhere(function($q) use ($currentMonth, $currentYear) {
                      $startOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
                      $endOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();
                      
                      $q->where('tanggal_mulai', '<=', $startOfMonth)
                        ->where('tanggal_selesai', '>=', $endOfMonth);
                  });
        })
        ->get();

    // Gabungkan semua kegiatan
    $allKegiatan = collect([]);
    
    foreach ($peminjamanRuangan as $peminjaman) {
        $tanggalMulai = \Carbon\Carbon::parse($helpers['getTanggalMulai']($peminjaman));
        $tanggalSelesai = \Carbon\Carbon::parse($helpers['getTanggalSelesai']($peminjaman));
        
        // Tambahkan event untuk setiap hari dalam rentang tanggal
        for ($date = $tanggalMulai->copy(); $date->lte($tanggalSelesai); $date->addDay()) {
            if ($date->month == $currentMonth && $date->year == $currentYear) {
                $allKegiatan->push([
                    'id' => $peminjaman->id,
                    'type' => 'ruangan',
                    'nama_kegiatan' => $helpers['getKegiatanName']($peminjaman),
                    'deskripsi' => $helpers['getDeskripsi']($peminjaman),
                    'tanggal' => $date->format('Y-m-d'),
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                    'waktu_mulai' => $helpers['getWaktuMulai']($peminjaman),
                    'waktu_selesai' => $helpers['getWaktuSelesai']($peminjaman),
                    'lokasi' => $helpers['getLokasiRuangan']($peminjaman),
                    'status' => $helpers['getStatus']($peminjaman),
                    'divisi' => $helpers['getDivisi']($peminjaman),
                    'created_at' => $peminjaman->created_at
                ]);
            }
        }
    }
    
    foreach ($penyewaanVidotron as $vidotron) {
        $tanggalMulai = \Carbon\Carbon::parse($helpers['getTanggalMulai']($vidotron));
        $tanggalSelesai = \Carbon\Carbon::parse($helpers['getTanggalSelesai']($vidotron));
        
        // Tambahkan event untuk setiap hari dalam rentang tanggal
        for ($date = $tanggalMulai->copy(); $date->lte($tanggalSelesai); $date->addDay()) {
            if ($date->month == $currentMonth && $date->year == $currentYear) {
                $allKegiatan->push([
                    'id' => $vidotron->id,
                    'type' => 'vidotron',
                    'nama_kegiatan' => $helpers['getKegiatanName']($vidotron),
                    'deskripsi' => $helpers['getDeskripsi']($vidotron),
                    'tanggal' => $date->format('Y-m-d'),
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                    'waktu_mulai' => $helpers['getWaktuMulai']($vidotron),
                    'waktu_selesai' => $helpers['getWaktuSelesai']($vidotron),
                    'lokasi' => 'Video Trone',
                    'status' => $helpers['getStatus']($vidotron),
                    'divisi' => $helpers['getDivisi']($vidotron),
                    'created_at' => $vidotron->created_at
                ]);
            }
        }
    }

    // Urutkan berdasarkan tanggal
    $allKegiatan = $allKegiatan->sortBy('tanggal');
    
    // Kegiatan hari ini
    $kegiatanHariIni = $allKegiatan->filter(function($kegiatan) use ($today) {
        return $kegiatan['tanggal'] == $today;
    })->unique('id');
    
    // Kegiatan terbaru (untuk tabel) - ambil yang unik berdasarkan ID
    $kegiatanTerbaru = $allKegiatan->unique('id')->take(4);
    
    // Data untuk kalender
    $calendarEvents = [];
    foreach ($allKegiatan as $kegiatan) {
        $day = \Carbon\Carbon::parse($kegiatan['tanggal'])->day;
        if (!isset($calendarEvents[$day])) {
            $calendarEvents[$day] = [];
        }
        if (!collect($calendarEvents[$day])->contains('id', $kegiatan['id'])) {
            $calendarEvents[$day][] = $kegiatan;
        }
    }
    
    // Helper functions untuk styling
    $stylingHelpers = [
        'getStatusColor' => function($status) {
            switch (strtolower($status)) {
                case 'disetujui':
                case 'approved':
                case 'diterima':
                    return 'bg-green-100 text-green-800';
                case 'menunggu':
                case 'pending':
                case 'proses':
                    return 'bg-yellow-100 text-yellow-800';
                case 'ditolak':
                case 'rejected':
                case 'ditolak':
                    return 'bg-red-100 text-red-800';
                case 'berlangsung':
                case 'ongoing':
                    return 'bg-blue-100 text-blue-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        },
        
        'getStatusText' => function($status) {
            switch (strtolower($status)) {
                case 'disetujui':
                case 'approved':
                case 'diterima':
                    return 'Disetujui';
                case 'menunggu':
                case 'pending':
                case 'proses':
                    return 'Menunggu';
                case 'ditolak':
                case 'rejected':
                case 'ditolak':
                    return 'Ditolak';
                case 'berlangsung':
                case 'ongoing':
                    return 'Berlangsung';
                default:
                    return ucfirst($status);
            }
        },
        
        'getEventColor' => function($type) {
            switch ($type) {
                case 'ruangan':
                    return 'bg-blue-100 text-blue-800';
                case 'vidotron':
                    return 'bg-purple-100 text-purple-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }
    ];
@endphp

<div class="max-w-7xl mx-auto">
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Calendar Header dengan tombol Lihat Daftar Kegiatan -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 md:mb-0">Kalender Jadwal</h2>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Tersedia</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Terpakai</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Menunggu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Navigation -->
        <div class="flex items-center justify-between mb-6">
            <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </a>
            
            <h3 class="text-xl font-semibold text-gray-800">{{ $dateObj->translatedFormat('F Y') }}</h3>
            
            <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chevron-right text-gray-600"></i>
            </a>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2 mb-4">
            <!-- Day Headers -->
            <div class="text-center text-sm font-medium text-gray-500 py-2">Minggu</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Senin</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Selasa</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Rabu</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Kamis</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Jumat</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Sabtu</div>

            @php
                $daysInMonth = $dateObj->daysInMonth;
                $firstDayOfMonth = $dateObj->dayOfWeek;
                $currentDay = (\Carbon\Carbon::now()->month == $currentMonth && \Carbon\Carbon::now()->year == $currentYear) ? \Carbon\Carbon::now()->day : 0;
            @endphp

            <!-- Empty days for first week -->
            @for($i = 0; $i < $firstDayOfMonth; $i++)
                <div class="h-24 border border-gray-200 rounded-lg"></div>
            @endfor

            <!-- Calendar Days -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $hasEvents = isset($calendarEvents[$day]) && count($calendarEvents[$day]) > 0;
                    $isToday = $day == $currentDay;
                    $dayEvents = $calendarEvents[$day] ?? [];
                    
                    // Tentukan status hari berdasarkan events
                    $dayStatus = 'available';
                    $hasPending = false;
                    $hasBooked = false;
                    
                    foreach ($dayEvents as $event) {
                        if ($event['status'] === 'disetujui' || $event['status'] === 'approved') {
                            $hasBooked = true;
                        } elseif ($event['status'] === 'menunggu' || $event['status'] === 'pending') {
                            $hasPending = true;
                        }
                    }
                    
                    if ($hasBooked) {
                        $dayStatus = 'booked';
                    } elseif ($hasPending) {
                        $dayStatus = 'pending';
                    }
                @endphp
                
                <div class="h-24 border border-gray-200 rounded-lg p-2 hover:bg-gray-50 cursor-pointer 
                    {{ $dayStatus == 'booked' ? 'bg-red-50 border-red-200' : '' }}
                    {{ $dayStatus == 'pending' ? 'bg-yellow-50 border-yellow-200' : '' }}
                    {{ $dayStatus == 'available' ? 'bg-green-50 border-green-200' : '' }}
                    {{ $isToday ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium 
                            {{ $dayStatus == 'booked' ? 'text-red-700' : '' }}
                            {{ $dayStatus == 'pending' ? 'text-yellow-700' : '' }}
                            {{ $dayStatus == 'available' ? 'text-green-700' : '' }}
                            {{ $isToday ? 'text-blue-700' : '' }}">
                            {{ $day }}
                        </span>
                        @if($dayStatus == 'booked')
                            <i class="fas fa-times text-red-500 text-xs"></i>
                        @elseif($dayStatus == 'pending')
                            <i class="fas fa-clock text-yellow-500 text-xs"></i>
                        @else
                            <i class="fas fa-check text-green-500 text-xs"></i>
                        @endif
                    </div>
                    
                    <!-- Events for the day -->
                    @foreach(array_slice($dayEvents, 0, 2) as $event)
                        <div class="mt-1 text-xs {{ $stylingHelpers['getEventColor']($event['type']) }} px-1 py-0.5 rounded truncate">
                            {{ $event['type'] == 'ruangan' ? $event['lokasi'] : 'Video Trone' }}
                        </div>
                    @endforeach
                    
                    @if(count($dayEvents) > 2)
                        <div class="mt-1 text-xs text-gray-500 px-1 py-0.5">
                            +{{ count($dayEvents) - 2 }} lainnya
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Jadwal Hari Ini - {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($kegiatanHariIni->count() > 0)
                    @foreach($kegiatanHariIni as $kegiatan)
                        @php
                            $waktu = $kegiatan['waktu_mulai'] ? 
                                \Carbon\Carbon::parse($kegiatan['waktu_mulai'])->format('H:i') . ' - ' . \Carbon\Carbon::parse($kegiatan['waktu_selesai'])->format('H:i') : 
                                'Seluruh Hari';
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-800">{{ $kegiatan['type'] == 'ruangan' ? $kegiatan['lokasi'] : 'Video Trone' }}</h4>
                                <span class="px-2 py-1 text-xs rounded font-medium
                                    {{ $kegiatan['status'] == 'disetujui' || $kegiatan['status'] == 'approved' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                                    {{ $kegiatan['status'] == 'menunggu' || $kegiatan['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                    {{ $kegiatan['status'] == 'ditolak' || $kegiatan['status'] == 'rejected' ? 'bg-gray-100 text-gray-800 border border-gray-200' : '' }}
                                    {{ ($kegiatan['status'] != 'disetujui' && $kegiatan['status'] != 'approved' && $kegiatan['status'] != 'menunggu' && $kegiatan['status'] != 'pending' && $kegiatan['status'] != 'ditolak' && $kegiatan['status'] != 'rejected') ? 'bg-green-100 text-green-800 border border-green-200' : '' }}">
                                    @if($kegiatan['status'] == 'disetujui' || $kegiatan['status'] == 'approved')
                                        Terpakai
                                    @elseif($kegiatan['status'] == 'menunggu' || $kegiatan['status'] == 'pending')
                                        Menunggu
                                    @elseif($kegiatan['status'] == 'ditolak' || $kegiatan['status'] == 'rejected')
                                        Ditolak
                                    @else
                                        Tersedia
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $waktu }}</p>
                            <p class="text-sm text-gray-700 font-medium mt-1">{{ $kegiatan['nama_kegiatan'] }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $kegiatan['deskripsi'] }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                        <i class="fas fa-calendar-day text-4xl mb-3 text-gray-300"></i>
                        <p class="text-lg">Tidak ada jadwal untuk hari ini</p>
                        <p class="text-sm mt-1">Semua ruangan dan vidotron tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex flex-wrap gap-4 justify-between items-center">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('user.peminjaman-ruangan.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center font-medium">
                    <i class="fas fa-door-open mr-2"></i> Pinjam Ruangan
                </a>
                <a href="{{ route('user.peminjaman-video') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center font-medium">
                    <i class="fas fa-video mr-2"></i> Pinjam Video Trone
                </a>
            </div>
            <a href="{{ route('user.daftar-kegiatan') }}" class="text-primary-600 hover:text-primary-800 font-medium flex items-center space-x-2">
                <i class="fas fa-list"></i>
                <span>Lihat Daftar Kegiatan Lengkap</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection