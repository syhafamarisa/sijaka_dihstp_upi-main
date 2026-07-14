@extends('layouts.user')

@section('title', 'Jadwal Kantor')
@section('page-title', 'Jadwal Kantor')

@section('content')
@php
    // Helper functions untuk styling
    $stylingHelpers = [
        'getEventColor' => function() {
            return 'bg-red-100 text-red-800';
        },
        
        'getEventIcon' => function() {
            return 'building';
        },
        
        'getEventBgColor' => function() {
            return 'red';
        },
        
        'getEventTypeText' => function() {
            return 'Jadwal Kantor';
        }
    ];

    $dateObj = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
    $prevMonth = $dateObj->copy()->subMonth();
    $nextMonth = $dateObj->copy()->addMonth();
@endphp

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-primary-900 elegant-font">Jadwal Kegiatan Kantor</h1>
        <p class="text-gray-600">Kelola jadwal kegiatan dan aktivitas kantor</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('pegawai.semua-kegiatan') }}" 
           class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
            <i class="fas fa-list"></i>
            <span>Lihat Semua Jadwal</span>
        </a>
        <a href="{{ route('pegawai.jadwal.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
            <i class="fas fa-plus"></i>
            <span>Buat Jadwal</span>
        </a>
    </div>
</div>

<!-- Calendar View -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </a>
            <h3 class="text-lg font-semibold text-primary-900">Kalender Jadwal {{ $dateObj->translatedFormat('F Y') }}</h3>
            <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chevron-right text-gray-600"></i>
            </a>
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                <span class="text-xs">Jadwal Kantor</span>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-7 gap-2 mb-4">
        <div class="text-center font-semibold text-gray-600 py-2">Senin</div>
        <div class="text-center font-semibold text-gray-600 py-2">Selasa</div>
        <div class="text-center font-semibold text-gray-600 py-2">Rabu</div>
        <div class="text-center font-semibold text-gray-600 py-2">Kamis</div>
        <div class="text-center font-semibold text-gray-600 py-2">Jumat</div>
        <div class="text-center font-semibold text-gray-600 py-2">Sabtu</div>
        <div class="text-center font-semibold text-gray-600 py-2">Minggu</div>
        
        <!-- Calendar Days -->
        @php
            $daysInMonth = $dateObj->daysInMonth;
            $firstDayOfMonth = ($dateObj->dayOfWeek + 6) % 7;
            $currentDay = (\Carbon\Carbon::now()->month == $currentMonth && \Carbon\Carbon::now()->year == $currentYear) ? \Carbon\Carbon::now()->day : 0;
        @endphp

        <!-- Empty days for first week -->
        @for($i = 0; $i < $firstDayOfMonth; $i++)
            <div class="h-24 border border-gray-200 rounded-lg"></div>
        @endfor

        <!-- Calendar Days -->
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $hasEvents = isset($calendarEvents[$day]) && count($calendarEvents[$day]) > 0;
                $isToday = $day == $currentDay;
                $dayEvents = $calendarEvents[$day] ?? [];
            @endphp
            
            <div class="h-24 border border-gray-200 rounded-lg p-2 hover:bg-gray-50 cursor-pointer transition-colors
                {{ $hasEvents ? 'bg-blue-50 border-blue-300' : '' }}
                {{ $isToday ? 'ring-2 ring-primary-500' : '' }}">
                <div class="flex justify-between items-start mb-1">
                    <span class="text-sm font-medium {{ $hasEvents ? 'text-blue-700' : ($isToday ? 'text-primary-700' : 'text-gray-700') }}">{{ $day }}</span>
                    @if($hasEvents)
                        <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                    @endif
                </div>
                
                <!-- Events -->
                @foreach(array_slice($dayEvents, 0, 2) as $event)
                    <div class="text-xs {{ $stylingHelpers['getEventColor']() }} px-1 py-0.5 rounded mb-1 truncate"
                         title="{{ $event['nama_kegiatan'] }}">
                        {{ $event['nama_kegiatan'] }}
                    </div>
                @endforeach
                
                @if(count($dayEvents) > 2)
                    <div class="text-xs text-gray-500 px-1 py-0.5">
                        +{{ count($dayEvents) - 2 }} lainnya
                    </div>
                @endif
            </div>
        @endfor
    </div>
