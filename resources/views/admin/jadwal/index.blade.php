@extends('layouts.admin')

@section('content')

<div class="p-6">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Jadwal Kegiatan
            </h1>

            <p class="text-gray-500">
                Daftar seluruh kegiatan pegawai
            </p>
        </div>

        <a href="{{ route('admin.jadwal.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">

            <i class="fas fa-plus mr-2"></i>
            Tambah Jadwal

        </a>

    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Kegiatan</th>
                    <th class="px-4 py-3 text-left">Ruangan</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-center">Peserta</th>
                    <th class="px-4 py-3 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody>

            @forelse($jadwal as $item)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-4 py-3 font-medium">
                        {{ $item->nama_kegiatan }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->ruangan->nama_ruangan ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}
                    </td>

                    <td class="px-4 py-3 text-center">

                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                            {{ $item->peserta->count() }} Pegawai

                        </span>

                    </td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('admin.jadwal.show', $item->id) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

                            Detail

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6"
                        class="text-center py-8 text-gray-500">

                        Belum ada jadwal.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-5">

        {{ $jadwal->links() }}

    </div>

</div>

@endsection