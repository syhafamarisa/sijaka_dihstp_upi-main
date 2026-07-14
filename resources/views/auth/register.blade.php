@extends('layouts.home')

@section('title', 'Register')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl card-shadow p-8">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/sijaka.png') }}" 
                     alt="Logo RuangIn" 
                     class="logo-img w-full h-full"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <!-- Fallback jika gambar error -->
                <div class="w-full h-full flex items-center justify-center" style="display: none;">
                    <i class="fas fa-door-open text-primary-600 text-3xl"></i>
                </div>
            </div>
            <p class="text-primary-600">Daftar Akun Baru</p>
        </div>
        
        <form method="POST" action="/register">
            @csrf
            
            <div class="mb-5">
                <label for="name" class="block text-primary-700 font-semibold mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-3 border border-primary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan nama lengkap Anda"
                       required>
                @error('name')
                    <p class="text-primary-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block text-primary-700 font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-3 border border-primary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan email Anda"
                       required>
                @error('email')
                    <p class="text-primary-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="no_telepon" class="block text-primary-700 font-semibold mb-2">No. Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" 
                       class="w-full px-4 py-3 border border-primary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan nomor telepon">
                @error('no_telepon')
                    <p class="text-primary-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-primary-700 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-3 border border-primary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                       placeholder="Buat password Anda"
                       required>
                @error('password')
                    <p class="text-primary-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-primary-700 font-semibold mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="w-full px-4 py-3 border border-primary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                       placeholder="Konfirmasi password Anda"
                       required>
            </div>

            <button type="submit" class="w-full gradient-bg hover:opacity-90 text-white py-3 px-4 rounded-xl font-semibold text-lg transition-all duration-300 hover-lift flex items-center justify-center">
                <i class="fas fa-user-plus mr-3"></i> Daftar Akun Baru
            </button>
        </form>

        <div class="text-center mt-6 pt-6 border-t border-primary-200">
            <p class="text-primary-600">
                Sudah punya akun? 
                <a href="/login" class="text-primary-600 hover:text-primary-800 font-semibold transition-colors duration-300">
                    Login di sini
                </a>
            </p>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 p-4 bg-primary-50 rounded-xl">
            <h4 class="text-primary-700 font-semibold mb-2 flex items-center">
                <i class="fas fa-shield-alt mr-2"></i> Keamanan Akun
            </h4>
            <ul class="text-primary-600 text-sm space-y-1">
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-primary-500 mr-2 text-xs"></i>
                    Password minimal 6 karakter
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-primary-500 mr-2 text-xs"></i>
                    Gunakan kombinasi huruf dan angka
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-primary-500 mr-2 text-xs"></i>
                    Jangan bagikan password kepada siapapun
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .elegant-font {
        font-family: 'Playfair Display', serif;
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #7f1d1d 0%, #b91c1c 50%, #dc2626 100%);
    }
    
    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(127, 29, 29, 0.2);
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(127, 29, 29, 0.3);
    }
    
    .logo-img {
        object-fit: contain;
    }
</style>
@endsection