@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Preferensi Sistem</h1>
        <p class="text-gray-600 mt-2">Atur preferensi dan pengaturan sistem Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <h3 class="font-bold mb-2">Terjadi kesalahan:</h3>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Preferensi -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.preferences.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tema -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-palette mr-2 text-blue-600"></i>Tema Interface
                </h3>

                <div class="space-y-3">
                    <label class="flex items-start cursor-pointer">
                        <input type="radio" name="theme" value="light" 
                            {{ session('admin_preferences.theme', 'light') == 'light' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 mt-1">
                        <span class="ml-3">
                            <span class="font-medium text-gray-700">Terang (Light)</span>
                            <p class="text-sm text-gray-600">Interface dengan latar belakang terang</p>
                        </span>
                    </label>

                    <label class="flex items-start cursor-pointer">
                        <input type="radio" name="theme" value="dark" 
                            {{ session('admin_preferences.theme', 'light') == 'dark' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 mt-1">
                        <span class="ml-3">
                            <span class="font-medium text-gray-700">Gelap (Dark)</span>
                            <p class="text-sm text-gray-600">Interface dengan latar belakang gelap</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Zona Waktu -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-clock mr-2 text-green-600"></i>Zona Waktu
                </h3>

                <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Asia/Jakarta" {{ session('admin_preferences.timezone', 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB) UTC+7</option>
                    <option value="Asia/Bangkok" {{ session('admin_preferences.timezone', 'Asia/Jakarta') == 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (ICT) UTC+7</option>
                    <option value="Asia/Manila" {{ session('admin_preferences.timezone', 'Asia/Jakarta') == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (PHT) UTC+8</option>
                    <option value="Asia/Singapore" {{ session('admin_preferences.timezone', 'Asia/Jakarta') == 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore (SGT) UTC+8</option>
                    <option value="Asia/Kolkata" {{ session('admin_preferences.timezone', 'Asia/Jakarta') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST) UTC+5:30</option>
                    <option value="UTC" {{ session('admin_preferences.timezone', 'Asia/Jakarta') == 'UTC' ? 'selected' : '' }}>UTC (Koordinat Universal)</option>
                </select>
            </div>

            <!-- Notifikasi -->
            <div class="mb-6 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-bell mr-2 text-yellow-600"></i>Notifikasi
                </h3>

                <div class="space-y-3">
                    <label class="flex items-start cursor-pointer p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <input type="radio" name="notifications" value="on" 
                            {{ session('admin_preferences.notifications', 'on') == 'on' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 mt-1">
                        <span class="ml-3">
                            <span class="font-medium text-gray-700">Aktifkan Notifikasi</span>
                            <p class="text-sm text-gray-600">Terima notifikasi untuk peminjaman, penyewaan, dan aktivitas lainnya</p>
                        </span>
                    </label>

                    <label class="flex items-start cursor-pointer p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <input type="radio" name="notifications" value="off" 
                            {{ session('admin_preferences.notifications', 'on') == 'off' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 mt-1">
                        <span class="ml-3">
                            <span class="font-medium text-gray-700">Nonaktifkan Notifikasi</span>
                            <p class="text-sm text-gray-600">Tidak akan menerima notifikasi sistem</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Simpan Preferensi
                </button>
                <a href="{{ route('admin.profile.show') }}" class="w-full sm:w-auto px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="reset" class="w-full sm:w-auto px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center justify-center">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Info Tambahan -->
    <div class="bg-blue-50 rounded-lg p-6 mt-6">
        <h3 class="font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi
        </h3>
        <p class="text-blue-800 text-sm">
            Pengaturan ini akan mempengaruhi pengalaman Anda menggunakan sistem SIJAKAPRANA. 
            Perubahan akan diterapkan segera setelah Anda menyimpan.
        </p>
    </div>
</div>
@endsection
