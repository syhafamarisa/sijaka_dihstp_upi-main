@extends('layouts.home')

@section('title', 'SIJAKAPRANA - Sistem Peminjaman Ruangan & Pra-Sarana')

@section('content')
<style>
    .hero-section{
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.45), rgba(255, 255, 255, 0.45)),
        url('/img/upi.jpg');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
    
    .card-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
    }
    
    .logo-container {
        transition: all 0.3s ease;
    }
    
    .logo-container:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    .elegant-font {
        font-family: "Lato", sans-serif;
    }
</style>

<!-- Navigation -->
<nav class="gradient-bg text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <!-- Logo dengan Kotak Pembatas -->
                <div class="logo-container flex items-center justify-center w-16 h-16 mr-3 rounded-xl  overflow-hidden">
                    <img src="{{ asset('img/sijaka.png') }}" 
                         alt="Logo SI JAKA" 
                         class="w-full h-full object-contain p-2"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="w-full h-full flex items-center justify-center bg-primary-600" style="display: none;">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                </div>
                 <div class="flex flex-col leading-tight">
        <h1 class="text-2xl font-bold elegant-font">SIJAKAPRANA</h1>

        <p class="text-1xl font-semibold text-white mt-1">Sistem Jadwal Kantor & Pra-Sarana</p>
    </div>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#features" class="hover:text-primary-200 transition-colors">Fitur</a>
                <a href="#about" class="hover:text-primary-200 transition-colors">Tentang</a>
                <a href="#contact" class="hover:text-primary-200 transition-colors">Kontak</a>
                <a href="{{ route('login') }}" class="hover:text-primary-200 transition-colors">
    Login
</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section text-white py-20">
    <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
        <!-- Logo Hero dengan Kotak -->
        <div class="flex justify-center items-center mb-8">
            <div class="logo-container flex items-center justify-center w-48 h-48 rounded-2xl overflow-hidden p-4">
                <img src="{{ asset('img/sijaka.png') }}" 
                     alt="Logo SI JAKA" 
                     class="w-full h-full object-contain"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="w-full h-full flex items-center justify-center bg-primary-600 rounded-2xl" style="display: none;">
                    <i class="fas fa-calendar-alt text-white text-6xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-primary-800 mb-4"
    style="font-family:'Lato', sans-serif;">
    Fitur Unggulan
