@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-xl shadow-md">

        <div class="border-b px-6 py-4">
            <h2 class="text-2xl font-bold text-gray-700">
                Buat Jadwal Kegiatan
            </h2>
            <p class="text-gray-500 text-sm">
                Tambahkan kegiatan dan pilih pegawai yang akan mengikuti.
            </p>
        </div>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-2 gap-6">

                {{-- Nama --}}
                <div class="col-span-2">
                    <label class="block mb-2 font-semibold">
                        Nama Kegiatan
                    </label>

                    <input
                        type="text"
                        name="nama_kegiatan"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-600"
                        required>
                </div>

                {{-- Ruangan --}}
                <div>
                    <label class="block mb-2 font-semibold">
                        Ruangan
                    </label>

                    <select
                        name="ruangan_id"
                        class="w-full border rounded-lg px-4 py-3">

                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}">
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label class="block mb-2 font-semibold">
                        Kapasitas
                    </label>

                    <input
                        type="number"
                        name="kapasitas_peserta"
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block mb-2 font-semibold">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block mb-2 font-semibold">
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                {{-- Jam --}}
                <div>
                    <label class="block mb-2 font-semibold">
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        name="waktu_mulai"
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block mb-2 font-semibold">
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        name="waktu_selesai"
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                {{-- Deskripsi --}}
                <div class="col-span-2">
                    <label class="block mb-2 font-semibold">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="4"
                        class="w-full border rounded-lg px-4 py-3"></textarea>
                </div>

                {{-- Pegawai --}}
                <div class="col-span-2">
                    <label class="block mb-2 font-semibold">
                        Pilih Pegawai
                    </label>

                    <select
                        name="peserta[]"
                        multiple
                        class="w-full h-36 border rounded-lg px-4 py-3">

                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }}
                            </option>
                        @endforeach

                    </select>

                    <small class="text-gray-500">
                        Tekan Ctrl untuk memilih lebih dari satu pegawai.
                    </small>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">

                <a href="{{ route('admin.jadwal.index') }}"
                    class="px-5 py-3 rounded-lg bg-gray-300 hover:bg-gray-400">

                    Batal

                </a>

                <button
                    type="submit"
                    class="px-6 py-3 bg-red-700 text-white rounded-lg hover:bg-red-800">

                    Simpan Jadwal

                </button>

            </div>

        </form>

    </div>

</div>
@endsection