{{-- resources/views/user/peminjaman-ruangan.blade.php --}}
@extends('layouts.user')

@section('title', 'Peminjaman Ruangan - Scheduler')
@section('page-title', 'Peminjaman Ruangan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Peminjaman -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Form Peminjaman Ruangan</h2>
                
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user.peminjaman-ruangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
<!-- Bagian Informasi Pengusul -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary-600"></i>
Informasi Pengusul
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
Jenis Pengusul *
                                <select name="jenis_pengusul" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
Pilih Jenis Pengusul
                                    <option value="mahasiswa" {{ old('jenis_pengusul') == 'mahasiswa' || ($user->role ?? '') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="dosen" {{ old('jenis_pengusul') == 'dosen' || ($user->role ?? '') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="staff" {{ old('jenis_pengusul') == 'staff' || ($user->role ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="tamu" {{ old('jenis_pengusul') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                                </select>
@error('jenis_pengusul')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
<input type="text" name="nama_pengusul" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan nama lengkap" value="{{ old('nama_pengusul', $user->name ?? '') }}" required>
                                @error('nama_pengusul')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIM/NIDN *</label>
                                <input type="text" name="nim_nip_nidn" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan NIM/NIDN atau NIP" value="{{ old('nim_nip_nidn', $user->nim_nidn ?? $user->nim_nip ?? '') }}" required>
                                @error('nim_nip')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fakultas/Unit Kerja *</label>
                                <input type="text" name="fakultas" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan nama fakultas" value="{{ old('fakultas', $user->fakultas ?? '') }}" required>
                                @error('fakultas')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                                <input type="text" name="program_studi" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan program studi" value="{{ old('program_studi', $user->program_studi ?? '') }}">
                                @error('program_studi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan email aktif" value="{{ old('email', $user->email ?? '') }}" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                                <input type="tel" name="no_telepon" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan nomor telepon" value="{{ old('no_telepon', $user->no_telepon ?? '') }}" required>
                                @error('no_telepon')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Informasi Peminjaman (yang sudah ada) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Acara/Kegiatan *</label>
                            <input type="text" name="acara" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan nama acara/kegiatan" value="{{ old('acara') }}" required>
                            @error('acara')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pilih Ruangan --}}
<div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Pilih Ruangan *
    </label>

    <select name="ruangan_id"
        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        required>
        <option value="">Pilih Ruangan</option>
        @foreach($ruangan as $room)
            <option value="{{ $room->id }}"
                {{ old('ruangan_id') == $room->id ? 'selected' : '' }}>
                {{ $room->kode_ruangan }} - {{ $room->nama_ruangan }}
                (Kapasitas {{ $room->kapasitas }})
            </option>
        @endforeach
    </select>

    @error('ruangan_id')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Tanggal --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Tanggal Mulai *
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
        Tanggal Selesai *
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

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai *</label>
                            <input type="time" name="jam_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai *</label>
                            <input type="time" name="jam_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" value="{{ old('jam_selesai') }}" required>
                            @error('jam_selesai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Peserta *</label>
                            <input type="number" name="jumlah_peserta" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan jumlah peserta" value="{{ old('jumlah_peserta') }}" required>
                            @error('jumlah_peserta')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Tambahkan keterangan mengenai acara...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran Surat </label>
<input type="file" name="lampiran_surat" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" accept=".pdf,.doc,.docx,.jpg,.png" required>
                            <p class="text-sm text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, PNG (Maks. 2MB)</p>
                            @error('lampiran_surat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Ruangan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Ruangan</h3>
                
                <div class="space-y-4">
                    @foreach($ruangan as $room)
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-800">{{ $room->kode_ruangan }} - {{ $room->nama_ruangan }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Kapasitas: {{ $room->kapasitas }} orang</p>
                        <p class="text-sm text-gray-600">Fasilitas: {{ $room->fasilitas }}</p>
                        <p class="text-sm mt-2">
                            <span class="px-2 py-1 rounded-full text-xs {{ $room->status == 'tersedia' ? 'bg-green-100 text-green-800' : ($room->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="peminjaman-ruangan"]');
    const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
    const jamMulaiInput = document.querySelector('input[name="jam_mulai"]');

    form?.addEventListener('submit', function(e) {
        const todayObj = new Date();
        // Convert to local YYYY-MM-DD
        const year = todayObj.getFullYear();
        const month = String(todayObj.getMonth() + 1).padStart(2, '0');
        const day = String(todayObj.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;

        // Validasi hanya jika tanggal mulai = hari ini
        if (tanggalMulaiInput && tanggalMulaiInput.value === todayStr) {
            const currentHourMin = todayObj.toTimeString().split(' ')[0].substring(0, 5); // "HH:MM"
            if (jamMulaiInput && jamMulaiInput.value < currentHourMin) {
                e.preventDefault();
                alert('Jam mulai tidak boleh kurang dari waktu sekarang.');
                jamMulaiInput.focus();
            }
        }
    });

    // Auto-set tanggal_selesai mengikuti tanggal_mulai (opsional, biar mudah buat interval satu hari)
    const tanggalSelesaiInput = document.querySelector('input[name="tanggal_selesai"]');
    if (tanggalMulaiInput && tanggalSelesaiInput) {
        tanggalSelesaiInput.value = tanggalMulaiInput.value;
        tanggalMulaiInput.addEventListener('change', function () {
            tanggalSelesaiInput.value = this.value;
        });
    }
});
</script>
@endsection