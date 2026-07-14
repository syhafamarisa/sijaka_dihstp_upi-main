<div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2 scrollable-content">
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h4 class="font-semibold text-gray-700">Informasi pengusul</h4>
            <div class="mt-2 space-y-2">
                <p><strong>Nama Pengusul:</strong><br>{{ $penyewaan->nama_pengusul }}</p>
                <p><strong>Jenis Pengusul:</strong><br>{{ ucfirst($penyewaan->jenis_pengusul) }}</p>
                <p><strong>NIM/NIDN:</strong><br>{{ $penyewaan->nim_nidn }}</p>
            </div>
        </div>
        
        <div>
            <h4 class="font-semibold text-gray-700">Informasi Institusi</h4>
            <div class="mt-2 space-y-2">
                <p><strong>Fakultas:</strong><br>{{ $penyewaan->fakultas }}</p>
                <p><strong>Program Studi:</strong><br>{{ $penyewaan->program_studi }}</p>
            </div>
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Detail Peminjaman</h4>
        <div class="mt-2 space-y-2">
            <p><strong>Tujuan Pemasangan:</strong><br>{{ $penyewaan->tujuan_pemasangan }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <p><strong>Periode:</strong><br>
                        {{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->translatedFormat('d F Y') }} - 
                        {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div>
                    <p><strong>Waktu:</strong><br>{{ $penyewaan->waktu_mulai }} - {{ $penyewaan->waktu_selesai }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Informasi Konten</h4>
        <div class="mt-2 space-y-2">
            <p><strong>Jenis Konten:</strong><br>{{ ucfirst($penyewaan->jenis_konten) }}</p>
            <p><strong>Deskripsi Konten:</strong></p>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-gray-700">{{ $penyewaan->deskripsi_konten }}</p>
            </div>
            @if($penyewaan->link_konten)
                <p><strong>Link Konten:</strong><br>
                    <a href="{{ $penyewaan->link_konten }}" target="_blank" class="text-blue-600 hover:underline break-words">
                        {{ Str::limit($penyewaan->link_konten, 50) }}
                    </a>
                </p>
            @endif
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Status & Dokumen</h4>
        <div class="mt-2 space-y-2">
            @php
                $statusConfig = [
                    'menunggu' => ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Menunggu Persetujuan'],
                    'disetujui' => ['color' => 'green', 'icon' => 'check', 'text' => 'Disetujui'],
                    'ditolak' => ['color' => 'red', 'icon' => 'times', 'text' => 'Ditolak'],
                    'selesai' => ['color' => 'gray', 'icon' => 'flag-checkered', 'text' => 'Selesai'],
                    'dibatalkan' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Dibatalkan']
                ];
                $config = $statusConfig[$penyewaan->status];
            @endphp
            <p><strong>Status:</strong></p>
            <span class="px-3 py-2 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 text-sm rounded-lg flex items-center w-fit">
                <i class="fas fa-{{ $config['icon'] }} mr-2"></i>
                {{ $config['text'] }}
            </span>
            
            @if($penyewaan->status == 'ditolak' && $penyewaan->alasan_penolakan)
                <div class="mt-2">
                    <p><strong>Alasan Penolakan:</strong></p>
                    <div class="bg-red-50 p-3 rounded-lg mt-1">
                        <p class="text-red-700">{{ $penyewaan->alasan_penolakan }}</p>
                    </div>
                </div>
            @endif
            
            @if($penyewaan->lampiran_surat)
                <div class="mt-2">
                    <p><strong>Lampiran Surat:</strong></p>
                    <a href="{{ route('user.penyewaan-vidotron.download-surat', $penyewaan->id) }}" 
                       class="text-green-600 hover:underline flex items-center space-x-2 mt-1">
                        <i class="fas fa-download"></i>
                        <span>Download Surat Pengajuan</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Timeline</h4>
        <div class="mt-2 space-y-1 text-sm text-gray-600">
            <p><strong>Diajukan pada:</strong><br>{{ $penyewaan->created_at->translatedFormat('d F Y H:i') }}</p>
            <p><strong>Terakhir diupdate:</strong><br>{{ $penyewaan->updated_at->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>
</div>