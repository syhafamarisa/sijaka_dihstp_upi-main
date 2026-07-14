@extends('layouts.admin')

@section('title', 'Peminjaman Video')
@section('page-title', 'Peminjaman Video')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 elegant-font">Peminjaman Video Trone</h1>
            <p class="text-gray-600">Kelola semua permintaan peminjaman video trone</p>
        </div>
        <div class="flex space-x-3">
            <button type="button"
        class="bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold cursor-not-allowed"
        disabled>
    Export (Segera Hadir)
</button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Total</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalPeminjaman ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-video text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Menunggu</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $menungguCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Disetujui</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $disetujuiCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Ditolak</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $ditolakCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Dibatalkan</h3>
                    <p class="text-2xl font-bold text-gray-600">{{ $dibatalkanCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ban text-gray-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    @if(($menungguCount ?? 0) > 0)
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-yellow-700 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Permintaan Menunggu Persetujuan ({{ $menungguCount ?? 0 }})
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($pendingApprovals ?? [] as $approval)
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-video text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $approval->tujuan_pemasangan }}</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Pengusul:</strong> {{ $approval->user->name ?? 'N/A' }} ({{ $approval->nama_pengusul }})
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Fakultas:</strong> {{ $approval->fakultas }} - {{ $approval->program_studi }}
                            </p>
                            <p class="text-sm text-gray-500">
                                <strong>Periode:</strong> 
                                {{ \Carbon\Carbon::parse($approval->tanggal_mulai)->translatedFormat('d M Y') }} 
                                s/d 
                                {{ \Carbon\Carbon::parse($approval->tanggal_selesai)->translatedFormat('d M Y') }}
                            </p>
                            @if($approval->deskripsi_konten)
                                <p class="text-sm text-gray-400 mt-1">{{ Str::limit($approval->deskripsi_konten, 100) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.penyewaan-vidotron.approve', $approval->id) }}" method="POST" class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 flex items-center space-x-2 transition-colors" onclick="return confirm('Setujui penyewaan vidotron ini?')">
                                <i class="fas fa-check"></i>
                                <span>Setujui</span>
                            </button>
                        </form>
                        <button onclick="showRejectModal({{ $approval->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 flex items-center space-x-2 transition-colors">
                            <i class="fas fa-times"></i>
                            <span>Tolak</span>
                        </button>
                        <button onclick="showDetailModal({{ $approval->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 flex items-center space-x-2 transition-colors">
                            <i class="fas fa-eye"></i>
                            <span>Detail</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- All Bookings Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Semua Penyewaan Vidotron</h3>
                <div class="flex space-x-2">
                    <input type="text" id="search-input" placeholder="Cari..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-64">
                    <select id="filter-status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Pengusul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Fakultas/program_studi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Tujuan & Konten</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="peminjaman-table-body">
                    @foreach($bookings ?? [] as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-medium">{{ substr($item->user->name ?? 'NA', 0, 2) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $item->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->nama_pengusul }}</div>
                                    <div class="text-xs text-gray-400 capitalize">{{ $item->jenis_pengusul }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->fakultas }}</div>
                            <div class="text-sm text-gray-500">{{ $item->program_studi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->tujuan_pemasangan }}</div>
                            <div class="text-sm text-gray-500 capitalize">{{ $item->jenis_konten }}</div>
                            @if($item->deskripsi_konten)
                                <div class="text-sm text-gray-400 mt-1">{{ Str::limit($item->deskripsi_konten, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-gray-500">
                                {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'menunggu' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Menunggu'],
                                    'disetujui' => ['color' => 'green', 'icon' => 'check', 'text' => 'Disetujui'],
                                    'ditolak' => ['color' => 'red', 'icon' => 'times', 'text' => 'Ditolak'],
                                    'selesai' => ['color' => 'gray', 'icon' => 'flag-checkered', 'text' => 'Selesai'],
                                    'dibatalkan' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Dibatalkan']
                                ];
                                $config = $statusConfig[$item->status] ?? $statusConfig['menunggu'];
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showDetailModal({{ $item->id }})" class="text-blue-600 hover:text-blue-900 transition-colors" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($item->status == 'menunggu')
                                    <form action="{{ route('admin.penyewaan-vidotron.approve', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Setujui" onclick="return confirm('Setujui penyewaan vidotron ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal({{ $item->id }})" class="text-red-600 hover:text-red-900 transition-colors" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                
                                @if(in_array($item->status, ['menunggu', 'disetujui']))
                                    <form action="{{ route('admin.penyewaan-vidotron.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors" title="Hapus" onclick="return confirm('Hapus penyewaan vidotron ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($item->surat_pengajuan)
                                    <a href="{{ route('admin.penyewaan-vidotron.download-surat', $item->id) }}" class="text-green-600 hover:text-green-900 transition-colors" title="Download Surat">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                
                                @if(in_array($item->status, ['menunggu', 'disetujui']))
                                    <form action="{{ route('admin.penyewaan-vidotron.cancel', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-orange-600 hover:text-orange-900 transition-colors" title="Batalkan" onclick="return confirm('Batalkan penyewaan vidotron ini?')">
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

        @if(($bookings ?? collect())->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-video text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Belum ada data penyewaan vidotron</p>
        </div>
        @endif

        <!-- Pagination -->
        @if(($bookings ?? collect()) instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Detail Penyewaan Vidotron</h3>
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
            @method('POST')
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Tolak Penyewaan Vidotron</h3>
            </div>
            <div class="p-6">
                <input type="hidden" name="penyewaan_id" id="rejectPenyewaanId">
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
                    Tolak Penyewaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Pastikan fungsi tersedia di global scope sebelum digunakan
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
    
    // GUNAKAN ROUTE PENYEWAAN-VIDOTRON UNTUK DETAIL
    fetch(`/admin/penyewaan-vidotron/${id}/detail`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
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
            console.error('Error:', error);
            document.getElementById('detailContent').innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                    <p>Gagal memuat detail penyewaan</p>
                    <p class="text-sm text-gray-500 mt-2">${error.message}</p>
                    <button onclick="showDetailModal(${id})" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                        Coba Lagi
                    </button>
                </div>
            `;
        });
}

window.closeDetailModal = function() {
    document.getElementById('detailModal').classList.add('hidden');
}

window.showRejectModal = function(id) {
    console.log('Showing reject modal for ID:', id);
    
    // Reset form
    const form = document.getElementById('rejectForm');
    form.reset();
    
    // Set the form action
    form.action = `/admin/penyewaan-vidotron/${id}/reject`;
    
    // Set the hidden ID field
    document.getElementById('rejectPenyewaanId').value = id;
    
    // Show modal
    document.getElementById('rejectModal').classList.remove('hidden');
}

window.closeRejectModal = function() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Filter Functions
function applyFilters() {
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('search-input').value;
    const params = new URLSearchParams();
    
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    
    window.location.href = '{{ route('admin.penyewaan-vidotron.index') }}?' + params.toString();
}

// Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const filterStatus = document.getElementById('filter-status');
    
    if (searchInput) {
        // Restore search value from URL
        const urlParams = new URLSearchParams(window.location.search);
        searchInput.value = urlParams.get('search') || '';
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }
    
    if (filterStatus) {
        // Restore filter value from URL
        const urlParams = new URLSearchParams(window.location.search);
        filterStatus.value = urlParams.get('status') || '';
        
        filterStatus.addEventListener('change', function() {
            applyFilters();
        });
    }

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
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Gagal menolak penyewaan');
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Gagal menolak penyewaan');
            });
        });
    }

    // Close modals when clicking outside
    const detailModal = document.getElementById('detailModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (detailModal) {
        detailModal.addEventListener('click', function(e) {
            if (e.target === this) closeDetailModal();
        });
    }
    
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    }

    // Debug info
    console.log('Modal functions loaded:', {
        showDetailModal: typeof showDetailModal,
        closeDetailModal: typeof closeDetailModal,
        showRejectModal: typeof showRejectModal,
        closeRejectModal: typeof closeRejectModal
    });
});

// Escape key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeRejectModal();
    }
});
</script>
@endsection