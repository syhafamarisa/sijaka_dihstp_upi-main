@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
@if(isset($completedNotifications) && count($completedNotifications) > 0)
<div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg shadow-sm">
    <div class="flex items-center justify-between mb-2">
        <h4 class="font-bold flex items-center text-blue-900">
            <i class="fas fa-bell mr-2 text-blue-600"></i>
            Notifikasi Sistem: Peminjaman Selesai
        </h4>
    </div>
    <div class="space-y-2 text-sm text-blue-800">
        @foreach($completedNotifications as $notif)
            <div class="flex items-start justify-between border-b border-blue-100 pb-2 last:border-b-0 last:pb-0">
                <p>{{ $notif['message'] }} <span class="font-semibold">(Status: Selesai)</span></p>
                <span class="text-xs text-blue-600 whitespace-nowrap ml-4">{{ $notif['time']->diffForHumans() }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- ===== STATISTICS ===== -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-primary-500 w-full">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <h3 class="text-sm md:text-lg font-semibold text-gray-600">Total Users</h3>
                <p class="text-2xl md:text-3xl font-bold text-primary-600">{{ $totalUsers ?? 0 }}</p>
            </div>
            <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 bg-primary-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-primary-600 text-lg md:text-xl"></i>
            </div>
        </div>
        <p class="text-xs md:text-sm text-gray-500 mt-2">User terdaftar</p>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500 w-full">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <h3 class="text-sm md:text-lg font-semibold text-gray-600">Total Pegawai</h3>
                <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $totalPegawai ?? 0 }}</p>
            </div>
            <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-green-600 text-lg md:text-xl"></i>
            </div>
        </div>
        <p class="text-xs md:text-sm text-gray-500 mt-2">Pegawai aktif</p>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500 w-full">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <h3 class="text-sm md:text-lg font-semibold text-gray-600">Peminjaman Ruangan</h3>
                <p class="text-2xl md:text-3xl font-bold text-purple-600">{{ $totalPeminjamanRuangan ?? 0 }}</p>
            </div>
            <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-door-open text-purple-600 text-lg md:text-xl"></i>
            </div>
        </div>
        <p class="text-xs md:text-sm text-gray-500 mt-2">Ruangan disetujui</p>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500 w-full">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <h3 class="text-sm md:text-lg font-semibold text-gray-600">Peminjaman Vidotron</h3>
                <p class="text-2xl md:text-3xl font-bold text-blue-600">{{ $totalPeminjamanVidotron ?? 0 }}</p>
            </div>
            <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-video text-blue-600 text-lg md:text-xl"></i>
            </div>
        </div>
        <p class="text-xs md:text-sm text-gray-500 mt-2">Vidotron disetujui</p>
    </div>
</div>

