<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Jadwal Peminjaman - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .elegant-font {
            font-family: 'Playfair Display', serif;
        }
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
</head>
<body class="bg-gray-50 min-h-screen p-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 elegant-font">Semua Jadwal Peminjaman</h1>
            <p class="text-gray-600">Kelola dan lihat semua jadwal peminjaman yang telah disetujui</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.jadwal-peminjaman') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
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
            
            <form method="GET" action="{{ route('admin.semua-jadwal') }}" class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <!-- Filter Bulan -->
                <div>
                    <select name="bulan" class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Bulan</option>
                        @foreach($bulanList as $value => $label)
                            <option value="{{ $value }}" {{ $selectedBulan == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Jenis -->
                <div>
                    <select name="jenis" class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Jenis</option>
                        <option value="ruangan" {{ $selectedJenis == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                        <option value="vidotron" {{ $selectedJenis == 'vidotron' ? 'selected' : '' }}>Vidotron</option>
                    </select>
                </div>
                
                <!-- Filter Fakultas -->
                <div>
                    <input type="text" name="fakultas" placeholder="Fakultas" 
                           value="{{ request('fakultas') }}" 
                           class="w-full md:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <!-- Tombol Filter -->
                <div class="flex space-x-2">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.semua-jadwal') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2">
                        <i class="fas fa-refresh"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Summary -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                <div>
                    <p class="font-medium text-blue-900">Menampilkan {{ $totalData }} jadwal</p>
                    <p class="text-sm text-blue-700">
                        @if($selectedBulan)
                            Bulan: {{ \Carbon\Carbon::parse($selectedBulan)->translatedFormat('F Y') }} • 
                        @endif
                        @if($selectedJenis)
                            Jenis: {{ ucfirst($selectedJenis) }} • 
                        @endif
                        @if(request('fakultas'))
                            Fakultas: {{ request('fakultas') }} • 
                        @endif
                        Diurutkan dari terbaru
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-sm text-blue-600">
                    Terakhir diperbarui: {{ now()->translatedFormat('d F Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Tabel Semua Jadwal -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-primary-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-primary-900">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Tabel Semua Jadwal Peminjaman
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $totalData }} data</span>
                    <button onclick="exportToExcel()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-file-excel text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($allBookings->count() > 0)
            <div class="scrollable-table">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Informasi Pengusul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Detail Acara</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Jadwal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($allBookings as $index => $booking)
                            @php
                                $isRuangan = isset($booking->ruangan_id);
                                $type = $isRuangan ? 'ruangan' : 'vidotron';
                                $tanggal = $isRuangan ? $booking->tanggal : $booking->tanggal_mulai;
                                $waktu = $isRuangan ? 
                                    substr($booking->jam_mulai, 0, 5) . ' - ' . substr($booking->jam_selesai, 0, 5) :
                                    substr($booking->waktu_mulai, 0, 5) . ' - ' . substr($booking->waktu_selesai, 0, 5);
                                $acara = $isRuangan ? $booking->acara : $booking->tujuan_pemasangan;
                                $lokasi = $isRuangan ? ($booking->ruangan->nama_ruangan ?? 'Ruangan') : 'Vidotron';
                                $tanggalFormatted = \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y');
                                
                                // Informasi pengusul
                                $namaPengusul = $booking->nama_pengusul ?? $booking->user->name;
                                $jenisPengusul = $booking->jenis_pengusul ?? 'user';
                                $fakultas = $booking->fakultas ?? '';
                                $program_studi = $booking->program_studi ?? '';
                                $nimNip = $booking->nim_nip ?? '';
                                $email = $booking->email ?? $booking->user->email ?? '';
                                $telepon = $booking->no_telepon ?? '';
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- No Urut -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">
                                    {{ $index + 1 }}
                                </td>
                                
                                <!-- Informasi pengusul -->
                                <td class="px-6 py-4 border-b">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                            @if($type == 'ruangan') bg-blue-100 text-blue-600 @else bg-green-100 text-green-600 @endif mr-3">
                                            <i class="@if($type == 'ruangan') fas fa-door-open @else fas fa-tv @endif text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $namaPengusul }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if($jenisPengusul)
                                                    <span class="px-1 py-0.5 bg-blue-100 text-blue-800 rounded mr-1">
                                                        {{ ucfirst($jenisPengusul) }}
                                                    </span>
                                                @endif
                                                @if($nimNip)
                                                    <span class="mr-1">{{ $nimNip }}</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                @if($fakultas)
                                                    {{ $fakultas }}
                                                    @if($program_studi)
                                                        - {{ $program_studi}}
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if($email)
                                                    <i class="fas fa-envelope mr-1"></i>{{ $email }}
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @if($telepon)
                                                    <i class="fas fa-phone mr-1"></i>{{ $telepon }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Jenis -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <div class="flex flex-col">
                                        <span class="px-3 py-1 text-xs rounded-full 
                                            @if($type == 'ruangan') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif font-medium w-fit">
                                            {{ ucfirst($type) }}
                                        </span>
                                        <span class="text-xs text-gray-600 mt-1">{{ $lokasi }}</span>
                                    </div>
                                </td>
                                
                                <!-- Detail Acara -->
                                <td class="px-6 py-4 border-b">
                                    <div class="font-medium text-gray-900">{{ $acara }}</div>
                                    @if($isRuangan && $booking->jumlah_peserta)
                                        <div class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-users mr-1"></i>{{ $booking->jumlah_peserta }} peserta
                                        </div>
                                    @endif
                                    @if($isRuangan && $booking->keterangan)
                                        <div class="text-xs text-gray-500 mt-1 truncate max-w-xs" title="{{ $booking->keterangan }}">
                                            <i class="fas fa-sticky-note mr-1"></i>{{ Str::limit($booking->keterangan, 50) }}
                                        </div>
                                    @endif
                                    @if(!$isRuangan && $booking->deskripsi_konten)
                                        <div class="text-xs text-gray-500 mt-1 truncate max-w-xs" title="{{ $booking->deskripsi_konten }}">
                                            <i class="fas fa-file-alt mr-1"></i>{{ Str::limit($booking->deskripsi_konten, 50) }}
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Jadwal -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <div class="text-sm text-gray-900">{{ $tanggalFormatted }}</div>
                                    <div class="text-sm text-gray-600">{{ $waktu }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y H:i') }}
                                    </div>
                                </td>
                                
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap border-b">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                                        Disetujui
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @php
                                            $createdDate = \Carbon\Carbon::parse($booking->created_at);
                                            $now = \Carbon\Carbon::now();
                                            
                                            if ($createdDate->diffInDays($now) < 1) {
                                                echo $createdDate->diffForHumans();
                                            } else {
                                                echo $createdDate->translatedFormat('d M Y');
                                            }
                                        @endphp
                                    </div>
                                </td>
                                
                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                                    <div class="flex space-x-2">
                                        @if($isRuangan)
                                            <button onclick="showRuanganDetail({{ $booking->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 transition-colors" 
                                                    title="Detail Peminjaman">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <button onclick="showVidotronDetail({{ $booking->id }})" 
                                                    class="text-green-600 hover:text-green-900 transition-colors" 
                                                    title="Detail Penyewaan">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
                                        
                                        @if($isRuangan && $booking->lampiran_surat)
                                            <a href="{{ Storage::url($booking->lampiran_surat) }}" 
                                               target="_blank" 
                                               class="text-purple-600 hover:text-purple-900 transition-colors" 
                                               title="Lihat Lampiran">
                                                <i class="fas fa-paperclip"></i>
                                            </a>
                                        @endif
                                        
                                        @if(!$isRuangan && $booking->lampiran_surat)
                                            <a href="{{ Storage::url($booking->lampiran_surat) }}" 
                                               target="_blank" 
                                               class="text-purple-600 hover:text-purple-900 transition-colors" 
                                               title="Lihat Lampiran">
                                                <i class="fas fa-paperclip"></i>
                                            </a>
                                        @endif
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
                        Menampilkan {{ $totalData }} jadwal dari total 
                        {{ ($stats['disetujui_ruangan'] ?? 0) + ($stats['disetujui_vidotron'] ?? 0) }} jadwal yang disetujui
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-door-open text-blue-500 mr-1"></i> Ruangan: {{ $allBookings->where('ruangan_id', '!=', null)->count() }}
                        <i class="fas fa-tv text-green-500 ml-4 mr-1"></i> Vidotron: {{ $allBookings->where('ruangan_id', null)->count() }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">Tidak ada jadwal ditemukan</p>
                <p class="text-gray-400 text-sm">
                    @if($selectedBulan || $selectedJenis || request('fakultas'))
                        Coba ubah filter atau <a href="{{ route('admin.semua-jadwal') }}" class="text-primary-600 hover:text-primary-700">reset filter</a>
                    @else
                        Belum ada jadwal peminjaman yang disetujui
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Modal Detail (akan diisi via AJAX) -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Detail Peminjaman</h3>
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

    <!-- Back to Top -->
    <div class="mt-6 text-center">
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2 mx-auto transition-colors">
            <i class="fas fa-arrow-up"></i>
            <span>Kembali ke Atas</span>
        </button>
    </div>

    <script>
    // Fungsi untuk menampilkan detail ruangan
    function showRuanganDetail(id) {
        fetch(`/admin/peminjaman-ruangan/${id}/detail`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('detailContent').innerHTML = data.html;
                    document.getElementById('detailModal').classList.remove('hidden');
                } else {
                    alert('Gagal memuat detail peminjaman');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail peminjaman');
            });
    }
    
    // Fungsi untuk menampilkan detail vidotron
    function showVidotronDetail(id) {
        fetch(`/admin/penyewaan-vidotron/${id}/detail`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('detailContent').innerHTML = data.html;
                    document.getElementById('detailModal').classList.remove('hidden');
                } else {
                    alert('Gagal memuat detail penyewaan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail penyewaan');
            });
    }
    
    // Fungsi untuk menutup modal
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
    
    // Fungsi untuk mencetak tabel
    function printTable() {
        const printContent = `
            <html>
                <head>
                    <title>Laporan Jadwal Peminjaman</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #333; text-align: center; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Laporan Jadwal Peminjaman</h1>
                        <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</p>
                    </div>
                    ${document.querySelector('table').outerHTML}
                    <div class="footer">
                        <p>Total Data: {{ $totalData }} jadwal | Halaman 1</p>
                    </div>
                </body>
            </html>
        `;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    }
    
    // Fungsi untuk export ke Excel (sederhana)
    function exportToExcel() {
        let csv = [];
        let rows = document.querySelectorAll("table tr");
        
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (let j = 0; j < cols.length; j++) {
                // Hapus tag HTML dan ambil teks saja
                let text = cols[j].innerText.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();
                row.push('"' + text + '"');
            }
            
            csv.push(row.join(","));
        }
        
        // Download file CSV
        let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        let downloadLink = document.createElement("a");
        downloadLink.download = `jadwal-peminjaman_${new Date().toISOString().split('T')[0]}.csv`;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
    
    // Close modal ketika klik di luar
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detailModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) closeDetailModal();
            });
        }
        
        // Escape key untuk close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
            }
        });
    });
    </script>

</body>
</html>