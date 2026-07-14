@extends('layouts.user')

@section('title', 'Peminjaman Video Trone')
@section('page-title', 'Peminjaman Video Trone')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Peminjaman Video Trone</h1>
        <p class="text-gray-600 mt-2">Isi form berikut untuk mengajukan peminjaman video trone</p>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('peminjaman.video-trone.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informasi pengusul -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user-circle mr-2 text-primary-600"></i>
                    Informasi Pengusul
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Fakultas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fakultas <span class="text-red-500">*</span>
                        </label>
                        <select name="fakultas" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Fakultas</option>
                            <option value="FPOK" {{ old('fakultas') == 'FPOK' ? 'selected' : '' }}>Fakultas Pendidikan Olahraga dan Kesehatan</option>
                            <option value="FIP" {{ old('fakultas') == 'FIP' ? 'selected' : '' }}>Fakultas Ilmu Pendidikan</option>
                            <option value="FPBS" {{ old('fakultas') == 'FPBS' ? 'selected' : '' }}>Fakultas Pendidikan Bahasa dan Sastra</option>
                            <option value="FPTK" {{ old('fakultas') == 'FPTK' ? 'selected' : '' }}>Fakultas Pendidikan Teknologi dan Kejuruan</option>
                            <option value="FPMIPA" {{ old('fakultas') == 'FPMIPA' ? 'selected' : '' }}>Fakultas Pendidikan Matematika dan Ilmu Pengetahuan Alam</option>
                            <option value="FPEB" {{ old('fakultas') == 'FPEB' ? 'selected' : '' }}>Fakultas Pendidikan Ekonomi dan Bisnis</option>
                            <option value="FPIPS" {{ old('fakultas') == 'FPIPS' ? 'selected' : '' }}>Fakultas Pendidikan Ilmu Pengetahuan Sosial</option>
                            <option value="FPSD" {{ old('fakultas') == 'FPSD' ? 'selected' : '' }}>Fakultas Pendidikan Seni dan Desain</option>
                            <option value="SPs" {{ old('fakultas') == 'SPs' ? 'selected' : '' }}>Program Pascasarjana</option>

                        </select>
                        @error('fakultas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program_studi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="program_studi" value="{{ old('program_studi') }}" required
                            placeholder="Masukkan program studi"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('program_studi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis pengusul -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Pengusul <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_pengusul" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Jenis Pengusul</option>
                            <option value="dosen" {{ old('jenis_pengusul') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="staff" {{ old('jenis_pengusul') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="mahasiswa" {{ old('jenis_pengusul') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="organisasi" {{ old('jenis_pengusul') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                        </select>
                        @error('jenis_pengusul')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama pengusul -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama pengusul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pengusul" value="{{ old('nama_pengusul') }}" required
                            placeholder="Masukkan nama lengkap"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('nama_pengusul')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIM/NIDN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            NIM/NIDN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nim_nidn" value="{{ old('nim_nidn') }}" required
                            placeholder="Masukkan NIM atau NIDN"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('nim_nidn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Masukkan email aktif"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required
                            placeholder="Contoh: 081234567890"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('no_telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-calendar-alt mr-2 text-primary-600"></i>
                    Informasi Peminjaman
                </h3>
                
                <!-- Tujuan Pemasangan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tujuan Pemasangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tujuan_pemasangan" value="{{ old('tujuan_pemasangan') }}" required
                        placeholder="Jelaskan tujuan pemasangan video trone"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('tujuan_pemasangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('waktu_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('waktu_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Konten -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-film mr-2 text-primary-600"></i>
                    Informasi Konten
                </h3>
                
                <!-- Jenis Konten -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Konten <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_konten" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Jenis Konten</option>
                        <option value="video" {{ old('jenis_konten') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="image" {{ old('jenis_konten') == 'image' ? 'selected' : '' }}>Gambar</option>
                        <option value="text" {{ old('jenis_konten') == 'text' ? 'selected' : '' }}>Teks</option>
                        <option value="live_feed" {{ old('jenis_konten') == 'live_feed' ? 'selected' : '' }}>Live Feed</option>
                    </select>
                    @error('jenis_konten')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Konten -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi_konten" rows="4" required
                        placeholder="Jelaskan secara detail konten yang akan ditampilkan..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('deskripsi_konten') }}</textarea>
                    @error('deskripsi_konten')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Konten -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Link Konten (Opsional)
                    </label>
                    <input type="url" name="link_konten" value="{{ old('link_konten') }}"
                        placeholder="https://example.com/konten"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('link_konten')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dokumen Pendukung -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                    Dokumen Pendukung
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Surat Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="surat_pengajuan" required
                        accept=".pdf,.doc,.docx"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Format: PDF, DOC, DOCX (Maks. 5MB)
                    </p>
                    @error('surat_pengajuan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('user.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" 
                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validasi tanggal dan waktu
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="video-trone"]');
    const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');
    const waktuMulai = document.querySelector('input[name="waktu_mulai"]');

    tanggalMulai?.addEventListener('change', function() {
        if (tanggalSelesai) {
            tanggalSelesai.min = this.value;
        }
    });

    form?.addEventListener('submit', function(e) {
        const todayObj = new Date();
        const year = todayObj.getFullYear();
        const month = String(todayObj.getMonth() + 1).padStart(2, '0');
        const day = String(todayObj.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;

        if (tanggalMulai && tanggalMulai.value === todayStr) {
            const currentHourMin = todayObj.toTimeString().split(' ')[0].substring(0, 5); // "HH:MM"
            if (waktuMulai && waktuMulai.value < currentHourMin) {
                e.preventDefault();
                alert('Waktu mulai tidak boleh kurang dari waktu sekarang.');
                waktuMulai.focus();
            }
        }
    });
});
</script>
@endsection