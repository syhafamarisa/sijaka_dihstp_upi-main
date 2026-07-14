@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Profil Admin</h1>
        <p class="text-gray-600 mt-2">Kelola informasi profil Anda</p>
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

    <!-- Card Profil -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white text-2xl font-bold">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $admin->name }}</h2>
                <p class="text-gray-600">{{ $admin->email }}</p>
            </div>
        </div>
    </div>

    <!-- Form Edit Profil -->
    <form action="{{ route('admin.profile.update') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="no_telepon" class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
            <input type="tel" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $admin->no_telepon) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </form>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <a href="{{ route('admin.profile.change-password') }}" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition">
            <i class="fas fa-lock text-blue-600 text-2xl mb-2"></i>
            <h3 class="font-semibold text-gray-800">Ubah Password</h3>
            <p class="text-gray-600 text-sm">Perbarui password keamanan Anda</p>
        </a>
        <a href="{{ route('admin.preferences.show') }}" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition">
            <i class="fas fa-sliders-h text-green-600 text-2xl mb-2"></i>
            <h3 class="font-semibold text-gray-800">Preferensi Sistem</h3>
            <p class="text-gray-600 text-sm">Atur pengaturan sistem Anda</p>
        </a>
    </div>
</div>
@endsection
