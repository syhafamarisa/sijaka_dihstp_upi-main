@extends('layouts.user')

@section('title', 'Edit Jadwal Kantor - Scheduler')

@section('page-title', 'Edit Jadwal Kantor')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header dengan Tombol Kembali -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Jadwal Kantor</h1>
            <p class="text-gray-600 mt-1">Perbarui jadwal kegiatan</p>
        </div>
        <a href="{{ route('pegawai.jadwal.index') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Jadwal
        </a>
    </div>

    <!-- Success/Error Messages -->
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
        <form action="{{ route('pegawai.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_kegiatan" 
                               value="{{ old('nama_kegiatan', $jadwal->nama_kegiatan) }}" 
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
                            @foreach($ruanganList as $ruangan)
                                <option value="{{ $ruangan->id }}" 
                                        {{ old('ruangan_id', $jadwal->ruangan_id) == $ruangan->id ? 'selected' : '' }}
                                        data-kapasitas="{{ $ruangan->kapasitas }}"
                                        data-status="{{ $ruangan->status }}">
                                    {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}
                                    @if($ruangan->kapasitas > 0)
                                        (Kapasitas: {{ $ruangan->kapasitas }} orang)
                                    @endif
                                    @if($ruangan->status != 'tersedia')
                                        - {{ ucfirst($ruangan->status) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih ruangan dari database
                        </p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal & Waktu <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 gap-4">
                            <input type="date" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('tanggal') border-red-500 @enderror" 
                                   required>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="time" 
                                           name="waktu_mulai" 
                                           value="{{ old('waktu_mulai', $jadwal->waktu_mulai) }}" 
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('waktu_mulai') border-red-500 @enderror" 
                                           placeholder="Mulai" 
                                           required>
                                    @error('waktu_mulai')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="time" 
                                           name="waktu_selesai" 
                                           value="{{ old('waktu_selesai', $jadwal->waktu_selesai) }}" 
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('waktu_selesai') border-red-500 @enderror" 
                                           placeholder="Selesai" 
                                           required>
                                    @error('waktu_selesai')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kapasitas Peserta
                        </label>
                        <input type="number" 
                               name="kapasitas_peserta" 
                               value="{{ old('kapasitas_peserta', $jadwal->kapasitas_peserta) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('kapasitas_peserta') border-red-500 @enderror" 
                               placeholder="Jumlah peserta"
                               min="1">
                        @error('kapasitas_peserta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500" id="kapasitasInfo">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kosongkan jika tidak ada batasan peserta
                        </p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" 
                          rows="4" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('deskripsi') border-red-500 @enderror" 
                          placeholder="Jelaskan detail kegiatan, agenda, dan informasi penting lainnya...">{{ old('deskripsi', $jadwal->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('pegawai.jadwal.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ruanganSelect = document.querySelector('select[name="ruangan_id"]');
        const kapasitasPesertaInput = document.querySelector('input[name="kapasitas_peserta"]');
        const kapasitasInfo = document.getElementById('kapasitasInfo');
        
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
        
        // Update kapasitas info berdasarkan ruangan yang dipilih
        ruanganSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.kapasitas) {
                const kapasitas = selectedOption.dataset.kapasitas;
                const status = selectedOption.dataset.status;
                
                if (kapasitas > 0) {
                    kapasitasInfo.innerHTML = `
                        <i class="fas fa-info-circle mr-1"></i>
                        Kapasitas maksimal ruangan: <strong>${kapasitas} orang</strong>
                        ${status != 'tersedia' ? ` (Status: ${status})` : ''}
                    `;
                    
                    // Set max value untuk input kapasitas
                    kapasitasPesertaInput.max = kapasitas;
                    
                    // Validasi jika nilai saat ini melebihi kapasitas
                    if (kapasitasPesertaInput.value && parseInt(kapasitasPesertaInput.value) > parseInt(kapasitas)) {
                        kapasitasPesertaInput.value = kapasitas;
                    }
                } else {
                    kapasitasInfo.innerHTML = `
                        <i class="fas fa-info-circle mr-1"></i>
                        Kosongkan jika tidak ada batasan peserta
                    `;
                    kapasitasPesertaInput.max = '';
                }
            }
        });
        
        // Trigger change event untuk inisialisasi
        if (ruanganSelect.value) {
            ruanganSelect.dispatchEvent(new Event('change'));
        }
        
        // Validasi kapasitas saat input
        kapasitasPesertaInput.addEventListener('input', function() {
            const selectedOption = ruanganSelect.options[ruanganSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.kapasitas) {
                const maxKapasitas = parseInt(selectedOption.dataset.kapasitas);
                const currentValue = parseInt(this.value) || 0;
                
                if (currentValue > maxKapasitas) {
                    this.value = maxKapasitas;
                    alert(`Kapasitas tidak boleh melebihi ${maxKapasitas} orang`);
                }
            }
        });
    });
</script>
@endpush
@endsection