</h2>
            <p class="text-xl text-primary-600 max-w-3xl mx-auto">
                Platform terintegrasi untuk mengoptimalkan manajemen ruangan dan koordinasi tim
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Fitur 1 -->
            <div class="bg-white rounded-2xl card-shadow p-8 hover-lift border border-primary-100">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-6 mx-auto shadow-lg">
                    <i class="fas fa-door-open text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary-800 mb-4 text-center">Peminjaman Ruangan</h3>
                <p class="text-primary-600 text-center leading-relaxed">
                    Pinjam ruang rapat, digital corner, atau ruang conference dengan mudah dan cepat melalui sistem online terintegrasi
                </p>
            </div>
            
            <!-- Fitur 2 -->
            <div class="bg-white rounded-2xl card-shadow p-8 hover-lift border border-primary-100">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-6 mx-auto shadow-lg">
                    <i class="fas fa-video text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary-800 mb-4 text-center">Video Trone</h3>
                <p class="text-primary-600 text-center leading-relaxed">
                    Akses video panduan lengkap untuk penggunaan ruangan dan fasilitas yang tersedia
                </p>
            </div>
            
            <!-- Fitur 3 -->
            <div class="bg-white rounded-2xl card-shadow p-8 hover-lift border border-primary-100">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-6 mx-auto shadow-lg">
                    <i class="fas fa-users text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary-800 mb-4 text-center">Jadwal Pegawai</h3>
                <p class="text-primary-600 text-center leading-relaxed">
                    Pantau jadwal kehadiran dan ketersediaan pegawai untuk koordinasi yang lebih efektif
                </p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-3xl p-8 card-shadow">
                    <h2 class="text-4xl font-bold text-primary-800 elegant-font mb-6">Tentang SIJAKAPRANA</h2>
                    <div class="space-y-4">
                        <p class="text-primary-600 leading-relaxed text-lg">
                            SIJAKAPRANA adalah platform terintegrasi yang dirancang khusus untuk memudahkan proses peminjaman ruangan, 
                            akses video trone, dan manajemen jadwal pegawai dalam satu sistem yang efisien dan user-friendly.
                        </p>
                        <p class="text-primary-600 leading-relaxed text-lg">
                            Dengan antarmuka yang intuitif dan fitur yang lengkap, SIJAKAPRANA membantu organisasi 
                            mengoptimalkan penggunaan ruangan dan meningkatkan produktivitas tim secara signifikan.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-3xl p-8 text-white card-shadow">
                <h3 class="text-2xl font-bold mb-6 elegant-font">Keunggulan Platform</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-200 mr-4 mt-1 text-xl"></i>
                        <span class="text-primary-50 text-lg">Proses peminjaman ruangan yang cepat dan terintegrasi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-200 mr-4 mt-1 text-xl"></i>
                        <span class="text-primary-50 text-lg">Akses video trone yang lengkap dan informatif</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-200 mr-4 mt-1 text-xl"></i>
                        <span class="text-primary-50 text-lg">Manajemen jadwal pegawai yang terpusat dan real-time</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-200 mr-4 mt-1 text-xl"></i>
                        <span class="text-primary-50 text-lg">Notifikasi otomatis untuk semua aktivitas penting</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-200 mr-4 mt-1 text-xl"></i>
                        <span class="text-primary-50 text-lg">Laporan dan analitik yang komprehensif</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold elegant-font mb-6">Siap Mengoptimalkan Manajemen Ruangan?</h2>
        <p class="text-xl text-primary-200 mb-8 max-w-2xl mx-auto">
            Bergabung dengan ratusan organisasi yang telah menggunakan SI JAKAPRANA untuk efisiensi yang lebih baik
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/register" class="bg-white text-primary-700 hover:bg-primary-50 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover-lift flex items-center justify-center">
                <i class="fas fa-user-plus mr-3"></i> Daftar Sekarang
            </a>
            <a href="/login" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary-700 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover-lift flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-3"></i> Login ke Akun
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="contact" class="bg-primary-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-2">
                <div class="flex items-center mb-6">
                    <div class="logo-container flex items-center justify-center w-16 h-16 mr-4 rounded-xl overflow-hidden">
                        <img src="{{ asset('img/sijaka.png') }}" 
                             alt="Logo SI JAKA" 
                             class="w-full h-full object-contain p-2"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full flex items-center justify-center bg-primary-600" style="display: none;">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold elegant-font">SIJAKAPRANA</h3>
                        <p class="text-primary-300 text-sm">Sistem Terintegrasi</p>
                    </div>
                </div>
                <p class="text-primary-300 mb-6 max-w-md text-lg leading-relaxed">
                    Sistem Jadwal DIHTSP Terintegrasi dengan fitur peminjaman ruangan, video trone, dan manajemen jadwal pegawai untuk efisiensi maksimal.
                </p>
            </div>
            
            <div>
                <h4 class="text-xl font-semibold mb-6 elegant-font">Kontak Kami</h4>
                <ul class="space-y-4 text-primary-300">
                    <li class="flex items-start">
                        <i class="fas fa-envelope mr-4 mt-1"></i>
                        <span>dit.inovasipuu@upi.edu</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone mr-4 mt-1"></i>
                        <span>+62 851-1760-0477</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mr-4 mt-1"></i>
                        <span>Jl. Dr. Setiabudhi No. 229<br>Bandung 40154</span>
                    </li>
                    <li class="flex items-start">
    <i class="fab fa-instagram mr-4 mt-1"></i>
    <a href="https://www.instagram.com/inovasiupi?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="hover:text-primary-600 transition-colors">
        @inovasiupi
    </a>
</li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-xl font-semibold mb-6 elegant-font">Tautan Cepat</h4>
                <ul class="space-y-3 text-primary-300">
                    <li><a href="#features" class="hover:text-white transition-colors duration-300">Fitur</a></li>
                    <li><a href="#about" class="hover:text-white transition-colors duration-300">Tentang</a></li>
                    <li><a href="#contact" class="hover:text-white transition-colors duration-300">Kontak</a></li>
                    <li><a href="/login" class="hover:text-white transition-colors duration-300">Login</a></li>
                    <li><a href="/register" class="hover:text-white transition-colors duration-300">Daftar</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-primary-800 mt-12 pt-8 text-center">
            <p class="text-primary-400">&copy; 2025 SIJAKAPRANA - Sistem Peminjaman Ruangan Terintegrasi. Semua hak dilindungi.</p>
        </div>
    </div>
</footer>
@endsection