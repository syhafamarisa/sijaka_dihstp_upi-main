@extends('layouts.home')

@section('title', 'Login')

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
            <p class="text-primary-600">Login ke Sistem</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-6">
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

            <div class="mb-6">
                <label for="password" class="block text-primary-700 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-3 border border-primary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan password Anda"
                       required>
                @error('password')
                    <p class="text-primary-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="w-full gradient-bg hover:opacity-90 text-white py-3 px-4 rounded-xl font-semibold text-lg transition-all duration-300 hover-lift flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-3"></i> Login ke Akun
            </button>
        </form>

        <div class="text-center mt-6 pt-6 border-t border-primary-200">
            <p class="text-primary-600">
                Belum punya akun? 
                <a href="/register" class="text-primary-600 hover:text-primary-800 font-semibold transition-colors duration-300">
                    Daftar Akun Baru
                </a>
            </p>
        </div>

        <div class="mt-4">
    <a href="{{ route('google.login') }}"
       class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg py-3 hover:bg-gray-100 transition">

        <img
            src="{{ asset('img/google.png') }}"
            width="20"
            alt="Google">



        <span>Masuk dengan Google</span>

    </a>
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