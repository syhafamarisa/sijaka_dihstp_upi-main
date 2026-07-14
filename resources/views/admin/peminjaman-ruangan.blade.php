@extends('layouts.admin')

@section('title', 'Admin - Peminjaman Ruangan')
@section('page-title', 'Manajemen Peminjaman Ruangan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header dan Filter -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-3xl font-bold text-primary-900 elegant-font">Manajemen Peminjaman Ruangan</h1>
                <p class="text-gray-600">Kelola semua permintaan peminjaman ruangan</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="updateStatusRealTime()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                    <span>Update Status</span>
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filter-status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                    <select id="filter-ruangan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruangan as $room)
                            <option value="{{ $room->id }}" {{ request('ruangan_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengusul</label>
                    <select id="filter-jenis-pengusul" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Jenis</option>
                        <option value="mahasiswa" {{ request('jenis_pengusul') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ request('jenis_pengusul') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="staff" {{ request('jenis_pengusul') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="tamu" {{ request('jenis_pengusul') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                    <input type="text" id="filter-fakultas" placeholder="Masukkan fakultas" value="{{ request('fakultas') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="filter-tanggal-mulai" value="{{ request('tanggal_mulai') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" id="filter-tanggal-selesai" value="{{ request('tanggal_selesai') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengusul</label>
                    <input type="text" id="filter-nama-pengusul" placeholder="Masukkan nama" value="{{ request('nama_pengusul') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="applyFilters()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
                    <i class="fas fa-filter"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Total</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-door-open text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Menunggu</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu'] }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Disetujui</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Ditolak</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Selesai</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['selesai'] }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-flag-checkered text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Dibatalkan</h3>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['dibatalkan'] }}</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ban text-gray-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold mb-4 text-primary-900 flex items-center">
            <i class="fas fa-clock mr-2 text-yellow-500"></i>
            Permintaan Menunggu Persetujuan
        </h3>
        
        @if($peminjaman->where('status', 'menunggu')->count() > 0)
            <div class="space-y-4">
                @foreach($peminjaman->where('status', 'menunggu') as $item)
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200 transition-colors hover:bg-yellow-100">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-door-open text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item->ruangan->kode_ruangan }} - {{ $item->acara }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $item->nama_pengusul }} 
                                <span class="px-1 py-0.5 bg-blue-100 text-blue-800 text-xs rounded ml-2">
                                    {{ ucfirst($item->jenis_pengusul) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $item->fakultas }} • {{ $item->program_studi ?: 'Tidak ada program_studi' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }} • 
                                {{ $item->jam_mulai }} - {{ $item->jam_selesai }} • 
                                Peserta: {{ $item->jumlah_peserta }} orang
                            </p>
                            @if($item->keterangan)
                                <p class="text-sm text-gray-500 mt-1">Keterangan: {{ $item->keterangan }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.peminjaman-ruangan.approve', $item->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 flex items-center space-x-2 transition-colors" onclick="return confirm('Setujui peminjaman ini?')">
                                <i class="fas fa-check"></i>
                                <span>Setujui</span>
                            </button>
                        </form>
                        <button onclick="showRejectModal({{ $item->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 flex items-center space-x-2 transition-colors">
                            <i class="fas fa-times"></i>
                            <span>Tolak</span>
                        </button>
                        <button onclick="showDetailModal({{ $item->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 flex items-center space-x-2 transition-colors">
                            <i class="fas fa-eye"></i>
                            <span>Detail</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                <p class="text-gray-600">Tidak ada permintaan yang menunggu persetujuan</p>
            </div>
        @endif
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-primary-900">Daftar Semua Peminjaman</h3>
                <div class="flex space-x-2">
                    <input type="text" id="search-input" placeholder="Cari..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors w-64">
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Pengusul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Informasi Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Ruangan & Acara</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="peminjaman-table-body">
                    @foreach($peminjaman as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Kolom pengusul -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    @php
                                        $initial = strtoupper(substr($item->nama_pengusul, 0, 1));
                                    @endphp
                                    <span class="text-white text-sm font-medium">{{ $initial }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $item->nama_pengusul }}</div>
                                    <div class="text-sm text-gray-500">
                                        <span class="px-1 py-0.5 bg-blue-100 text-blue-800 text-xs rounded">
                                            {{ ucfirst($item->jenis_pengusul) }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $item->nim_nip }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Kolom Informasi Kontak -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->fakultas }}</div>
                            <div class="text-sm text-gray-600">{{ $item->program_studi ?: '-' }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $item->email }}</div>
                            <div class="text-xs text-gray-500">{{ $item->no_telepon }}</div>
                        </td>
                        
                        <!-- Kolom Ruangan & Acara -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->ruangan->kode_ruangan }} - {{ $item->ruangan->nama_ruangan }}</div>
                            <div class="text-sm text-gray-900 mt-1">{{ $item->acara }}</div>
                            <div class="text-sm text-gray-500">Peserta: {{ $item->jumlah_peserta }} orang</div>
                            @if($item->lampiran_surat)
                                <a href="{{ route('admin.peminjaman-ruangan.download-surat', $item->id) }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center mt-1 transition-colors">
                                    <i class="fas fa-paperclip mr-1"></i> Surat Peminjaman
                                </a>
                            @endif
                        </td>
                        
                        <!-- Kolom Jadwal -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ $item->hari }}</div>
                            <div class="text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                            <div class="text-sm text-gray-600">{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</div>
                        </td>
                        
                        <!-- Kolom Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusRealTime = $item->status_real_time;
                                $statusConfig = [
                                    'menunggu' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Menunggu'],
                                    'disetujui' => ['color' => 'blue', 'icon' => 'check', 'text' => 'Disetujui'],
                                    'ditolak' => ['color' => 'red', 'icon' => 'times', 'text' => 'Ditolak'],
                                    'dibatalkan' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Dibatalkan'],
                                    'akan_datang' => ['color' => 'green', 'icon' => 'calendar', 'text' => 'Akan Datang'],
                                    'berlangsung' => ['color' => 'purple', 'icon' => 'play', 'text' => 'Berlangsung'],
                                    'selesai' => ['color' => 'indigo', 'icon' => 'flag-checkered', 'text' => 'Selesai']
                                ];
                                $config = $statusConfig[$statusRealTime] ?? $statusConfig[$item->status];
                            @endphp
                            <span class="px-3 py-1 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 text-xs rounded-full flex items-center w-fit">
                                <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                                {{ $config['text'] }}
                            </span>
                            @if($item->status == 'ditolak' && $item->alasan_penolakan)
                                <div class="text-xs text-red-600 mt-1" title="{{ $item->alasan_penolakan }}">
                                    <i class="fas fa-info-circle"></i> Ada alasan penolakan
                                </div>
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showDetailModal({{ $item->id }})" class="text-blue-600 hover:text-blue-900 transition-colors" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($item->status == 'menunggu')
                                    <form action="{{ route('admin.peminjaman-ruangan.approve', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Setujui" onclick="return confirm('Setujui peminjaman ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal({{ $item->id }})" class="text-red-600 hover:text-red-900 transition-colors" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                @if(in_array($item->status, ['menunggu', 'disetujui']))
                                    <form action="{{ route('admin.peminjaman-ruangan.cancel', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors" title="Batalkan" onclick="return confirm('Batalkan peminjaman ini?')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($peminjaman->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-door-open text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Belum ada data peminjaman ruangan</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Detail Peminjaman Ruangan</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]" id="detailContent">
            <!-- Content will be loaded via AJAX -->
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Tolak Peminjaman</h3>
            </div>
            <div class="p-6">
                <input type="hidden" name="peminjaman_id" id="rejectPeminjamanId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="Berikan alasan penolakan..." required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Tolak Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.fixed {
    position: fixed;
}
.inset-0 {
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
.hidden {
    display: none;
}
.z-50 {
    z-index: 50;
}
.max-h-\[90vh\] {
    max-height: 90vh;
}
.max-h-\[calc\(90vh-120px\)\] {
    max-height: calc(90vh - 120px);
}
</style>
@endpush

@push('scripts')
<script>
// Deklarasi fungsi di global scope
window.showDetailModal = function(id) {
    console.log('Opening modal for ID:', id);
    
    // Show loading state
    document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600">Memuat data...</span>
        </div>
    `;
    
    document.getElementById('detailModal').classList.remove('hidden');
    
    fetch(`/admin/peminjaman-ruangan/${id}/detail`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            if (data.success) {
                document.getElementById('detailContent').innerHTML = data.html;
            } else {
                throw new Error(data.message || 'Gagal memuat data');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('detailContent').innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                    <p>Gagal memuat detail peminjaman</p>
                    <p class="text-sm text-gray-500 mt-2">Error: ${error.message}</p>
                    <p class="text-xs text-gray-400 mt-1">ID: ${id}</p>
                    <div class="mt-4 space-x-2">
                        <button onclick="showDetailModal(${id})" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Coba Lagi
                        </button>
                        <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
        });
};

window.closeDetailModal = function() {
    document.getElementById('detailModal').classList.add('hidden');
}

window.showRejectModal = function(id) {
    document.getElementById('rejectPeminjamanId').value = id;
    document.getElementById('rejectForm').action = `/admin/peminjaman-ruangan/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

window.closeRejectModal = function() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Filter Functions
function applyFilters() {
    const status = document.getElementById('filter-status').value;
    const ruangan = document.getElementById('filter-ruangan').value;
    const jenisPengusul = document.getElementById('filter-jenis-pengusul').value;
    const fakultas = document.getElementById('filter-fakultas').value;
    const namaPengusul = document.getElementById('filter-nama-pengusul').value;
    const tanggalMulai = document.getElementById('filter-tanggal-mulai').value;
    const tanggalSelesai = document.getElementById('filter-tanggal-selesai').value;
    
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (ruangan) params.append('ruangan_id', ruangan);
    if (jenisPengusul) params.append('jenis_pengusul', jenisPengusul);
    if (fakultas) params.append('fakultas', fakultas);
    if (namaPengusul) params.append('nama_pengusul', namaPengusul);
    if (tanggalMulai) params.append('tanggal_mulai', tanggalMulai);
    if (tanggalSelesai) params.append('tanggal_selesai', tanggalSelesai);
    
    window.location.href = '{{ route('admin.peminjaman-ruangan.index') }}?' + params.toString();
}

// Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#peminjaman-table-body tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Apply initial filters from URL
    const urlParams = new URLSearchParams(window.location.search);
    document.getElementById('filter-status').value = urlParams.get('status') || '';
    document.getElementById('filter-ruangan').value = urlParams.get('ruangan_id') || '';
    document.getElementById('filter-jenis-pengusul').value = urlParams.get('jenis_pengusul') || '';
    document.getElementById('filter-fakultas').value = urlParams.get('fakultas') || '';
    document.getElementById('filter-nama-pengusul').value = urlParams.get('nama_pengusul') || '';
    document.getElementById('filter-tanggal-mulai').value = urlParams.get('tanggal_mulai') || '';
    document.getElementById('filter-tanggal-selesai').value = urlParams.get('tanggal_selesai') || '';

    // Handle reject form submission
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Gagal menolak peminjaman');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menolak peminjaman');
            });
        });
    }

    // Close modals when clicking outside
    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('click', function(e) {
            if (e.target === this) closeDetailModal();
        });
    }

    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    }

    // Escape key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeRejectModal();
        }
    });
});

// Real-time Status Update
function updateStatusRealTime() {
    fetch('{{ route('admin.peminjaman-ruangan.update-status') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Status berhasil diperbarui');
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memperbarui status');
    });
}
</script>
@endpush