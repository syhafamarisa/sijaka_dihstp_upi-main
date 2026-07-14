<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SIJAKAPRANA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Layout untuk User/Pegawai (navbar atas) -->
    <nav class="bg-primary-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo dan Brand -->
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu untuk Mobile -->
                    <button id="mobileMenuButton" class="lg:hidden text-white hover:text-primary-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="flex items-center space-x-3">
                        <div class="logo-container flex items-center justify-center w-10 h-10 rounded-md overflow-hidden">
                            <img src="{{ asset('img/sijaka.png') }}" 
                                 alt="Logo SIPRUVIS" 
                                 class="logo-img w-8 h-8 object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="w-8 h-8 flex items-center justify-center" style="display: none;">
                                <i class="fas fa-calendar-alt text-primary-600 text-lg"></i>
                            </div>
                        </div>
                        <h1 class="text-xl font-bold elegant-font hidden sm:block">SIJAKAPRANA</h1>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-6">
                    @if(auth()->user()->isPegawai())
                        <!-- Menu untuk Pegawai -->
                        <a href="{{ route('pegawai.dashboard') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('pegawai.dashboard') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-home mr-1"></i> Home
                        </a>
                        <a href="{{ route('pegawai.buat-jadwal') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('pegawai.input-jadwal') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-plus-circle mr-1"></i> Input Jadwal
                        </a>
                        <a href="{{ route('pegawai.jadwal-staff') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('pegawai.lihat-jadwal') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-calendar-alt mr-1"></i> Lihat Jadwal
                        </a>
                    @else
                        <!-- Menu untuk User Biasa -->
                        <a href="{{ route('user.dashboard') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-home mr-1"></i> Home
                        </a>
                        <a href="{{ route('user.peminjaman-ruangan.create') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('user.peminjaman-ruangan.create') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-door-open mr-1"></i> Peminjaman Ruangan
                        </a>

                        <a href="{{ route('user.peminjaman-video') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('user.peminjaman-video') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-video mr-1"></i> Video Trone
                        </a>
                        <a href="{{ route('user.peminjaman-ruangan.riwayat') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('user.peminjaman-ruangan.riwayat') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-history mr-1"></i> Riwayat
                        </a>

                        <a href="{{ route('user.lihat-jadwal') }}" class="hover:bg-primary-700 px-3 py-2 rounded transition-colors {{ request()->routeIs('user.lihat-jadwal') ? 'bg-primary-700' : '' }}">
                            <i class="fas fa-calendar-check mr-1"></i> Lihat Jadwal
                        </a>
                    @endif
                </div>

                <!-- User Info dan Logout -->
                <div class="flex items-center space-x-4">
                    <span class="text-primary-100 hidden md:block">Halo, {{ auth()->user()->name }}</span>
                    <div class="relative">
                        <button id="userDropdownBtn" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <span class="md:hidden text-primary-100">{{ auth()->user()->name }}</span>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="fixed inset-y-0 left-0 w-64 bg-primary-700 text-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out z-50 lg:hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="logo-container flex items-center justify-center w-10 h-10 rounded-md overflow-hidden">
                            <img src="{{ asset('img/sijaka.png') }}" 
                                 alt="Logo SIPRUVIS" 
                                 class="logo-img w-8 h-8 object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="w-8 h-8 flex items-center justify-center" style="display: none;">
                                <i class="fas fa-calendar-alt text-primary-600 text-lg"></i>
                            </div>
                        </div>
                        <h1 class="text-xl font-bold elegant-font">SIJAKAPRANA</h1>
                    </div>
                    <button id="closeMobileMenu" class="text-white hover:text-primary-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- User Info Mobile -->
                <div class="mb-6 p-3 bg-primary-600 rounded-lg">
                    <p class="font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-primary-200">{{ auth()->user()->email }}</p>
                </div>

                <!-- Mobile Navigation -->
                <div class="space-y-2">
                    @if(auth()->user()->isPegawai())
                        <!-- Menu untuk Pegawai -->
                        <a href="{{ route('pegawai.dashboard') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('pegawai.dashboard') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-home w-5"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('pegawai.buat-jadwal') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('pegawai.input-jadwal') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Input Jadwal</span>
                        </a>
                        <a href="{{ route('pegawai.jadwal-staff') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('pegawai.lihat-jadwal') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span>Lihat Jadwal</span>
                        </a>
                    @else
                        <!-- Menu untuk User Biasa -->
                        <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-home w-5"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('user.peminjaman-ruangan.create') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('user.peminjaman-ruangan.create') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-door-open w-5"></i>
                            <span>Peminjaman Ruangan</span>
                        </a>
                        <a href="{{ route('user.peminjaman-video') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('user.peminjaman-video') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-video w-5"></i>
                            <span>Video Trone</span>
                        </a>
                        <a href="{{ route('user.peminjaman-ruangan.riwayat') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('user.peminjaman-ruangan.riwayat') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-history w-5"></i>
                            <span>Riwayat</span>
                        </a>
                        <a href="{{ route('user.lihat-jadwal') }}" class="flex items-center space-x-3 p-3 hover:bg-primary-600 rounded-lg transition-colors {{ request()->routeIs('user.lihat-jadwal') ? 'bg-primary-600' : '' }}" onclick="closeMobileMenu()">
                            <i class="fas fa-calendar-check w-5"></i>
                            <span>Lihat Jadwal</span>
                        </a>
                    @endif
                </div>

                <!-- Logout Button Mobile -->
                <div class="mt-8 pt-4 border-t border-primary-600">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded transition-colors" onclick="closeMobileMenu()">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6 px-4 md:py-8 md:px-6">
        @yield('content')
    </main>

    <!-- Bottom Navigation untuk Mobile (Small Screens) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-2 px-4 z-30">
        <div class="flex justify-between items-center">
            @if(auth()->user()->isPegawai())
                <!-- Bottom Nav untuk Pegawai -->
                <a href="{{ route('pegawai.dashboard') }}" class="flex flex-col items-center text-center {{ request()->routeIs('pegawai.dashboard') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-home mb-1"></i>
                    <span class="text-xs">Home</span>
                </a>
                <a href="{{ route('pegawai.buat-jadwal') }}" class="flex flex-col items-center text-center {{ request()->routeIs('pegawai.input-jadwal') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-plus-circle mb-1"></i>
                    <span class="text-xs">Input</span>
                </a>
                <a href="{{ route('pegawai.jadwal-staff') }}" class="flex flex-col items-center text-center {{ request()->routeIs('pegawai.lihat-jadwal') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-calendar-alt mb-1"></i>
                    <span class="text-xs">Jadwal</span>
                </a>
                <button id="openMobileMenu" class="flex flex-col items-center text-center text-gray-600">
                    <i class="fas fa-bars mb-1"></i>
                    <span class="text-xs">Menu</span>
                </button>
            @else
                <!-- Bottom Nav untuk User Biasa -->
                <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center text-center {{ request()->routeIs('user.dashboard') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-home mb-1"></i>
                    <span class="text-xs">Home</span>
                </a>
                <a href="{{ route('user.peminjaman-ruangan.create') }}" class="flex flex-col items-center text-center {{ request()->routeIs('user.peminjaman-ruangan.create') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-door-open mb-1"></i>
                    <span class="text-xs">Ruangan</span>
                </a>

                <a href="{{ route('user.peminjaman-video') }}" class="flex flex-col items-center text-center {{ request()->routeIs('user.peminjaman-video') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-video mb-1"></i>
                    <span class="text-xs">Video</span>
                </a>
                <a href="{{ route('user.peminjaman-ruangan.riwayat') }}" class="flex flex-col items-center text-center {{ request()->routeIs('user.peminjaman-ruangan.riwayat') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-history mb-1"></i>
                    <span class="text-xs">Riwayat</span>
                </a>

            @endif
        </div>
    </div>

    <style>
        .elegant-font{
            font-family: 'Oswald', sans-serif !important;
            font-weight: 600;
            letter-spacing: .5px;
        }
        
        .logo-container {
            transition: all 0.3s ease;
        }
        
        .logo-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Responsive table styles */
        @media (max-width: 640px) {
            .responsive-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
            
            .card-responsive {
                margin-bottom: 1rem;
                padding: 1rem;
            }
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>

    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const openMobileMenuBtn = document.getElementById('openMobileMenu');
        const closeMobileMenuBtn = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        function openMobileMenu() {
            mobileMenu.classList.remove('-translate-x-full');
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.add('-translate-x-full');
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Event Listeners
        mobileMenuButton?.addEventListener('click', openMobileMenu);
        openMobileMenuBtn?.addEventListener('click', openMobileMenu);
        closeMobileMenuBtn?.addEventListener('click', closeMobileMenu);
        mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

        // Close menu when clicking links
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

        // Close menu on window resize (if resized to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                closeMobileMenu();
            }
        });

        // User dropdown functionality
        const userDropdownBtn = document.getElementById('userDropdownBtn');
        const userDropdown = document.getElementById('userDropdown');

        userDropdownBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown?.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (userDropdown && !userDropdown.classList.contains('hidden')) {
                if (!userDropdownBtn?.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            }
        });

        // Close dropdown with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userDropdown?.classList.add('hidden');
            }
        });
    </script>

    @if(session('error'))
<script>
    alert("{{ session('error') }}");
</script>
@endif
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>