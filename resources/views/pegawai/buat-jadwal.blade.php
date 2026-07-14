@extends('layouts.user')

@section('title', 'Buat Jadwal Kantor - Scheduler')

@section('page-title', 'Buat Jadwal Kantor')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Jadwal Kantor</h1>
            <p class="text-gray-600 mt-1">Tambah jadwal kegiatan dan aktivitas kantor</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin
            </a>
            @endif
            <a href="{{ route('pegawai.dashboard') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <i class="fas fa-home mr-2"></i> Kembali ke Home
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('pegawai.jadwal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_kegiatan"
                               value="{{ old('nama_kegiatan') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('nama_kegiatan') border-red-500 @enderror"
                               placeholder="Masukkan nama kegiatan"
                               required>
                        @error('nama_kegiatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ruangan <span class="text-red-500">*</span>
                        </label>
                        <select name="ruangan_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('ruangan_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruangan as $room)
                                <option value="{{ $room->id }}" {{ old('ruangan_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->kode_ruangan }} - {{ $room->nama_ruangan }}
                                    (Kapasitas: {{ $room->kapasitas }})
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="tanggal_mulai"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('tanggal_mulai') }}"
                                required>

                            @error('tanggal_mulai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="tanggal_selesai"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                min="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                value="{{ old('tanggal_selesai', old('tanggal_mulai')) }}"
                                required>

                            @error('tanggal_selesai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mb-2 mt-6">
                        Waktu <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input type="time"
                                   name="waktu_mulai"
                                   value="{{ old('waktu_mulai', '08:00') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('waktu_mulai') border-red-500 @enderror"
                                   required>
                            @error('waktu_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="time"
                                   name="waktu_selesai"
                                   value="{{ old('waktu_selesai', '09:00') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('waktu_selesai') border-red-500 @enderror"
                                   required>
                            @error('waktu_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kapasitas Peserta
                        </label>
                        <input type="number"
                               name="kapasitas_peserta"
                               value="{{ old('kapasitas_peserta') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('kapasitas_peserta') border-red-500 @enderror"
                               placeholder="Jumlah peserta"
                               min="1">
                        @error('kapasitas_peserta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" 
                          rows="4" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('deskripsi') border-red-500 @enderror" 
                          placeholder="Jelaskan detail kegiatan...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi waktu selesai > waktu mulai
        const waktuMulaiInput = document.querySelector('input[name="waktu_mulai"]');
        const waktuSelesaiInput = document.querySelector('input[name="waktu_selesai"]');
        
        waktuMulaiInput.addEventListener('change', function() {
            if (waktuSelesaiInput.value && waktuSelesaiInput.value <= this.value) {
                alert('Waktu selesai harus setelah waktu mulai');
                waktuSelesaiInput.value = '';
                waktuSelesaiInput.focus();
            }
        });
        
        waktuSelesaiInput.addEventListener('change', function() {
            if (waktuMulaiInput.value && this.value <= waktuMulaiInput.value) {
                alert('Waktu selesai harus setelah waktu mulai');
                this.value = '';
                this.focus();
            }
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mulai = document.querySelector('input[name="tanggal_mulai"]');
    const selesai = document.querySelector('input[name="tanggal_selesai"]');

    selesai.value = mulai.value;

    mulai.addEventListener('change', function () {
        selesai.value = this.value;
    });
});
</script>
@endpush
@endsection

