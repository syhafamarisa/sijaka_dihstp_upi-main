<div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
    <!-- Custom scrollbar styling -->
    <style>
        .scrollable-content::-webkit-scrollbar {
            width: 6px;
        }
        .scrollable-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollable-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .scrollable-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    <!-- Bagian Informasi pengusul -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
            <i class="fas fa-user-circle mr-2 text-primary-600"></i>
            Informasi pengusul
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><strong>Jenis pengusul:</strong><br>
                    @switch($peminjaman->jenis_pengusul)
                        @case('mahasiswa')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Mahasiswa</span>
                            @break
                        @case('dosen')
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Dosen</span>
                            @break
                        @case('staff')
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Staff</span>
                            @break
                        @case('tamu')
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">Tamu</span>
                            @break
                    @endswitch
                </p>
                <p class="mt-2"><strong>Nama:</strong><br>{{ $peminjaman->nama_pengusul }}</p>
                <p class="mt-2"><strong>NIM/NIP:</strong><br>{{ $peminjaman->nim_nip }}</p>
            </div>
            <div>
                <p><strong>Fakultas:</strong><br>{{ $peminjaman->fakultas }}</p>
                <p class="mt-2"><strong>Program Studi:</strong><br>{{ $peminjaman-> ?: '-' }}</p>
                <p class="mt-2"><strong>Email:</strong><br>{{ $peminjaman->email }}</p>
                <p class="mt-2"><strong>Telepon:</strong><br>{{ $peminjaman->no_telepon }}</p>
            </div>
        </div>
    </div>

    <!-- Bagian Informasi Ruangan -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
            <i class="fas fa-door-open mr-2 text-primary-600"></i>
            Informasi Ruangan
        </h4>
        <div class="mt-2 space-y-2">
            <p><strong>Ruangan:</strong> {{ $peminjaman->ruangan->kode_ruangan }} - {{ $peminjaman->ruangan->nama_ruangan }}</p>
            <p><strong>Kapasitas:</strong> {{ $peminjaman->ruangan->kapasitas }} orang</p>
        </div>
    </div>
    
    <!-- Bagian Detail Acara -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3">Detail Acara</h4>
        <div class="mt-2 space-y-2">
            <p><strong>Acara/Kegiatan:</strong> {{ $peminjaman->acara }}</p>
            <p><strong>Jumlah Peserta:</strong> {{ $peminjaman->jumlah_peserta }} orang</p>
        </div>
    </div>

    <!-- Bagian Jadwal -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3">Jadwal Peminjaman</h4>
        <div class="mt-2 space-y-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <p><strong>Hari:</strong><br>{{ $peminjaman->hari }}</p>
                </div>
                <div>
                    <p><strong>Tanggal:</strong><br>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p><strong>Waktu:</strong><br>{{ $peminjaman->jam_mulai }} - {{ $peminjaman->jam_selesai }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($peminjaman->keterangan)
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3">Keterangan Tambahan</h4>
        <div class="mt-2">
            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $peminjaman->keterangan }}</p>
        </div>
    </div>
    @endif

    <!-- Lampiran Surat -->
    @if($peminjaman->lampiran_surat)
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3">Lampiran Surat</h4>
        <div class="mt-2">
            <a href="{{ Storage::url($peminjaman->lampiran_surat) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                <i class="fas fa-file-pdf mr-2"></i>
                Lihat Lampiran
            </a>
        </div>
    </div>
    @endif

    <!-- Bagian Status -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-gray-700 mb-3">Status</h4>
        <div class="mt-2 space-y-2">
            @php
                $statusConfig = [
                    'menunggu' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Menunggu Persetujuan'],
                    'disetujui' => ['color' => 'green', 'icon' => 'check', 'text' => 'Disetujui'],
                    'ditolak' => ['color' => 'red', 'icon' => 'times', 'text' => 'Ditolak'],
                    'selesai' => ['color' => 'gray', 'icon' => 'flag-checkered', 'text' => 'Selesai'],
                    'dibatalkan' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Dibatalkan']
                ];
                $config = $statusConfig[$peminjaman->status];
            @endphp
            <p><strong>Status:</strong></p>
            <span class="px-3 py-2 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 text-sm rounded-lg flex items-center w-fit">
                <i class="fas fa-{{ $config['icon'] }} mr-2"></i>
                {{ $config['text'] }}
            </span>
            
            @if($peminjaman->status == 'ditolak' && $peminjaman->alasan_penolakan)
                <div class="mt-2">
                    <p><strong>Alasan Penolakan:</strong></p>
                    <div class="bg-red-50 p-3 rounded-lg mt-1">
                        <p class="text-red-700">{{ $peminjaman->alasan_penolakan }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bagian Timeline -->
    <div>
        <h4 class="font-semibold text-gray-700 mb-3">Timeline</h4>
        <div class="mt-2 space-y-1 text-sm text-gray-600">
            <p><strong>Diajukan pada:</strong><br>{{ $peminjaman->created_at->translatedFormat('d F Y H:i') }}</p>
            <p><strong>Terakhir diupdate:</strong><br>{{ $peminjaman->updated_at->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>
</div>