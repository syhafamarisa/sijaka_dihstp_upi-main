@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')
@section('page-title', 'Manajemen Ruangan')

@section('content')

{{-- ================= STATISTIK ================= --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow border-l-4 border-blue-600">
        <h3 class="text-xs sm:text-sm text-gray-600">Total Ruangan</h3>
        <p class="text-xl sm:text-3xl font-bold">{{ $statistics['total'] }}</p>
    </div>
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow border-l-4 border-green-500">
        <h3 class="text-xs sm:text-sm text-gray-600">Tersedia</h3>
        <p class="text-xl sm:text-3xl font-bold text-green-600">{{ $statistics['tersedia'] }}</p>
    </div>
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow border-l-4 border-yellow-500">
        <h3 class="text-xs sm:text-sm text-gray-600">Dipinjam</h3>
        <p class="text-xl sm:text-3xl font-bold text-yellow-600">{{ $statistics['dipinjam'] }}</p>
    </div>
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow border-l-4 border-red-500">
        <h3 class="text-xs sm:text-sm text-gray-600">Maintenance</h3>
        <p class="text-xl sm:text-3xl font-bold text-red-600">{{ $statistics['maintenance'] }}</p>
    </div>
</div>

{{-- ================= TABEL ================= --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Ruangan</h2>
                <p class="text-gray-600 text-sm mt-1">Total {{ $ruangan->total() }} ruangan</p>
            </div>
            <button onclick="openCreateModal()"
                class="mt-3 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Ruangan
            </button>
        </div>
        
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.ruangan.index') }}" class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-3">
            <input name="search" value="{{ request('search') }}"
                   class="md:col-span-8 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Cari ruangan...">
            <select name="status" class="md:col-span-3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <button type="submit" class="md:col-span-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                <i class="fas fa-search p-2"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($ruangan as $item)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ ($ruangan->currentPage() - 1) * $ruangan->perPage() + $loop->iteration }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-blue-600">{{ $item->kode_ruangan }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $item->nama_ruangan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->kapasitas }} orang
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @if($item->fasilitas)
                                @php
                                    $fasilitas = explode(',', $item->fasilitas);
                                    $fasilitas = array_map('trim', $fasilitas);
                                @endphp
                                @foreach(array_slice($fasilitas, 0, 2) as $fas)
                                    @if($fas != '')
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded mr-1 mb-1">
                                            {{ $fas }}
                                        </span>
                                    @endif
                                @endforeach
                                @if(count($fasilitas) > 2)
                                    <span class="text-xs text-gray-500">+{{ count($fasilitas) - 2 }} lagi</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status == 'tersedia')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Tersedia
                            </span>
                        @elseif($item->status == 'dipinjam')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Dipinjam
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Maintenance
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openDetailModal({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="openEditModal({{ $item->id }})"
                                class="text-yellow-600 hover:text-yellow-900 px-2 py-1 rounded hover:bg-yellow-50"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteRuangan({{ $item->id }}, '{{ $item->kode_ruangan }}')"
                                class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50"
                                title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($ruangan->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-door-closed text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Tidak ada data ruangan</p>
        <button onclick="openCreateModal()"
            class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Tambah Ruangan Pertama
        </button>
    </div>
    @endif

    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $ruangan->firstItem() ?: 0 }} sampai {{ $ruangan->lastItem() ?: 0 }} dari {{ $ruangan->total() }} ruangan
            </div>
            <div>
                {{ $ruangan->links() }}
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL CREATE ================= --}}
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Tambah Ruangan Baru</h2>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="createForm" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Ruangan *</label>
                    <input type="text" name="kode_ruangan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: R001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan *</label>
                    <input type="text" name="nama_ruangan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Ruang Rapat A">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas *</label>
                    <input type="number" name="kapasitas" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="10">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                <input type="text" name="fasilitas"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Pisahkan dengan koma, contoh: AC, Proyektor">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Ruangan</label>
                <input type="file" name="gambar" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF (Maks: 2MB)</p>
            </div>
            
            <div class="pt-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Ruangan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Edit Ruangan</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="editForm" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Ruangan *</label>
                    <input type="text" id="editKode" name="kode_ruangan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan *</label>
                    <input type="text" id="editNama" name="nama_ruangan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas *</label>
                    <input type="number" id="editKapasitas" name="kapasitas" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select id="editStatus" name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                <input type="text" id="editFasilitas" name="fasilitas"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Ruangan</label>
                <div id="currentImage" class="mb-2"></div>
                <input type="file" name="gambar" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
            </div>
            
            <div class="pt-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Ruangan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DETAIL ================= --}}
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Detail Ruangan</h2>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div id="detailContent" class="p-6">
            <div class="space-y-4">
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t">
            <div class="text-right">
                <button onclick="closeDetailModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const csrf = '{{ csrf_token() }}';
let currentRuanganId = null;

// Modal Functions
function openCreateModal() { 
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createModal').classList.add('flex');
}

function closeCreateModal() { 
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createModal').classList.remove('flex');
    document.getElementById('createForm').reset();
}

function openEditModal(id) {
    currentRuanganId = id;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    
    fetch(`/admin/ruangan/${id}/detail`)
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            document.getElementById('editId').value = id;
            document.getElementById('editKode').value = res.data.kode_ruangan;
            document.getElementById('editNama').value = res.data.nama_ruangan;
            document.getElementById('editKapasitas').value = res.data.kapasitas;
            document.getElementById('editFasilitas').value = res.data.fasilitas || '';
            document.getElementById('editStatus').value = res.data.status;
            
            // Show current image
            const currentImageDiv = document.getElementById('currentImage');
            if (res.data.gambar) {
                currentImageDiv.innerHTML = `
                    <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                    <img src="/storage/${res.data.gambar}" 
                         class="w-32 h-32 object-cover rounded-lg border">
                `;
            } else {
                currentImageDiv.innerHTML = '<p class="text-sm text-gray-500">Tidak ada gambar</p>';
            }
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Gagal memuat data ruangan');
    });
}

