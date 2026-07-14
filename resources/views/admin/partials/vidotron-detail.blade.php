<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="font-semibold text-gray-700">Informasi pengusul</h4>
            <div class="mt-2 space-y-2">
                <p><strong>Nama:</strong> {{ $penyewaan->user->name }}</p>
                <p><strong>Nama pengusul:</strong> {{ $penyewaan->nama_pengusul }}</p>
                <p><strong>Jenis pengusul:</strong> {{ ucfirst($penyewaan->jenis_pengusul) }}</p>
                <p><strong>NIM/NIDN:</strong> {{ $penyewaan->nim_nidn }}</p>
                <p><strong>Email:</strong> {{ $penyewaan->email }}</p>
                <p><strong>No. Telepon:</strong> {{ $penyewaan->no_telepon }}</p>
            </div>
        </div>
        
        <div>
            <h4 class="font-semibold text-gray-700">Informasi Institusi</h4>
            <div class="mt-2 space-y-2">
                <p><strong>Fakultas:</strong> {{ $penyewaan->fakultas }}</p>
                <p><strong>Program Studi:</strong> {{ $penyewaan->program_studi }}</p>
            </div>
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Detail Peminjaman</h4>
        <div class="mt-2 space-y-2">
            <p><strong>Tujuan Pemasangan:</strong> {{ $penyewaan->tujuan_pemasangan }}</p>
            <p><strong>Periode:</strong> 
                {{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->translatedFormat('d F Y') }} - 
                {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->translatedFormat('d F Y') }}
            </p>
            <p><strong>Waktu:</strong> {{ $penyewaan->waktu_mulai }} - {{ $penyewaan->waktu_selesai }}</p>
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Informasi Konten</h4>
        <div class="mt-2 space-y-2">
            <p><strong>Jenis Konten:</strong> {{ ucfirst($penyewaan->jenis_konten) }}</p>
            <p><strong>Deskripsi Konten:</strong> {{ $penyewaan->deskripsi_konten }}</p>
            @if($penyewaan->link_konten)
                <p><strong>Link Konten:</strong> 
                    <a href="{{ $penyewaan->link_konten }}" target="_blank" class="text-blue-600 hover:underline">
                        {{ $penyewaan->link_konten }}
                    </a>
                </p>
            @endif
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Status & Dokumen</h4>
        <div class="mt-2 space-y-2">
            @php
                $statusColors = [
                    'menunggu' => 'yellow',
                    'disetujui' => 'green', 
                    'ditolak' => 'red',
                    'selesai' => 'blue',
                    'dibatalkan' => 'gray'
                ];
                $statusText = [
                    'menunggu' => 'Menunggu Persetujuan',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan'
                ];
            @endphp
            <p><strong>Status:</strong> 
                <span class="px-2 py-1 bg-{{ $statusColors[$penyewaan->status] }}-100 text-{{ $statusColors[$penyewaan->status] }}-800 text-sm rounded">
                    {{ $statusText[$penyewaan->status] }}
                </span>
            </p>
            
            @if($penyewaan->status == 'ditolak' && $penyewaan->alasan_penolakan)
                <p><strong>Alasan Penolakan:</strong> 
                    <span class="text-red-600">{{ $penyewaan->alasan_penolakan }}</span>
                </p>
            @endif
            
            @if($penyewaan->surat_pengajuan)
                <p><strong>Surat Pengajuan:</strong> 
                    <a href="{{ route('admin.penyewaan-vidotron.download-surat', $penyewaan->id) }}" 
                       class="text-green-600 hover:underline flex items-center space-x-1">
                        <i class="fas fa-download"></i>
                        <span>Download Surat</span>
                    </a>
                </p>
            @endif
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-700">Timeline</h4>
        <div class="mt-2 space-y-1 text-sm text-gray-600">
            <p><strong>Diajukan pada:</strong> {{ $penyewaan->created_at->translatedFormat('d F Y H:i') }}</p>
            <p><strong>Terakhir diupdate:</strong> {{ $penyewaan->updated_at->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>
</div>