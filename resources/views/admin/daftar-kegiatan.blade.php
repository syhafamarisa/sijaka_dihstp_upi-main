<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kegiatan Kantor - Scheduler</title>
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
</head>
<body class="bg-gray-50">
    <!-- Navigation Simple -->
    <nav class="bg-primary-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- Logo dengan Kotak Pembatas -->
                    <div class="logo-container flex items-center justify-center w-10 h-10 rounded-md overflow-hidden">
                        <img src="{{ asset('img/sijaka.png') }}" 
                             alt="Logo Scheduler" 
                             class="logo-img w-8 h-8 object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-8 h-8 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-calendar-alt text-primary-600 text-lg"></i>
                        </div>
                    </div>
                    <h1 class="text-xl font-bold elegant-font">Scheduler</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.jadwal-pegawai') }}" class="bg-white text-primary-700 hover:bg-primary-50 px-4 py-2 rounded-lg font-semibold transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-2 rounded transition-colors flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-primary-900 elegant-font">Daftar Kegiatan Kantor</h1>
                <p class="text-gray-600">Semua jadwal kegiatan dan aktivitas kantor</p>
            </div>
            <div class="flex space-x-4">
                <div class="bg-white px-4 py-2 rounded-lg shadow">
                    <span class="text-sm text-gray-600">Total Peminjaman Ruangan:</span>
                    <span class="ml-2 font-bold text-primary-600">{{ $peminjamanCount }}</span>
                </div>
                <div class="bg-white px-4 py-2 rounded-lg shadow">
                    <span class="text-sm text-gray-600">Total Penyewaan Vidotron:</span>
                    <span class="ml-2 font-bold text-primary-600">{{ $penyewaanCount }}</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kegiatan</label>
                    <select id="filterJenis" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="semua">Semua Jenis</option>
                        <option value="peminjaman">Peminjaman Ruangan</option>
                        <option value="penyewaan">Penyewaan Vidotron</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filterStatus" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="semua">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" id="filterDariTanggal" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" id="filterSampaiTanggal" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="resetFilter()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>

        <!-- Daftar Kegiatan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-primary-900">Daftar Semua Kegiatan</h3>
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $totalKegiatan }} kegiatan
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Kegiatan/Acara</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Pengusul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Lokasi/Tujuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kegiatan as $item)
                            @if(isset($item['jenis']) && $item['jenis'] == 'peminjaman_ruangan')
                                <!-- Data Peminjaman Ruangan -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            <i class="fas fa-door-open mr-1"></i> Ruangan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item['acara'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['keterangan'] ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['jam_mulai'] }} - {{ $item['jam_selesai'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $item['hari'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $item['nama_pengguna'] ?? 'User' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['jumlah_peserta'] }} peserta</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $item['nama_ruangan'] ?? 'Ruangan' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['jenis_ruangan'] ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'selesai' => 'bg-blue-100 text-blue-800'
                                            ];
                                            $statusText = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'selesai' => 'Selesai'
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$item['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusText[$item['status']] ?? $item['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @elseif(isset($item['jenis']) && $item['jenis'] == 'penyewaan_vidotron')
                                <!-- Data Penyewaan Vidotron -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                            <i class="fas fa-tv mr-1"></i> Vidotron
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item['tujuan_pemasangan'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['deskripsi_konten'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($item['tanggal_mulai'])->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($item['tanggal_selesai'])->format('d M Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $item['waktu_mulai'] }} - {{ $item['waktu_selesai'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $item['nama_pengusul'] }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item['fakultas'] }} / {{ $item['program_studi'] }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $item['jenis_pengusul'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">Vidotron Display</div>
                                        <div class="text-xs text-gray-500">{{ $item['jenis_konten'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'selesai' => 'bg-blue-100 text-blue-800'
                                            ];
                                            $statusText = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'selesai' => 'Selesai'
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$item['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusText[$item['status']] ?? $item['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-3xl mb-2"></i>
                                    <p class="text-lg">Tidak ada kegiatan ditemukan</p>
                                    <p class="text-sm mt-1">Belum ada peminjaman ruangan atau penyewaan vidotron</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($kegiatan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ $kegiatan->firstItem() }} sampai {{ $kegiatan->lastItem() }} dari {{ $kegiatan->total() }} kegiatan
                    </div>
                    <div class="flex space-x-2">
                        @if($kegiatan->onFirstPage())
                            <span class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $kegiatan->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        @foreach(range(1, min(3, $kegiatan->lastPage())) as $page)
                            @if($page == $kegiatan->currentPage())
                                <span class="px-3 py-1 bg-primary-600 text-white rounded text-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $kegiatan->url($page) }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($kegiatan->hasMorePages())
                            <a href="{{ $kegiatan->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <script>
        function resetFilter() {
            document.getElementById('filterJenis').value = 'semua';
            document.getElementById('filterStatus').value = 'semua';
            document.getElementById('filterDariTanggal').value = '';
            document.getElementById('filterSampaiTanggal').value = '';
            
            // Reload halaman tanpa filter
            window.location.href = window.location.pathname;
        }

        // Filter on change
        document.getElementById('filterJenis').addEventListener('change', applyFilter);
        document.getElementById('filterStatus').addEventListener('change', applyFilter);
        document.getElementById('filterDariTanggal').addEventListener('change', applyFilter);
        document.getElementById('filterSampaiTanggal').addEventListener('change', applyFilter);

        function applyFilter() {
            const jenis = document.getElementById('filterJenis').value;
            const status = document.getElementById('filterStatus').value;
            const dariTanggal = document.getElementById('filterDariTanggal').value;
            const sampaiTanggal = document.getElementById('filterSampaiTanggal').value;

            let params = new URLSearchParams();
            
            if (jenis !== 'semua') params.append('jenis', jenis);
            if (status !== 'semua') params.append('status', status);
            if (dariTanggal) params.append('dari_tanggal', dariTanggal);
            if (sampaiTanggal) params.append('sampai_tanggal', sampaiTanggal);

            window.location.href = window.location.pathname + '?' + params.toString();
        }
    </script>

    <style>
        .elegant-font {
            font-family: 'Playfair Display', serif;
        }
        
        .logo-container {
            transition: all 0.3s ease;
        }
        
        .logo-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</body>
</html>