@extends('layouts.user')

@section('title', 'Riwayat Peminjaman - Scheduler')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                @php
                    $activeTab = $activeTab ?? 'ruangan';
                @endphp
                
                <a href="{{ route('user.peminjaman-ruangan.riwayat', ['tab' => 'ruangan'] + request()->except('tab', 'page')) }}" 
                   class="@if($activeTab == 'ruangan') border-primary-500 text-primary-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif flex-1 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-center">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-door-open mr-2"></i>
                        Peminjaman Ruangan
                        @if($peminjamanRuangan->total() > 0)
                        <span class="ml-2 bg-primary-100 text-primary-600 text-xs px-2 py-1 rounded-full">
                            {{ $peminjamanRuangan->total() }}
                        </span>
                        @endif
                    </div>
                </a>
                <a href="{{ route('user.peminjaman-ruangan.riwayat', ['tab' => 'video'] + request()->except('tab', 'page')) }}" 
                   class="@if($activeTab == 'video') border-primary-500 text-primary-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif flex-1 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-center">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-video mr-2"></i>
                        Penyewaan Vidotron
                        @if($penyewaanVidotron->total() > 0)
                        <span class="ml-2 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                            {{ $penyewaanVidotron->total() }}
                        </span>
                        @endif
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <!-- Filter Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-xl font-bold text-gray-800">
                    @if($activeTab == 'ruangan')
                        Riwayat Peminjaman Ruangan
                    @else
                        Riwayat Penyewaan Vidotron
                    @endif
                </h2>
                
                <div class="flex flex-wrap gap-3">
                    <select id="filter-status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    
                    <input type="month" id="filter-bulan" value="{{ request('bulan') }}" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    
                    <button onclick="applyFilters()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-semibold">
                        Terapkan
                    </button>
                    
                    @if(request()->has('status') || request()->has('bulan'))
                    <a href="{{ route('user.peminjaman-ruangan.riwayat', ['tab' => $activeTab]) }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                        Reset Filter
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table Riwayat -->
        <div class="overflow-x-auto">
            @if($activeTab == 'ruangan')
                @if($peminjamanRuangan->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($peminjamanRuangan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-door-open text-primary-600 text-sm"></i>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">Ruangan</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $item->ruangan->kode_ruangan ?? '-' }} - {{ $item->ruangan->nama_ruangan ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $item->acara }}</div>
                                @if($item->keterangan)
                                    <div class="text-sm text-gray-400 mt-1">{{ Str::limit($item->keterangan, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->jam_mulai }} - {{ $item->jam_selesai }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        'selesai' => 'bg-gray-100 text-gray-800',
                                        'dibatalkan' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusLabels = [
                                        'menunggu' => 'Menunggu',
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan'
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$item->status] ?? $item->status }}
                                </span>
                                @if($item->status == 'ditolak' && $item->alasan_penolakan)
                                    <div class="text-xs text-red-600 mt-1 cursor-help" title="{{ $item->alasan_penolakan }}">
                                        <i class="fas fa-info-circle"></i> Ada alasan penolakan
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <!-- TOMBOL DETAIL - PERBAIKI ONCLICK -->
                                <button onclick="showDetail('ruangan', {{ $item->id }})" 
                                        class="text-primary-600 hover:text-primary-900 mr-3 p-2 rounded-full hover:bg-primary-50 transition-colors" 
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if(in_array($item->status, ['menunggu', 'disetujui']))
                                <!-- FORM CANCEL - PERBAIKI KE POST -->
                                <form action="{{ route('user.peminjaman-ruangan.cancel', $item->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ruangan ini?')">
                                    @csrf
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50 transition-colors" 
                                            title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($peminjamanRuangan->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $peminjamanRuangan->firstItem() }} sampai {{ $peminjamanRuangan->lastItem() }} dari {{ $peminjamanRuangan->total() }} entri
                        </div>
                        <div class="flex space-x-2">
                            {{ $peminjamanRuangan->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Belum ada riwayat peminjaman ruangan</p>
                        <a href="{{ route('user.peminjaman-ruangan.create') }}" 
                           class="mt-4 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Ajukan Peminjaman Ruangan
                        </a>
                    </div>
                </div>
                @endif

            @else
                @if($penyewaanVidotron->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($penyewaanVidotron as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-video text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">Vidotron</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $item->tujuan_pemasangan }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->nama_pengusul }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->fakultas }} - {{ $item->program_studi }}
                                </div>
                                @if($item->deskripsi_konten)
                                    <div class="text-sm text-gray-400 mt-1">
                                        {{ Str::limit($item->deskripsi_konten, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }} 
                                    - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        'selesai' => 'bg-gray-100 text-gray-800',
                                        'dibatalkan' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusLabels = [
                                        'menunggu' => 'Menunggu',
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan'
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$item->status] ?? $item->status }}
                                </span>
                                @if($item->status == 'ditolak' && $item->alasan_penolakan)
                                    <div class="text-xs text-red-600 mt-1 cursor-help" title="{{ $item->alasan_penolakan }}">
                                        <i class="fas fa-info-circle"></i> Ada alasan penolakan
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <!-- TOMBOL DETAIL VIDOTRON -->
                                <button onclick="showDetail('vidotron', {{ $item->id }})" 
                                        class="text-primary-600 hover:text-primary-900 mr-3 p-2 rounded-full hover:bg-primary-50 transition-colors" 
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if(in_array($item->status, ['menunggu', 'disetujui']))
                                <!-- FORM CANCEL VIDOTRON -->
                                <form action="{{ route('user.penyewaan-vidotron.cancel', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penyewaan vidotron ini?')">
    @csrf

    <button type="submit"
            class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50"
            title="Batalkan">
        <i class="fas fa-times"></i>
    </button>
</form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($penyewaanVidotron->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $penyewaanVidotron->firstItem() }} sampai {{ $penyewaanVidotron->lastItem() }} dari {{ $penyewaanVidotron->total() }} entri
                        </div>
                        <div class="flex space-x-2">
                            {{ $penyewaanVidotron->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Belum ada riwayat penyewaan vidotron</p>
                        <a href="{{ route('user.penyewaan-vidotron.create') }}" 
                           class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Ajukan Penyewaan Vidotron
                        </a>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-hidden transform transition-transform duration-300 scale-95" id="modalContent">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Detail</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]" id="detailContent">
            <!-- Content akan dimuat via AJAX -->
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                <span class="ml-3 text-gray-600">Memuat data...</span>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end bg-gray-50">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div id="globalLoading" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 shadow-xl">
        <div class="flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            <span class="ml-3 text-gray-700 font-medium">Memuat...</span>
        </div>
    </div>
</div>

<!-- Debug Info -->
<div class="hidden">
    <div id="debugCurrentUser">User ID: {{ auth()->id() }}</div>
    <div id="debugRouteRuangan">Route: {{ route('user.peminjaman-ruangan.detail', ':id') }}</div>
    <div id="debugRouteVidotron">Route: {{ route('user.penyewaan-vidotron.detail', ':id') }}</div>
</div>
@endsection

@push('scripts')
<script>
// ============================================
// KONFIGURASI DAN FUNGSI UTAMA - FIXED VERSION
// ============================================

// Debug info
console.log('=== SISTEM RIWAYAT DETAIL ===');
console.log('User ID saat ini:', {{ auth()->id() ?? 'null' }});
console.log('Tab aktif:', '{{ $activeTab }}');
console.log('Jumlah data ruangan:', {{ $peminjamanRuangan->count() }});
console.log('Jumlah data vidotron:', {{ $penyewaanVidotron->count() }});

// CSRF Token untuk AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Fungsi untuk mendapatkan URL detail - SIMPLE VERSION
function getDetailUrl(type, id) {
    if (type === 'ruangan') {
        return `/user/peminjaman-ruangan/${id}/detail`;
    } else if (type === 'vidotron') {
        return `/user/penyewaan-vidotron/${id}/detail`;
    }
    return null;
}

// FUNGSI UTAMA UNTUK MENAMPILKAN DETAIL - FIXED
function showDetail(type, id) {
    console.log(`Menampilkan detail: ${type}, ID: ${id}`);
    
    if (!type || !id) {
        alert('Error: Parameter tidak valid');
        return false;
    }

    // Tampilkan loading
    showLoading();
    
    // Set judul modal
    const modalTitle = document.getElementById('modalTitle');
    if (modalTitle) {
        modalTitle.textContent = type === 'ruangan' 
            ? 'Detail Peminjaman Ruangan' 
            : 'Detail Penyewaan Vidotron';
    }
    
    // Tampilkan modal
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    if (modal && modalContent) {
        modal.classList.remove('hidden');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }
    
    // Reset dan tampilkan loading di modal
    const detailContent = document.getElementById('detailContent');
    if (detailContent) {
        detailContent.innerHTML = `
            <div class="flex flex-col items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mb-4"></div>
                <span class="text-gray-600">Memuat data...</span>
                <p class="text-sm text-gray-400 mt-2">ID: ${id}</p>
            </div>
        `;
    }
    
    // Ambil data dari API
    loadDetailData(type, id);
    
    return false; // Prevent default
}

// Fungsi untuk memuat data detail
async function loadDetailData(type, id) {
    try {
        const url = getDetailUrl(type, id);
        console.log('Mengambil data dari:', url);
        
        if (!url) {
            throw new Error('URL tidak valid');
        }
        
        // Tambahkan timestamp untuk cache busting
        const urlWithTimestamp = `${url}?t=${Date.now()}`;
        
        const response = await fetch(urlWithTimestamp, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        });
        
        console.log('Status Response:', response.status);
        
        // Handle response
        if (response.status === 401) {
            throw new Error('Sesi telah habis. Silakan login kembali.');
        }
        
        if (response.status === 404) {
            throw new Error('Data tidak ditemukan. ID mungkin tidak valid.');
        }
        
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('Data response:', data);
        
        // Update modal content
        const detailContent = document.getElementById('detailContent');
        if (detailContent) {
            if (data.success && data.html) {
                detailContent.innerHTML = data.html;
            } else {
                throw new Error(data.message || 'Gagal memuat data');
            }
        }
        
    } catch (error) {
        console.error('Error loading detail:', error);
        
        // Tampilkan error di modal
        const detailContent = document.getElementById('detailContent');
        if (detailContent) {
            detailContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-red-700 mb-2">Gagal Memuat Data</h4>
                    <p class="text-gray-600 mb-4">${error.message}</p>
                    <div class="space-x-2">
                        <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Tutup
                        </button>
                        <button onclick="showDetail('${type}', ${id})" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            `;
        }
    } finally {
        hideLoading();
    }
}

// Fungsi untuk menutup modal
function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    
    if (modal && modalContent) {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset content
            const detailContent = document.getElementById('detailContent');
            if (detailContent) {
                detailContent.innerHTML = `
                    <div class="flex justify-center items-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                        <span class="ml-3 text-gray-600">Memuat data...</span>
                    </div>
                `;
            }
        }, 300);
    }
}

// Fungsi untuk menerapkan filter
function applyFilters() {
    const status = document.getElementById('filter-status')?.value;
    const bulan = document.getElementById('filter-bulan')?.value;
    
    const params = new URLSearchParams();
    
    if (status) params.append('status', status);
    if (bulan) params.append('bulan', bulan);
    params.append('tab', '{{ $activeTab }}');
    
    window.location.href = `{{ route('user.peminjaman-ruangan.riwayat') }}?${params.toString()}`;
}

// Fungsi loading
function showLoading() {
    const loading = document.getElementById('globalLoading');
    if (loading) loading.classList.remove('hidden');
}

function hideLoading() {
    const loading = document.getElementById('globalLoading');
    if (loading) loading.classList.add('hidden');
}

// ============================================
// EVENT LISTENERS DAN INISIALISASI
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Sistem detail siap');
    
    // Setup modal events
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    }
    
    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (modal && !modal.classList.contains('hidden')) {
                closeDetailModal();
            }
        }
    });
    
    // Setup semua tombol detail dengan event listener
    setupDetailButtons();
    
    // Log semua tombol yang ditemukan
    console.log('Tombol detail ditemukan:', document.querySelectorAll('[onclick*="showDetail"]').length);
});

// Setup detail buttons secara manual
function setupDetailButtons() {
    // Tombol untuk ruangan
    const ruanganButtons = document.querySelectorAll('button[onclick*="showDetail(\'ruangan\'"]');
    ruanganButtons.forEach(button => {
        const match = button.getAttribute('onclick').match(/showDetail\('ruangan',\s*(\d+)\)/);
        if (match) {
            const id = match[1];
            button.setAttribute('data-id', id);
            button.setAttribute('data-type', 'ruangan');
            button.addEventListener('click', function(e) {
                e.preventDefault();
                showDetail('ruangan', id);
            });
        }
    });
    
    // Tombol untuk vidotron
    const vidotronButtons = document.querySelectorAll('button[onclick*="showDetail(\'vidotron\'"]');
    vidotronButtons.forEach(button => {
        const match = button.getAttribute('onclick').match(/showDetail\('vidotron',\s*(\d+)\)/);
        if (match) {
            const id = match[1];
            button.setAttribute('data-id', id);
            button.setAttribute('data-type', 'vidotron');
            button.addEventListener('click', function(e) {
                e.preventDefault();
                showDetail('vidotron', id);
            });
        }
    });
    
    console.log('Tombol ruangan yang disetup:', ruanganButtons.length);
    console.log('Tombol vidotron yang disetup:', vidotronButtons.length);
}

// Fallback untuk testing
window.testDetailSystem = function() {
    console.log('Testing detail system...');
    
    // Coba ambil ID pertama dari tabel
    let testId = null;
    let testType = 'ruangan';
    
    // Cari ID dari tabel yang aktif
    if ('{{ $activeTab }}' === 'ruangan' && {{ $peminjamanRuangan->count() }} > 0) {
        const firstRow = document.querySelector('tbody tr');
        if (firstRow) {
            const button = firstRow.querySelector('button[onclick*="showDetail"]');
            if (button) {
                const match = button.getAttribute('onclick').match(/(\d+)/);
                if (match) {
                    testId = match[1];
                }
            }
        }
    } else if ('{{ $activeTab }}' === 'video' && {{ $penyewaanVidotron->count() }} > 0) {
        testType = 'vidotron';
        const firstRow = document.querySelector('tbody tr');
        if (firstRow) {
            const button = firstRow.querySelector('button[onclick*="showDetail"]');
            if (button) {
                const match = button.getAttribute('onclick').match(/(\d+)/);
                if (match) {
                    testId = match[1];
                }
            }
        }
    }
    
    if (testId) {
        console.log(`Testing dengan ${testType} ID: ${testId}`);
        showDetail(testType, testId);
    } else {
        alert('Tidak ada data untuk testing');
    }
};

// Helper untuk debug
window.logRoute = function() {
    console.log('Route debug:');
    console.log('1. Ruangan:', '{{ route("user.peminjaman-ruangan.detail", 999) }}');
    console.log('2. Vidotron:', '{{ route("user.penyewaan-vidotron.detail", 999) }}');
    console.log('3. Current URL:', window.location.href);
};
</script>

{{-- SweetAlert untuk notifikasi --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif
@endpush

@push('styles')
<style>
    /* Animasi untuk modal */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideIn {
        from { 
            transform: translateY(-20px) scale(0.95);
            opacity: 0;
        }
        to { 
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
    
    #detailModal {
        animation: fadeIn 0.3s ease-out;
    }
    
    #modalContent {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Hover effects */
    button:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    /* Scrollbar styling untuk modal */
    #detailContent::-webkit-scrollbar {
        width: 6px;
    }
    
    #detailContent::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #detailContent::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    #detailContent::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Spinner animation */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Tooltip styling */
    [title] {
        position: relative;
    }
    
    [title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
    }
</style>
@endpush