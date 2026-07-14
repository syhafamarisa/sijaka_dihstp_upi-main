@extends('layouts.admin')

@section('content')

<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Jadwal
            </h1>
            <p class="text-gray-500">
                Informasi lengkap jadwal kegiatan
            </p>
        </div>

        <a href="{{ route('admin.jadwal.index') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Informasi Jadwal</h2>

            <div class="space-y-4">
                <div class="border-b pb-3">
                    <div class="text-sm text-gray-500">Kegiatan</div>
                    <div class="text-gray-900 font-medium">{{ $jadwal->nama_kegiatan }}</div>
                </div>

                <div class="border-b pb-3">
                    <div class="text-sm text-gray-500">Ruangan</div>
                    <div class="text-gray-900 font-medium">{{ $jadwal->ruangan->nama_ruangan ?? '-' }}</div>
                </div>

                <div class="border-b pb-3">
                    <div class="text-sm text-gray-500">Tanggal</div>
                    <div class="text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('d F Y') }}
                        @if(!empty($jadwal->tanggal_selesai))
                            s/d {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->translatedFormat('d F Y') }}
                        @endif
                    </div>
                </div>

                <div class="border-b pb-3">
                    <div class="text-sm text-gray-500">Waktu</div>
                    <div class="text-gray-900 font-medium">
                        {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}
                    </div>
                </div>

                @if(!empty($jadwal->deskripsi))
                    <div class="border-b pb-3">
                        <div class="text-sm text-gray-500">Deskripsi</div>
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $jadwal->deskripsi }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Peserta</h2>

            <div class="mb-4">
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                    {{ $jadwal->peserta->count() }} Pegawai
                </span>
            </div>

            @if($jadwal->peserta && $jadwal->peserta->count() > 0)
                <div class="space-y-2">
                    @foreach($jadwal->peserta as $pegawai)
                        <div class="border rounded-lg px-3 py-2">
                            <div class="font-medium text-gray-800">{{ $pegawai->name ?? $pegawai->nama ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $pegawai->role ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500">Belum ada peserta.</div>
            @endif
        </div>
    </div>
</div>

@endsection