function closeEditModal() { 
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.getElementById('editForm').reset();
    document.getElementById('currentImage').innerHTML = '';
    currentRuanganId = null;
}

function openDetailModal(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    
    fetch(`/admin/ruangan/${id}/detail`)
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            const data = res.data;
            let fasilitasHTML = '-';
            if (data.fasilitas) {
                const fasilitas = data.fasilitas.split(',').map(f => f.trim()).filter(f => f);
                fasilitasHTML = fasilitas.map(f => 
                    `<span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded mr-1 mb-1">${f}</span>`
                ).join('');
            }
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Ruangan</p>
                        <p class="text-lg font-semibold text-blue-600">${data.kode_ruangan}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nama Ruangan</p>
                        <p class="text-lg">${data.nama_ruangan}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kapasitas</p>
                        <p class="text-lg">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                ${data.kapasitas} orang
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fasilitas</p>
                        <div class="mt-1">${fasilitasHTML}</div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        ${data.status == 'tersedia' 
                            ? '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Tersedia</span>' 
                            : data.status == 'dipinjam'
                            ? '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Dipinjam</span>'
                            : '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Maintenance</span>'
                        }
                    </div>
                    ${data.gambar ? `
                    <div>
                        <p class="text-sm text-gray-500">Gambar</p>
                        <img src="/storage/${data.gambar}" 
                             class="mt-2 w-48 h-48 object-cover rounded-lg border">
                    </div>
                    ` : ''}
                    <div class="pt-4 border-t">
                        <p class="text-sm text-gray-500">Dibuat Pada</p>
                        <p class="text-sm">${data.tanggal_dibuat}</p>
                    </div>
                </div>
            `;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('detailContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-3"></i>
                <p class="text-gray-600">Gagal memuat detail ruangan</p>
            </div>
        `;
    });
}

function closeDetailModal() { 
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

// Form Handlers
document.getElementById('createForm').onsubmit = function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.ruangan.store") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': csrf }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ruangan berhasil ditambahkan!');
            closeCreateModal();
            window.location.reload();
        } else {
            if (data.errors) {
                let errorMessage = '';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        errorMessage += error + '\n';
                    });
                });
                alert(errorMessage);
            } else {
                alert(data.message || 'Gagal menambahkan ruangan');
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
};

document.getElementById('editForm').onsubmit = function(e) {
    e.preventDefault();
    
    if (!currentRuanganId) return;
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memperbarui...';
    
    const formData = new FormData(this);
    
    fetch(`/admin/ruangan/${currentRuanganId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrf,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ruangan berhasil diperbarui!');
            closeEditModal();
            window.location.reload();
        } else {
            if (data.errors) {
                let errorMessage = '';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        errorMessage += error + '\n';
                    });
                });
                alert(errorMessage);
            } else {
                alert(data.message || 'Gagal memperbarui ruangan');
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui data');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
};

// Delete Function
function deleteRuangan(id, kode) {
    if (confirm(`Apakah Anda yakin ingin menghapus ruangan "${kode}"?`)) {
        fetch(`/admin/ruangan/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Ruangan berhasil dihapus!');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menghapus ruangan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
    }
}

// Close modals on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeEditModal();
        closeDetailModal();
    }
});

// Close modals on backdrop click
document.querySelectorAll('#createModal, #editModal, #detailModal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            if (this.id === 'createModal') closeCreateModal();
            if (this.id === 'editModal') closeEditModal();
            if (this.id === 'detailModal') closeDetailModal();
        }
    });
});
</script>

<style>
/* Loading animation */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Hover effects */
.hover\:bg-blue-50:hover { background-color: #eff6ff; }
.hover\:bg-yellow-50:hover { background-color: #fefce8; }
.hover\:bg-red-50:hover { background-color: #fef2f2; }
</style>
@endsection