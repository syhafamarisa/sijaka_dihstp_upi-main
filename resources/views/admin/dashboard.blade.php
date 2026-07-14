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

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-primary-500">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-600">Total Users</h3>
                <p class="text-3xl font-bold text-primary-600">{{ $totalUsers ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-primary-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">User terdaftar</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-600">Total Pegawai</h3>
                <p class="text-3xl font-bold text-green-600">{{ $totalPegawai ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-green-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Pegawai aktif</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-600">Peminjaman Ruangan</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $totalPeminjamanRuangan ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-door-open text-purple-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Ruangan disetujui</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-600">Peminjaman Vidotron</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $totalPeminjamanVidotron ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-video text-blue-600"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Vidotron disetujui</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h2 class="text-xl font-semibold mb-4 text-primary-900">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-primary-50 hover:bg-primary-100 p-4 rounded-lg border border-primary-200 transition-all duration-300 hover:scale-105">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-users-cog text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-800">Manajemen Akun</h3>
                    <p class="text-sm text-primary-600">Kelola akun pegawai & admin</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.peminjaman-ruangan.index') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg border border-green-200 transition-all duration-300 hover:scale-105">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-door-open text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-green-800">Peminjaman Ruangan</h3>
                    <p class="text-sm text-green-600">Kelola peminjaman ruangan</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.jadwal-peminjaman') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg border border-purple-200 transition-all duration-300 hover:scale-105">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-purple-800">Jadwal Kegiatan</h3>
                    <p class="text-sm text-purple-600">Lihat jadwal kegiatan pegawai</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Jadwal yang Disetujui -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-primary-900">Jadwal yang Disetujui</h3>
        <div class="space-y-4">
            @if($jadwalDisetujui && $jadwalDisetujui->count() > 0)
                @foreach($jadwalDisetujui as $jadwal)
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-door-open text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $jadwal->ruangan->nama_ruangan ?? 'Ruangan' }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $jadwal->nama_pengusul ?? ($jadwal->user->name ?? 'Tidak diketahui') }} | 
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
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
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-video text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">Video Trone</p>
                            <p class="text-sm text-gray-600">
                                {{ $vidotron->nama_pengusul ?? ($vidotron->user->name ?? 'Tidak diketahui') }} | 
                                {{ \Carbon\Carbon::parse($vidotron->tanggal_mulai)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
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
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-primary-900">Pengguna Terbaru</h3>
        <div class="space-y-3">
            @if($recentUsers && $recentUsers->count() > 0)
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
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
                            <p class="font-medium text-gray-800 truncate">{{ $user->name ?? 'Tidak diketahui' }}</p>
                            <p class="text-sm text-gray-600 truncate">{{ $user->email ?? 'Email tidak tersedia' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role == 'pegawai' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }} text-xs rounded-full whitespace-nowrap">
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

<!-- Jadwal Mendatang -->
@if($jadwalMendatang && $jadwalMendatang->count() > 0)
<div class="bg-white p-6 rounded-lg shadow mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-primary-900">Jadwal Mendatang</h2>
        <a href="{{ route('admin.jadwal-peminjaman') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acara</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($jadwalMendatang as $jadwal)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="font-medium text-gray-900">{{ $jadwal->ruangan->nama_ruangan ?? 'N/A' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-gray-900">{{ $jadwal->acara ?? 'Tidak diketahui' }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="text-gray-900">{{ substr($jadwal->jam_mulai ?? '00:00', 0, 5) }} - {{ substr($jadwal->jam_selesai ?? '00:00', 0, 5) }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="text-gray-900">{{ $jadwal->nama_pengusul ?? ($jadwal->user->name ?? 'N/A') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Auto refresh setiap 60 detik
setTimeout(() => {
    location.reload();
}, 60000);
</script>
@endpush