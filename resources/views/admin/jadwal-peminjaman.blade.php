@extends('layouts.admin')

@section('title', 'Jadwal Peminjaman')
@section('page-title', 'Jadwal Peminjaman')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-primary-900 elegant-font">Jadwal Peminjaman</h1>
        <p class="text-gray-600">Lihat jadwal peminjaman terbaru yang telah disetujui</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.semua-jadwal') }}" 
           class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
            <i class="fas fa-list"></i>
            <span>Lihat Semua Jadwal</span>
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Peminjaman Ruangan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ruangan'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-door-open text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-green-600 text-sm">
                <i class="fas fa-check"></i> {{ $stats['disetujui_ruangan'] ?? 0 }} Disetujui
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Penyewaan Vidotron</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_vidotron'] ?? 0 }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-tv text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-green-600 text-sm">
                <i class="fas fa-check"></i> {{ $stats['disetujui_vidotron'] ?? 0 }} Disetujui
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Disetujui</p>
                <p class="text-2xl font-bold text-gray-900">{{ ($stats['disetujui_ruangan'] ?? 0) + ($stats['disetujui_vidotron'] ?? 0) }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Jadwal Terbaru</p>
                <p class="text-2xl font-bold text-gray-900">{{ $recentBookings->count() }}/3</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-clock text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Jadwal Terbaru - HANYA 3 DATA -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-primary-50">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-primary-900">
                <i class="fas fa-history mr-2"></i>
                Jadwal Peminjaman Terbaru
            </h3>
            <span class="text-sm text-gray-500">Menampilkan {{ $recentBookings->count() }} dari 3 jadwal terbaru</span>
        </div>
    </div>

    <div class="divide-y divide-gray-200">
        @if($recentBookings->count() > 0)
            @foreach($recentBookings->take(3) as $booking) <!-- PERUBAHAN: take(3) -->
                @php
                    $isRuangan = isset($booking->ruangan_id);
                    $type = $isRuangan ? 'ruangan' : 'vidotron';
                    $tanggal = $isRuangan ? $booking->tanggal : $booking->tanggal_mulai;
                    $waktu = $isRuangan ? 
                        substr($booking->jam_mulai, 0, 5) . ' - ' . substr($booking->jam_selesai, 0, 5) :
                        substr($booking->waktu_mulai, 0, 5) . ' - ' . substr($booking->waktu_selesai, 0, 5);
                    $acara = $isRuangan ? $booking->acara : $booking->tujuan_pemasangan;
                    $lokasi = $isRuangan ? ($booking->ruangan->nama_ruangan ?? 'Ruangan') : 'Vidotron';
                @endphp

                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center 
                                @if($type == 'ruangan') bg-blue-100 text-blue-600 @else bg-green-100 text-green-600 @endif">
                                <i class="@if($type == 'ruangan') fas fa-door-open @else fas fa-tv @endif"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $acara }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($type == 'ruangan') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif">
                                        {{ $lokasi }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-user text-gray-400"></i>
                                        <span>{{ $booking->user->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                        <span>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-clock text-gray-400"></i>
                                        <span>{{ $waktu }}</span>
                                    </div>
                                </div>
                                @if($isRuangan && $booking->jumlah_peserta)
                                    <div class="mt-2">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-users mr-1"></i>{{ $booking->jumlah_peserta }} peserta
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-medium">
                                Disetujui
                            </span>
                            <div class="mt-2 text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada jadwal peminjaman</p>
                <p class="text-gray-400 text-sm mt-2">Semua peminjaman yang disetujui akan muncul di sini</p>
            </div>
        @endif
    </div>

    <!-- Footer dengan button Lihat Semua -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <div class="flex justify-between items-center">
            <p class="text-sm text-gray-600">
                Menampilkan {{ min($recentBookings->count(), 3) }} jadwal terbaru dari total 
                {{ ($stats['disetujui_ruangan'] ?? 0) + ($stats['disetujui_vidotron'] ?? 0) }} jadwal yang disetujui
            </p>
            <a href="{{ route('admin.semua-jadwal') }}" 
               class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 transition-colors">
                <i class="fas fa-list"></i>
                <span>Lihat Semua Jadwal</span>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-door-open text-blue-600 text-xl"></i>
        </div>
        <h4 class="font-semibold text-gray-900 mb-2">Peminjaman Ruangan</h4>
        <p class="text-gray-600 text-sm mb-4">Kelola semua peminjaman ruangan</p>
        <a href="{{ route('admin.peminjaman-ruangan.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Kelola →
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-tv text-green-600 text-xl"></i>
        </div>
        <h4 class="font-semibold text-gray-900 mb-2">Penyewaan Vidotron</h4>
        <p class="text-gray-600 text-sm mb-4">Kelola semua penyewaan vidotron</p>
        <a href="{{ route('penyewaan-vidotron.index') }}" 
           class="text-green-600 hover:text-green-800 text-sm font-medium">
            Kelola →
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
        </div>
        <h4 class="font-semibold text-gray-900 mb-2">Semua Jadwal</h4>
        <p class="text-gray-600 text-sm mb-4">Lihat seluruh jadwal peminjaman</p>
        <a href="{{ route('admin.semua-jadwal') }}" 
           class="text-purple-600 hover:text-purple-800 text-sm font-medium">
            Lihat Semua →
        </a>
    </div>
</div>
@endsection