</div>

<!-- Today's Schedule -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-primary-900">Kegiatan Hari Ini</h3>
        <span class="px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full">{{ now()->translatedFormat('d F Y') }}</span>
    </div>
    
    @if($kegiatanHariIni->count() > 0)
        <div class="space-y-4">
@foreach($kegiatanHariIni as $jadwal)
                @php
                    $waktu = (!empty($jadwal['waktu_mulai']))
                        ? \Carbon\Carbon::parse($jadwal['waktu_mulai'])->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwal['waktu_selesai'])->format('H:i')
                        : 'Seluruh Hari';
                @endphp
                
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
<p class="font-medium text-gray-800">{{ $jadwal['nama_kegiatan'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-600">{{ $waktu }} • {{ $jadwal['lokasi'] ?? '-' }}</p>
                                    @if(!empty($jadwal['deskripsi']) && $jadwal['deskripsi'] != '-')
<p class="text-xs text-red-600 mt-1">{{ Str::limit($jadwal['deskripsi'], 100) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <span>Tipe: Jadwal Kantor</span>
@if(!empty($jadwal['user']['name']))
                                    <span class="ml-4">Oleh: {{ $jadwal['user']['name'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-calendar-day text-4xl mb-3 text-gray-300"></i>
            <p class="text-lg">Tidak ada kegiatan kantor untuk hari ini</p>
        </div>
    @endif
</div>

<!-- Kegiatan List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-primary-900">Daftar Jadwal Kantor Terbaru</h3>
        <span class="text-sm text-gray-500">
            Menampilkan {{ $kegiatanTerbaru->count() }} jadwal
        </span>
    </div>
    
    @if($kegiatanTerbaru->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Kegiatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Tanggal/Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-primary-900 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($kegiatanTerbaru as $kegiatan)
                    @php
                            $waktu = $kegiatan['waktu_mulai'] ? 
                                \Carbon\Carbon::parse($kegiatan['waktu_mulai'])->format('H:i') . ' - ' . 
                                \Carbon\Carbon::parse($kegiatan['waktu_selesai'])->format('H:i') : 
                                'Seluruh Hari';
$tanggalFormat = isset($kegiatan['tanggal_mulai'])
                                ? \Carbon\Carbon::parse($kegiatan['tanggal_mulai'])->translatedFormat('d M Y')
                                : (isset($kegiatan['tanggal'])
                                    ? \Carbon\Carbon::parse($kegiatan['tanggal'])->translatedFormat('d M Y')
                                    : '');
                        @endphp
                        
                        <tr class="hover:bg-gray-50" data-kegiatan='@json($kegiatan)'>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-building text-red-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $kegiatan['nama_kegiatan'] }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full">
                                                Jadwal Kantor
                                            </span>
                                        </div>
                                        @if($kegiatan['deskripsi'] && $kegiatan['deskripsi'] != '-')
                                            <div class="text-sm text-gray-500">{{ Str::limit($kegiatan['deskripsi'], 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $kegiatan['tanggal_mulai'] }}</div>
                                <div class="text-sm text-gray-500">{{ substr($kegiatan['waktu_mulai'], 0, 5) }} - {{ substr($kegiatan['waktu_selesai'], 0, 5) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $kegiatan['lokasi'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kegiatan['creator'] ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- Tombol Detail -->
                                    <button type="button" 
                                            class="detail-btn text-green-600 hover:text-green-900"
                                            title="Lihat Detail"
                                            data-id="{{ $kegiatan['model_id'] }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                     <!-- Tombol Edit - PERBAIKAN: Gunakan route yang benar -->
                                    <a href="{{ route('pegawai.jadwal.edit', $kegiatan['model_id']) }}" 
                                    class="text-blue-600 hover:text-blue-900"
                                    title="Edit Jadwal">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Tombol Delete -->
                                    <button type="button" 
                                            class="delete-btn text-red-600 hover:text-red-900"
                                            title="Hapus Jadwal"
                                            data-id="{{ $kegiatan['model_id'] }}"
                                            data-nama="{{ $kegiatan['nama_kegiatan'] }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-calendar-times text-5xl mb-4 text-gray-300"></i>
            <p class="text-lg font-semibold">Belum ada jadwal kantor</p>
            <p class="text-sm">Tidak ada jadwal kantor yang ditemukan</p>
        </div>
    @endif
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Total {{ $jadwalKantor->count() }} jadwal kantor ditemukan
            </div>
            <a href="{{ route('pegawai.semua-kegiatan') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-colors">
                <i class="fas fa-list"></i>
                <span>Lihat Semua Jadwal</span>
            </a>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-primary-900">Detail Jadwal</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6" id="detailContent">
            <!-- Konten detail akan diisi oleh JavaScript -->
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button type="button" class="close-modal bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-semibold">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-primary-900">Edit Jadwal</h3>
            <button type="button" class="close-edit-modal text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kegiatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <select name="lokasi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="a101">A101 - Ruang Rapat Besar</option>
                        <option value="a102">A102 - Ruang conference</option>
                        <option value="b201">B201 - Ruang Digital corner</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="close-edit-modal bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
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
            <button type="button" class="cancel-delete bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Element modal
    const detailModal = document.getElementById('detailModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    const detailContent = document.getElementById('detailContent');
    const editForm = document.getElementById('editForm');
    const deleteForm = document.getElementById('deleteForm');
    const deleteNama = document.getElementById('deleteNama');
    
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
        // waktu bisa string: "08:00:00" atau "08:00"
        if (typeof waktu !== 'string') return '-';
        if (waktu.length === 5) return waktu;
        if (waktu.length >= 8) return waktu.substring(0, 5);
        return waktu;
    }
    
    // Peta lokasi untuk tampilan
    const lokasiMap = {
        'a101': 'A101 - Ruang Rapat Besar',
        'a102': 'A102 - Ruang Conference',
        'b201': 'B201 - Ruang Digital Corner',
    };
    
    // Fungsi untuk menampilkan detail
    function showDetail(jadwal) {
        let content = `
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-building text-red-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-gray-900">${jadwal.nama_kegiatan}</h4>
                        <p class="text-sm text-gray-600">Jadwal Kantor</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-2">Informasi Jadwal</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tanggal:</span>
                                <span class="text-sm font-medium">${jadwal.tanggal_mulai ? formatTanggal(jadwal.tanggal_mulai) : (jadwal.tanggal ? formatTanggal(jadwal.tanggal) : '-')}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Waktu:</span>
                                <span class="text-sm font-medium">${formatWaktu(jadwal.waktu_mulai)} - ${formatWaktu(jadwal.waktu_selesai)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Lokasi:</span>
                                <span class="text-sm font-medium">${lokasiMap[jadwal.lokasi] || jadwal.lokasi}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-2">Informasi Tambahan</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Dibuat:</span>
                                <span class="text-sm font-medium">${new Date(jadwal.created_at).toLocaleDateString('id-ID')}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Dibuat Oleh:</span>
                                <span class="text-sm font-medium">${jadwal.creator || 'Staff'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tipe:</span>
                                <span class="text-sm font-medium">Jadwal Kantor</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${jadwal.deskripsi && jadwal.deskripsi !== '-' ? `
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2">Deskripsi</h5>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-700">${jadwal.deskripsi}</p>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        detailContent.innerHTML = content;
        detailModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    // Event listener untuk tombol detail
    document.querySelectorAll('.detail-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const jadwal = JSON.parse(row.getAttribute('data-kegiatan'));
            showDetail(jadwal);
        });
    });
    
// Update modal edit untuk mengambil data dari database
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        
        // Ambil data via AJAX
        fetch(`/pegawai/jadwal/${id}/data`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const jadwal = data.jadwal;
                    
                    // Isi form dengan data yang ada
                    document.querySelector('#editForm input[name="nama_kegiatan"]').value = jadwal.nama_kegiatan || '';
                    document.querySelector('#editForm input[name="tanggal"]').value = jadwal.tanggal || '';
                    document.querySelector('#editForm input[name="waktu_mulai"]').value = jadwal.waktu_mulai || '';
                    document.querySelector('#editForm input[name="waktu_selesai"]').value = jadwal.waktu_selesai || '';
                    document.querySelector('#editForm select[name="ruangan_id"]').value = jadwal.ruangan_id || '';
                    document.querySelector('#editForm input[name="kapasitas_peserta"]').value = jadwal.kapasitas_peserta || '';
                    document.querySelector('#editForm textarea[name="deskripsi"]').value = jadwal.deskripsi || '';
                    
                    // Set action form untuk PUT request
                    document.getElementById('editForm').action = `/pegawai/jadwal/${id}`;
                    
                    // Tampilkan modal
                    document.getElementById('editModal').classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching jadwal data:', error);
                alert('Gagal memuat data jadwal');
            });
    });
});
    
    // Event listener untuk tombol delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            
            console.log('Delete clicked:', { id, nama });
            
            deleteNama.textContent = nama;
            deleteForm.action = `/pegawai/jadwal/${id}`;
            
            deleteModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });
    
    // Event listener untuk close modal
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            detailModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    document.querySelectorAll('.close-edit-modal').forEach(button => {
        button.addEventListener('click', function() {
            editModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    document.querySelectorAll('.cancel-delete').forEach(button => {
        button.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    // Close modal dengan ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            detailModal.classList.add('hidden');
            editModal.classList.add('hidden');
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
    
    // Close modal dengan klik di luar modal
    [detailModal, editModal, deleteModal].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        }
    });
    
    // Handle form submit edit
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;
            
            // Kirim data via AJAX
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: new FormData(this)
            })
            .then(response => {
                console.log('Edit response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Edit success:', data);
                if (data.success) {
                    editModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    showNotification('success', data.message || 'Jadwal berhasil diperbarui!');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Gagal memperbarui jadwal');
                }
            })
            .catch(error => {
                console.error('Edit error:', error);
                showNotification('error', error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Handle form submit delete
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Delete form submitted');
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
            submitBtn.disabled = true;
            
            // Ambil token CSRF dari meta tag atau input
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            console.log('CSRF Token:', csrfToken ? 'Found' : 'Not found');
            console.log('Delete URL:', this.action);
            
            // Kirim request DELETE dengan method override
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
                console.log('Delete response status:', response.status);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Delete error response:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete success data:', data);
                
                if (data.success) {
                    // Tutup modal
                    deleteModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    
                    // Tampilkan notifikasi sukses
                    showNotification('success', data.message || 'Jadwal berhasil dihapus!');
                    
                    // Reload halaman setelah delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Gagal menghapus jadwal');
                }
            })
            .catch(error => {
                console.error('Delete fetch error:', error);
                
                let errorMessage = error.message;
                if (errorMessage.includes('405')) {
                    errorMessage = 'Method tidak diizinkan. Pastikan route DELETE sudah didefinisikan.';
                } else if (errorMessage.includes('403')) {
                    errorMessage = 'Akses ditolak. Periksa hak akses atau token CSRF.';
                } else if (errorMessage.includes('404')) {
                    errorMessage = 'Jadwal tidak ditemukan. Mungkin sudah dihapus.';
                }
                
                showNotification('error', errorMessage);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
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
    
    // Tambahkan style untuk modal backdrop
    const style = document.createElement('style');
    style.textContent = `
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
        .overflow-hidden {
            overflow: hidden;
        }
        .fixed-notification {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    
    console.log('Jadwal script loaded successfully');
});
</script>
@endpush
@endsection