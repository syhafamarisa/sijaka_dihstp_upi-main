<!-- resources/views/pegawai/semua-kegiatan.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Jadwal Kantor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-50">
<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-red-900">Semua Jadwal Kantor</h1>
            <p class="text-gray-600">Daftar lengkap semua jadwal kegiatan kantor</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('pegawai.jadwal.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Kalender</span>
            </a>
            <button onclick="printTable()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
                <i class="fas fa-print"></i>
                <span>Cetak</span>
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Filter Jadwal</h3>
                <p class="text-sm text-gray-600">Saring jadwal berdasarkan kriteria tertentu</p>
            </div>
            
            <form method="GET" action="{{ route('pegawai.semua-kegiatan') }}" class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <!-- Filter Tahun -->
                <div>
                    <select name="tahun" class="w-full md:w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        @foreach($years as $key => $year)
                            <option value="{{ $key }}" {{ request('tahun') == $key ? 'selected' : (date('Y') == $key ? 'selected' : '') }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Bulan -->
                <div>
                    <select name="bulan" class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        @foreach($months as $key => $month)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Ruangan (GANTI DARI LOKASI) -->
                <div>
                    <select name="ruangan_id" class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruanganList as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tombol Filter -->
                <div class="flex space-x-2">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('pegawai.semua-kegiatan') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2">
                        <i class="fas fa-refresh"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Summary -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-info-circle text-red-600 text-xl"></i>
                <div>
                    <p class="font-medium text-red-900">Menampilkan {{ $totalData }} jadwal kantor</p>
                    <p class="text-sm text-red-700">
                        @if(request('bulan'))
                            Bulan: {{ $months[request('bulan')] }} • 
                        @endif
                        @if(request('tahun'))
                            Tahun: {{ request('tahun') }} • 
                        @endif
                        @if(request('ruangan_id') && $ruangan = $ruanganList->where('id', request('ruangan_id'))->first())
                            Ruangan: {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }} • 
                        @endif
                        Diurutkan dari terbaru
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-sm text-red-600">
                    Terakhir diperbarui: {{ now()->translatedFormat('d F Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Tabel Semua Jadwal -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-red-900">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Tabel Semua Jadwal Kantor
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $totalData }} data</span>
                    <button onclick="exportToExcel()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-file-excel text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($kegiatanTerbaru->count() > 0)
            <div class="scrollable-table">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Kegiatan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Jadwal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Ruangan</th> <!-- GANTI LOKASI KE RUANGAN -->
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Pembuat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($kegiatanTerbaru as $index => $kegiatan)
                            @php
                                $tanggal = \Carbon\Carbon::parse($kegiatan['tanggal_mulai'] ?? ($kegiatan['tanggal'] ?? null));
                                $tanggalFormat = $tanggal ? $tanggal->translatedFormat('d M Y') : '-';
                                $waktu = $kegiatan['waktu_mulai'] ? 
                                    \Carbon\Carbon::parse($kegiatan['waktu_mulai'])->format('H:i') . ' - ' . 
                                    \Carbon\Carbon::parse($kegiatan['waktu_selesai'])->format('H:i') : 
                                    'Seluruh Hari';
                                
                                // Gunakan ruangan_nama jika ada, fallback ke lokasi
                                $ruanganNama = $kegiatan['ruangan_nama'] ?? $kegiatan['lokasi'];
                                $creator = $kegiatan['creator'] ?? 'Staff';
                                $createdAt = \Carbon\Carbon::parse($kegiatan['created_at'])->translatedFormat('d M Y H:i');
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- No Urut -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">
                                    {{ $index + 1 }}
                                </td>
                                
                                <!-- Kegiatan -->
                                <td class="px-6 py-4 border-b">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-red-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $kegiatan['nama_kegiatan'] }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                    Jadwal Kantor
                                                </span>
                                            </div>
                                            @if($kegiatan['deskripsi'] && $kegiatan['deskripsi'] != '-')
                                                <div class="text-xs text-gray-600 mt-1 truncate max-w-xs">
                                                    {{ Str::limit($kegiatan['deskripsi'], 60) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Jadwal -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <div class="text-sm text-gray-900">{{ $tanggalFormat }}</div>
                                    <div class="text-sm text-gray-600">{{ $waktu }}</div>
                                </td>
                                
                                <!-- Ruangan (GANTI DARI LOKASI) -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <div class="text-sm text-gray-900">
                                        @if($kegiatan['ruangan_kode'])
                                            {{ $kegiatan['ruangan_kode'] }} - {{ $kegiatan['ruangan_nama'] }}
                                        @else
                                            {{ $ruanganNama }}
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Pembuat -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <div class="text-sm text-gray-900">{{ $creator }}</div>
                                </td>
                                
                                <!-- Tanggal Dibuat -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <div class="text-sm text-gray-900">{{ $createdAt }}</div>
                                </td>
                                
                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                                    <div class="flex space-x-2">
                                        <!-- Tombol Detail -->
                                        <button type="button" 
                                                onclick="showDetail(@json($kegiatan))"
                                                class="text-green-600 hover:text-green-900 transition-colors" 
                                                title="Detail Kegiatan">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                       <!-- Tombol Edit - PERBAIKAN: Gunakan route yang benar -->
                                        <a href="{{ route('pegawai.jadwal.edit', $kegiatan['model_id']) }}" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors" 
                                        title="Edit Jadwal">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Tombol Delete -->
                                        <button type="button" 
                                                onclick="deleteKegiatan({{ $kegiatan['model_id'] }}, '{{ $kegiatan['nama_kegiatan'] }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors" 
                                                title="Hapus Jadwal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Summary Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $kegiatanTerbaru->count() }} dari {{ $totalData }} jadwal
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-building text-red-500 mr-1"></i> Total Jadwal Kantor: {{ $totalData }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">Tidak ada jadwal kantor ditemukan</p>
                <p class="text-gray-400 text-sm">
                    @if(request('bulan') || request('tahun') || request('ruangan_id'))
                        Coba ubah filter atau <a href="{{ route('pegawai.jadwal.semua-kegiatan') }}" class="text-red-600 hover:text-red-700">reset filter</a>
                    @else
                        Belum ada jadwal kantor yang dibuat
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Detail Jadwal Kantor</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]" id="detailContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Delete Confirmation -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus jadwal 
                        <span class="font-semibold" id="deleteNama"></span>?
                    </p>
                    <p class="mt-1 text-xs text-red-600">Aksi ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <div class="mt-6 text-center">
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2 mx-auto transition-colors">
            <i class="fas fa-arrow-up"></i>
            <span>Kembali ke Atas</span>
        </button>
    </div>
</div>

@push('styles')
<style>
    .scrollable-table {
        max-height: 600px;
        overflow-y: auto;
    }
    .scrollable-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>
@endpush

@push('scripts')
<script>
// Peta ruangan dari controller
const ruanganMap = @json($ruanganMap ?? []);

// Format tanggal
function formatTanggal(tanggal) {
    if (!tanggal) return '-';
    const date = new Date(tanggal);
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

// Format waktu
function formatWaktu(waktu) {
    if (!waktu) return '-';
    if (waktu.length === 5) return waktu;
    if (waktu.length >= 8) return waktu.substring(0, 5);
    return waktu;
}

// Fungsi untuk menampilkan detail kegiatan
function showDetail(kegiatan) {
    // Tentukan nama ruangan
    let ruanganNama = kegiatan.ruangan_nama;
    if (!ruanganNama && kegiatan.ruangan_id && ruanganMap[kegiatan.ruangan_id]) {
        ruanganNama = ruanganMap[kegiatan.ruangan_id];
    } else if (!ruanganNama) {
        ruanganNama = kegiatan.lokasi || 'Tidak ditentukan';
    }
    
    let content = `
        <div class="space-y-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-building text-red-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xl text-gray-900">${kegiatan.nama_kegiatan}</h4>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full font-medium">
                            Jadwal Kantor
                        </span>
                        <span class="text-sm text-gray-500">${kegiatan.creator || 'Staff'}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-3 pb-2 border-b">Informasi Jadwal</h5>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-calendar-day text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal</p>
                                    <p class="font-medium text-gray-900">${formatTanggal(kegiatan.tanggal)}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-clock text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Waktu</p>
                                    <p class="font-medium text-gray-900">${formatWaktu(kegiatan.waktu_mulai)} - ${formatWaktu(kegiatan.waktu_selesai)}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-door-open text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Ruangan</p>
                                    <p class="font-medium text-gray-900">${ruanganNama}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-3 pb-2 border-b">Informasi Tambahan</h5>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-user text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Dibuat Oleh</p>
                                    <p class="font-medium text-gray-900">${kegiatan.creator || 'Staff'}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-calendar-plus text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Dibuat Pada</p>
                                    <p class="font-medium text-gray-900">
                                        ${new Date(kegiatan.created_at).toLocaleDateString('id-ID', { 
                                            weekday: 'long', 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}
                                    </p>
                                </div>
                            </div>
                            ${kegiatan.kapasitas_peserta ? `
                            <div class="flex items-start">
                                <i class="fas fa-users text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Kapasitas Peserta</p>
                                    <p class="font-medium text-gray-900">${kegiatan.kapasitas_peserta} orang</p>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>

            ${kegiatan.deskripsi && kegiatan.deskripsi !== '-' ? `
            <div>
                <h5 class="font-semibold text-gray-700 mb-3 pb-2 border-b">Deskripsi Kegiatan</h5>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-line">${kegiatan.deskripsi}</p>
                </div>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('detailContent').innerHTML = content;
    document.getElementById('detailModal').classList.remove('hidden');
}

// Fungsi untuk delete kegiatan
function deleteKegiatan(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('deleteForm').action = `/pegawai/jadwal/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Fungsi untuk menutup modal detail
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Fungsi untuk menutup modal delete
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fungsi untuk mencetak tabel
function printTable() {
    const printContent = `
        <html>
            <head>
                <title>Laporan Jadwal Kantor</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; text-align: center; margin-bottom: 10px; }
                    h2 { color: #666; text-align: center; font-weight: normal; margin-bottom: 30px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    .badge-kantor { background-color: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px; font-size: 12px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Laporan Jadwal Kantor</h1>
                    <h2>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</h2>
                </div>
                ${document.querySelector('table').outerHTML}
                <div class="footer">
                    <p>Total Data: {{ $totalData }} jadwal | Halaman 1</p>
                    <p>Generated by Office Management System</p>
                </div>
            </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
}

// Fungsi untuk export ke Excel
function exportToExcel() {
    let csv = [];
    let rows = document.querySelectorAll("table tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (let j = 0; j < cols.length; j++) {
            let text = cols[j].innerText.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();
            row.push('"' + text + '"');
        }
        
        csv.push(row.join(","));
    }
    
    let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    let downloadLink = document.createElement("a");
    downloadLink.download = `jadwal-kantor_${new Date().toISOString().split('T')[0]}.csv`;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Close modal ketika klik di luar
    const modals = ['detailModal', 'deleteModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'detailModal') closeDetailModal();
                    if (modalId === 'deleteModal') closeDeleteModal();
                }
            });
        }
    });
    
    // Escape key untuk close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeDeleteModal();
        }
    });
    
    // Handle form submit delete
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
            submitBtn.disabled = true;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(new FormData(this))
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    showNotification('success', data.message || 'Jadwal berhasil dihapus!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Gagal menghapus jadwal');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                showNotification('error', error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Fungsi untuk menampilkan notifikasi
function showNotification(type, message) {
    const existingNotif = document.querySelector('.fixed-notification');
    if (existingNotif) existingNotif.remove();
    
    const notification = document.createElement('div');
    notification.className = `fixed-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-semibold z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}
</script>
@endpush
</body>
</html>