<!-- ===== QUICK ACTIONS ===== -->
<div class="bg-white p-4 md:p-6 rounded-lg shadow mb-8 w-full">
    <h2 class="text-lg md:text-xl font-semibold mb-4 text-primary-900">Quick Actions</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-primary-50 hover:bg-primary-100 p-3 md:p-4 rounded-lg border border-primary-200 transition-all duration-300 hover:scale-105">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users-cog text-white text-sm md:text-base"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-semibold text-primary-800 text-sm md:text-base">Manajemen Akun</h3>
                    <p class="text-xs md:text-sm text-primary-600 truncate">Kelola akun pegawai & admin</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.peminjaman-ruangan.index') }}" class="bg-green-50 hover:bg-green-100 p-3 md:p-4 rounded-lg border border-green-200 transition-all duration-300 hover:scale-105">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-door-open text-white text-sm md:text-base"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-semibold text-green-800 text-sm md:text-base">Peminjaman Ruangan</h3>
                    <p class="text-xs md:text-sm text-green-600 truncate">Kelola peminjaman ruangan</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.jadwal-peminjaman') }}" class="bg-purple-50 hover:bg-purple-100 p-3 md:p-4 rounded-lg border border-purple-200 transition-all duration-300 hover:scale-105">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-alt text-white text-sm md:text-base"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-semibold text-purple-800 text-sm md:text-base">Jadwal Kegiatan</h3>
                    <p class="text-xs md:text-sm text-purple-600 truncate">Lihat jadwal kegiatan pegawai</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ===== RECENT ACTIVITY ===== -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
    <!-- Jadwal yang Disetujui -->
    <div class="bg-white p-4 md:p-6 rounded-lg shadow w-full">
        <h3 class="text-lg font-semibold mb-4 text-primary-900">Jadwal yang Disetujui</h3>
        <div class="space-y-3">
            @if($jadwalDisetujui && $jadwalDisetujui->count() > 0)
                @foreach($jadwalDisetujui as $jadwal)
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-door-open text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate text-sm">{{ $jadwal->ruangan->nama_ruangan ?? 'Ruangan' }}</p>
                            <p class="text-xs md:text-sm text-gray-600 truncate">
                                {{ $jadwal->nama_pengusul ?? ($jadwal->user->name ?? 'Tidak diketahui') }} | 
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full flex-shrink-0 ml-2">
                        {{ substr($jadwal->jam_mulai, 0, 5) }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-door-closed text-3xl text-gray-400 mb-2"></i>
                    <p>Tidak ada jadwal ruangan yang disetujui</p>
                </div>
            @endif
            
            @if($vidotronDisetujui && $vidotronDisetujui->count() > 0)
                @foreach($vidotronDisetujui as $vidotron)
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-video text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate text-sm">Video Trone</p>
                            <p class="text-xs md:text-sm text-gray-600 truncate">
                                {{ $vidotron->nama_pengusul ?? ($vidotron->user->name ?? 'Tidak diketahui') }} | 
                                {{ \Carbon\Carbon::parse($vidotron->tanggal_mulai)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full flex-shrink-0 ml-2">
                        {{ substr($vidotron->waktu_mulai, 0, 5) }}
                    </span>
                </div>
                @endforeach
            @endif
            
            @if(($jadwalDisetujui && $jadwalDisetujui->count() == 0) && ($vidotronDisetujui && $vidotronDisetujui->count() == 0))
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                    <p>Belum ada jadwal yang disetujui</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white p-4 md:p-6 rounded-lg shadow w-full">
        <h3 class="text-lg font-semibold mb-4 text-primary-900">Pengguna Terbaru</h3>
        <div class="space-y-3">
            @if($recentUsers && $recentUsers->count() > 0)
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center flex-shrink-0">
                            @php
                                $initials = '';
                                if (!empty($user->name)) {
                                    $names = explode(' ', $user->name);
                                    foreach ($names as $name) {
                                        if (!empty($name)) {
                                            $initials .= strtoupper(substr($name, 0, 1));
                                            if (strlen($initials) >= 2) break;
                                        }
                                    }
                                }
                                $initials = $initials ?: substr($user->name ?? 'U', 0, 1);
                            @endphp
                            <span class="text-white text-sm font-medium">{{ $initials }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate text-sm">{{ $user->name ?? 'Tidak diketahui' }}</p>
                            <p class="text-xs md:text-sm text-gray-600 truncate">{{ $user->email ?? 'Email tidak tersedia' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role == 'pegawai' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }} text-xs rounded-full flex-shrink-0 ml-2">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-users text-3xl text-gray-400 mb-2"></i>
                    <p>Belum ada pengguna terdaftar</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ===== JADWAL MENDATANG ===== -->
@if($jadwalMendatang && $jadwalMendatang->count() > 0)
<div class="bg-white p-4 md:p-6 rounded-lg shadow mt-4 md:mt-6 w-full">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
        <h2 class="text-lg md:text-xl font-semibold text-primary-900">Jadwal Mendatang</h2>
        <a href="{{ route('admin.jadwal-peminjaman') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-3 sm:px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                    <th class="px-3 sm:px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Acara</th>
                    <th class="px-3 sm:px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-3 sm:px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-3 sm:px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Pemohon</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($jadwalMendatang as $jadwal)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                        <span class="font-medium text-gray-900 text-sm">{{ $jadwal->ruangan->nama_ruangan ?? 'N/A' }}</span>
                    </td>
                    <td class="px-3 sm:px-4 py-3 hidden sm:table-cell">
                        <span class="text-gray-900 text-sm">{{ $jadwal->acara ?? 'Tidak diketahui' }}</span>
                    </td>
                    <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                        <span class="text-gray-900 text-sm">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</span>
                    </td>
                    <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                        <span class="text-gray-900 text-sm">{{ substr($jadwal->jam_mulai ?? '00:00', 0, 5) }} - {{ substr($jadwal->jam_selesai ?? '00:00', 0, 5) }}</span>
                    </td>
                    <td class="px-3 sm:px-4 py-3 whitespace-nowrap hidden md:table-cell">
                        <span class="text-gray-900 text-sm">{{ $jadwal->nama_pengusul ?? ($jadwal->user->name ?? 'N/A') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection