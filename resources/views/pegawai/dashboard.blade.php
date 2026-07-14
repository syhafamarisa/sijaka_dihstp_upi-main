@extends('layouts.user')

@section('title', 'Dashboard Pegawai - Scheduler')
@section('page-title', 'Dashboard Pegawai')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 mt-2">Kelola jadwal kegiatan dan aktivitas kantor</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Hari ini</p>
                <p class="text-lg font-semibold text-primary-600">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    @if(isset($error))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <?php
            // Set default values jika variabel tidak ada
            $todayJadwal = $todayJadwal ?? 0;
            $totalJadwal = $totalJadwal ?? 0;
            $jadwalBerlangsung = $jadwalBerlangsung ?? 0;
            $jadwalAkanDatang = $jadwalAkanDatang ?? 0;
        ?>

        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-100">Jadwal Hari Ini</p>
                    <p class="text-3xl font-bold mt-2">{{ $todayJadwal }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100">Sedang Berlangsung</p>
                    <p class="text-3xl font-bold mt-2">{{ $jadwalBerlangsung }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-play-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100">Akan Datang</p>
                    <p class="text-3xl font-bold mt-2">{{ $jadwalAkanDatang }}</p>
                </div>
                <div class="w-12 h-12 bg-green-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100">Total Jadwal</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalJadwal }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini & Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Jadwal Hari Ini</h2>
                <span class="px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            
            <?php
                // Set default jika variabel tidak ada
                $todayJadwalList = $todayJadwalList ?? collect([]);
            ?>
            
            @if($todayJadwalList->count() > 0)
            <div class="space-y-4">
                @foreach($todayJadwalList as $jadwal)
                @php
                    $currentTime = \Carbon\Carbon::now();
                    $jamMulai = \Carbon\Carbon::parse($jadwal->waktu_mulai);
                    $jamSelesai = \Carbon\Carbon::parse($jadwal->waktu_selesai);
                    
                    if ($currentTime->between($jamMulai, $jamSelesai)) {
                        $status = 'Berlangsung';
                        $bgColor = 'bg-blue-50';
                        $borderColor = 'border-blue-500';
                        $textColor = 'text-blue-800';
                        $iconBgColor = 'bg-blue-100';
                        $iconColor = 'text-blue-600';
                        $icon = 'fa-play-circle';
                    } elseif ($currentTime->lt($jamMulai)) {
                        $status = 'Akan Datang';
                        $bgColor = 'bg-green-50';
                        $borderColor = 'border-green-500';
                        $textColor = 'text-green-800';
                        $iconBgColor = 'bg-green-100';
                        $iconColor = 'text-green-600';
                        $icon = 'fa-clock';
                    } else {
                        $status = 'Selesai';
                        $bgColor = 'bg-gray-50';
                        $borderColor = 'border-gray-500';
                        $textColor = 'text-gray-800';
                        $iconBgColor = 'bg-gray-100';
                        $iconColor = 'text-gray-600';
                        $icon = 'fa-check-circle';
                    }
                    
                    // Tentukan ikon berdasarkan nama kegiatan
                    $namaKegiatan = strtolower($jadwal->nama_kegiatan);
                    if (str_contains($namaKegiatan, 'rapat') || str_contains($namaKegiatan, 'meeting')) {
                        $kegiatanIcon = 'fa-users';
                    } elseif (str_contains($namaKegiatan, 'training') || str_contains($namaKegiatan, 'pelatihan')) {
                        $kegiatanIcon = 'fa-chalkboard-teacher';
                    } elseif (str_contains($namaKegiatan, 'presentasi') || str_contains($namaKegiatan, 'presentation')) {
                        $kegiatanIcon = 'fa-presentation';
                    } elseif (str_contains($namaKegiatan, 'maint') || str_contains($namaKegiatan, 'maintenance')) {
                        $kegiatanIcon = 'fa-tools';
                    } else {
                        $kegiatanIcon = 'fa-calendar-alt';
                    }
                @endphp
                
                <div class="flex items-center justify-between p-4 {{ $bgColor }} rounded-lg border-l-4 {{ $borderColor }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $iconBgColor }} rounded-full flex items-center justify-center">
                            <i class="fas {{ $kegiatanIcon }} {{ $iconColor }}"></i>
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-800">{{ $jadwal->nama_kegiatan }}</p>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} • 
                                {{ $jadwal->lokasi }}
                            </p>
                            @if($jadwal->deskripsi)
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($jadwal->deskripsi, 50) }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="px-3 py-1 {{ $iconBgColor }} {{ $textColor }} text-sm rounded-full">
                        {{ $status }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Tidak ada jadwal untuk hari ini</p>
                <a href="{{ route('pegawai.buat-jadwal') }}" class="inline-block mt-4 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Buat Jadwal Baru
                </a>
            </div>
            @endif
            
            @if($todayJadwalList->count() > 0)
            <div class="mt-6">
                <a href="{{ route('pegawai.jadwal-staff') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
                    <i class="fas fa-list mr-2"></i>
                    Lihat Semua Jadwal
                </a>
            </div>
            @endif
        </div>

        <!-- Jadwal Mendatang & Ringkasan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Jadwal Mendatang</h2>
                <a href="{{ route('pegawai.buat-jadwal') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    <i class="fas fa-plus mr-1"></i>Tambah Jadwal
                </a>
            </div>
            
            <?php
                // Set default jika variabel tidak ada
                $upcomingJadwal = $upcomingJadwal ?? collect([]);
            ?>
            
            @if($upcomingJadwal->count() > 0)
            <div class="space-y-3 mb-6">
                @foreach($upcomingJadwal as $jadwal)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition duration-150">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-primary-600 text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-800 text-sm">{{ $jadwal->nama_kegiatan }}</p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M') }} • 
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $jadwal->lokasi }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-6">
                <i class="fas fa-calendar-alt text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500 text-sm">Tidak ada jadwal mendatang</p>
            </div>
            @endif
            
            <!-- Ringkasan Minggu Ini -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-4">Ringkasan Minggu Ini</h3>
                
                <?php
                    // Set default jika variabel tidak ada
                    $daysOfWeek = $daysOfWeek ?? [];
                    $weeklyStats = $weeklyStats ?? collect([]);
                ?>
                
                <div class="grid grid-cols-7 gap-2">
                    @foreach($daysOfWeek as $day)
                    <div class="text-center">
                        <div class="text-xs text-gray-500 mb-1">{{ $day['day_name'] }}</div>
                        <div class="relative">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center 
                                {{ $day['is_today'] ? 'bg-primary-100 text-primary-600 font-semibold' : 
                                  ($day['count'] > 0 ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500') }}">
                                {{ \Carbon\Carbon::parse($day['date'])->format('d') }}
                            </div>
                            @if($day['count'] > 0)
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs">{{ $day['count'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <p>Total kegiatan minggu ini: <span class="font-semibold">{{ $weeklyStats->sum('count') }}</span></p>
                </div>
            </div>
            
            <!-- Ruangan Terpopuler -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Ruangan Terpopuler</h3>
                
                <?php
                    // Set default jika variabel tidak ada
                    $popularRuangan = $popularRuangan ?? collect([]);
                ?>
                
                @if($popularRuangan->count() > 0)
                <div class="space-y-3">
                    @foreach($popularRuangan as $ruangan)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700">{{ $ruangan->nama_ruangan }} ({{ $ruangan->kode_ruangan }})</span>
                            <span class="font-medium text-primary-600">{{ $ruangan->jadwal_count }} kegiatan</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $maxCount = max($popularRuangan->max('jadwal_count'), 1);
                                $percentage = ($ruangan->jadwal_count / $maxCount) * 100;
                            @endphp
                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-sm">Belum ada data ruangan</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection