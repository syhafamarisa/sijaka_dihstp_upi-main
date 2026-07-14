<!DOCTYPE html>
<html lang="id" class="{{ session('admin_preferences.theme', 'light') == 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - SIJAKAPRANA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html, body {
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif !important;
        }
        /* Sidebar lebih rapi */
        #sidebar {
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .elegant-font {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .logo-container {
            transition: all 0.3s ease;
        }
        .logo-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        /* Nav item hover & active */
        .nav-item {
            transition: all 0.2s ease;
            border-radius: 0.5rem;
        }
        .nav-item:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .nav-item.active {
            background-color: rgba(255,255,255,0.2);
            box-shadow: inset 4px 0 0 #fff;
        }
        /* Responsive table */
        @media (max-width: 640px) {
            .responsive-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
        /* Dark mode overrides */
        html.dark body {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        html.dark header {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark header h2,
        html.dark header button,
        html.dark header i {
            color: #f1f5f9 !important;
        }
        html.dark #notificationDropdown,
        html.dark #settingsDropdown {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark #notificationDropdown *,
        html.dark #settingsDropdown * {
            color: #f1f5f9 !important;
            border-color: #334155 !important;
        }
        html.dark #settingsDropdown a:hover,
        html.dark #settingsDropdown button:hover {
            background-color: #334155 !important;
        }
        html.dark .bg-white {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border-color: #334155 !important;
        }
        html.dark .text-gray-800 { color: #f1f5f9 !important; }
        html.dark .text-gray-700 { color: #cbd5e1 !important; }
        html.dark .text-gray-600 { color: #94a3b8 !important; }
        html.dark .text-gray-500 { color: #64748b !important; }
        html.dark .text-primary-900 { color: #f1f5f9 !important; }
        html.dark .border-gray-200 { border-color: #334155 !important; }
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #334155 !important;
            color: #fff !important;
            border-color: #475569 !important;
        }
        html.dark input:focus,
        html.dark select:focus,
        html.dark textarea:focus {
            border-color: #3b82f6 !important;
        }
        html.dark td { color: #e2e8f0 !important; border-color: #334155 !important; }
        html.dark th { background-color: #334155 !important; color: #f1f5f9 !important; }
        html.dark .bg-primary-50 { background-color: rgba(220, 38, 38, 0.1) !important; color: #fee2e2 !important; }
        html.dark .bg-green-50 { background-color: rgba(22, 163, 74, 0.1) !important; color: #dcfce7 !important; }
        html.dark .bg-purple-50 { background-color: rgba(147, 51, 234, 0.1) !important; color: #f3e8ff !important; }
        html.dark .bg-blue-50 { background-color: rgba(37, 99, 235, 0.1) !important; color: #dbeafe !important; }
        html.dark .bg-yellow-50 { background-color: rgba(202, 138, 4, 0.1) !important; color: #fef9c3 !important; }
        html.dark .text-primary-800 { color: #fca5a5 !important; }
        html.dark .text-green-800 { color: #86efac !important; }
        html.dark .text-purple-800 { color: #d8b4fe !important; }
        html.dark .text-blue-800 { color: #93c5fd !important; }
        html.dark .border-primary-200 { border-color: rgba(220, 38, 38, 0.3) !important; }
        html.dark .border-green-200 { border-color: rgba(22, 163, 74, 0.3) !important; }
        html.dark .border-purple-200 { border-color: rgba(147, 51, 234, 0.3) !important; }
        html.dark .border-blue-200 { border-color: rgba(37, 99, 235, 0.3) !important; }
        html.dark .bg-primary-100 { background-color: rgba(220, 38, 38, 0.2) !important; }
        html.dark .bg-green-100 { background-color: rgba(22, 163, 74, 0.2) !important; }
        html.dark .bg-purple-100 { background-color: rgba(147, 51, 234, 0.2) !important; }
        html.dark .bg-blue-100 { background-color: rgba(37, 99, 235, 0.2) !important; }
        html.dark .text-primary-600 { color: #f87171 !important; }
        html.dark .text-green-600 { color: #4ade80 !important; }
        html.dark .text-purple-600 { color: #c084fc !important; }
        html.dark .text-blue-600 { color: #60a5fa !important; }
        html.dark .md\:hidden.bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark .md\:hidden.bg-white a {
            color: #cbd5e1 !important;
        }
        html.dark .md\:hidden.bg-white a.text-primary-600 {
            color: #f87171 !important;
        }
    </style>

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
    <div class="flex h-screen">
        <!-- =============== SIDEBAR DESKTOP =============== -->
        <div id="sidebar" class="hidden md:flex w-64 bg-primary-700 text-white shadow flex-col flex-shrink-0">
            <!-- Brand -->
            <div class="p-5 border-b border-primary-600 flex items-center space-x-3">
                <div class="logo-container flex items-center justify-center w-12 h-12 rounded-lg overflow-hidden bg-white/10">
                    <img src="{{ asset('img/sijaka.png') }}" 
                         alt="Logo" 
                         class="logo-img w-10 h-10 object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="w-10 h-10 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-calendar-alt text-primary-600 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-xl font-bold elegant-font leading-tight">SIJAKAPRANA</h1>
                    <p class="text-xs text-primary-200">Admin Panel</p>
                </div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto">
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Manajemen Akun -->
                    <li>
                        <a href="{{ route('admin.users.index') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog w-5 text-center"></i>
                            <span>Manajemen Akun</span>
                        </a>
                    </li>

                    <!-- Manajemen Ruangan -->
                    <li>
                        <a href="{{ route('admin.ruangan.index') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                            <i class="fas fa-building w-5 text-center"></i>
                            <span>Manajemen Ruangan</span>
                        </a>
                    </li>

                    <!-- Jadwal Kegiatan -->
                    <li>
                        <a href="{{ route('admin.jadwal.index') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-plus w-5 text-center"></i>
                            <span>Jadwal Kegiatan</span>
                        </a>
                    </li>

                    <!-- Jadwal Pegawai -->
                    <li>
                        <a href="{{ route('admin.jadwal-pegawai') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal-pegawai') ? 'active' : '' }}">
                            <i class="fas fa-user-clock w-5 text-center"></i>
                            <span>Jadwal Pegawai</span>
                        </a>
                    </li>

                    <!-- Jadwal Peminjaman -->
                    <li>
                        <a href="{{ route('admin.jadwal-peminjaman') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal-peminjaman') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check w-5 text-center"></i>
                            <span>Jadwal Peminjaman</span>
                        </a>
                    </li>

                    <!-- Peminjaman Ruangan -->
                    <li>
                        <a href="{{ route('admin.peminjaman-ruangan.index') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.peminjaman-ruangan.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open w-5 text-center"></i>
                            <span>Peminjaman Ruangan</span>
                        </a>
                    </li>

                    <!-- Peminjaman Video -->
                    <li>
                        <a href="{{ route('admin.peminjaman-video') }}" 
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.peminjaman-video') ? 'active' : '' }}">
                            <i class="fas fa-video w-5 text-center"></i>
                            <span>Peminjaman Video</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Profil & Logout -->
            <div class="border-t border-primary-600 p-4 bg-primary-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-primary-200 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-primary-600 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- =============== SIDEBAR MOBILE =============== -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
        <div id="mobileSidebar" class="fixed inset-y-0 left-0 w-64 bg-primary-700 text-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden flex flex-col">
            <!-- Brand -->
            <div class="p-5 border-b border-primary-600 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="logo-container flex items-center justify-center w-12 h-12 rounded-lg overflow-hidden bg-white/10">
                        <img src="{{ asset('img/sijaka.png') }}" 
                             alt="Logo" 
                             class="logo-img w-10 h-10 object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-10 h-10 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-calendar-alt text-primary-600 text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold elegant-font leading-tight">SIJAKAPRANA</h1>
                        <p class="text-xs text-primary-200">Admin Panel</p>
                    </div>
                </div>
                <button id="closeSidebar" class="text-white hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-tachometer-alt w-5 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-users-cog w-5 text-center"></i>
                            <span>Manajemen Akun</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ruangan.index') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-building w-5 text-center"></i>
                            <span>Manajemen Ruangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jadwal.index') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-calendar-plus w-5 text-center"></i>
                            <span>Jadwal Kegiatan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jadwal-pegawai') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal-pegawai') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-user-clock w-5 text-center"></i>
                            <span>Jadwal Pegawai</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jadwal-peminjaman') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.jadwal-peminjaman') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-calendar-check w-5 text-center"></i>
                            <span>Jadwal Peminjaman</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.peminjaman-ruangan.index') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.peminjaman-ruangan.*') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-door-open w-5 text-center"></i>
                            <span>Peminjaman Ruangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.peminjaman-video') }}" class="nav-item flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('admin.peminjaman-video') ? 'active' : '' }}" onclick="closeMobileSidebar()">
                            <i class="fas fa-video w-5 text-center"></i>
                            <span>Peminjaman Video</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Profil & Logout Mobile -->
            <div class="border-t border-primary-600 p-4 bg-primary-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-primary-200 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-primary-600 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- =============== KONTEN UTAMA =============== -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="flex justify-between items-center px-4 md:px-6 py-3">
                    <div class="flex items-center space-x-4">
                        <button id="openSidebar" class="md:hidden text-gray-600 hover:text-primary-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-lg md:text-2xl font-bold text-primary-900 elegant-font">
                            @yield('page-title', 'Admin Dashboard')
                        </h2>
                    </div>
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <!-- Notifikasi -->
                        <div class="relative">
                            <button id="notificationBtn" class="p-2 text-gray-600 hover:text-primary-600 relative transition-colors">
                                <i class="fas fa-bell"></i>
                                @if(isset($completedNotifications) && count($completedNotifications) > 0)
                                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                @endif
                            </button>
                            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                                </div>
                                <div class="p-4">
                                    @if(isset($completedNotifications) && count($completedNotifications) > 0)
                                        <div class="space-y-3">
                                            @foreach($completedNotifications as $notif)
                                                <div class="p-2 border-b border-gray-100 hover:bg-gray-50 rounded text-xs">
                                                    <p class="font-semibold text-primary-800">{{ $notif['title'] }}</p>
                                                    <p class="text-gray-600 mt-0.5">{{ $notif['message'] }}</p>
                                                    <p class="text-gray-400 text-[10px] mt-1">{{ $notif['time']->diffForHumans() }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-center py-4">Tidak ada notifikasi baru</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="relative">
                            <button id="settingsBtn" class="p-2 text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div id="settingsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">Pengaturan</h3>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('admin.profile.show') }}" class="block px-4 py-2 bg-primary-50 text-primary-900 hover:bg-primary-100 transition-colors rounded">
                                        <i class="fas fa-user-circle mr-2 text-primary-600"></i>Profil Admin
                                    </a>
                                    <a href="{{ route('admin.profile.change-password') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-lock mr-2 text-primary-600"></i>Ubah Password
                                    </a>
                                    <a href="{{ route('admin.preferences.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sliders-h mr-2 text-primary-600"></i>Preferensi Sistem
                                    </a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-primary-900 hover:bg-primary-100 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-2 text-primary-600"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- User avatar mobile -->
                        <div class="md:hidden flex items-center">
                            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>

            <!-- Bottom Navigation Mobile -->
            <div class="md:hidden bg-white border-t border-gray-200 py-2 px-4 flex-shrink-0">
                <div class="flex justify-around items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center {{ request()->routeIs('admin.dashboard') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                        <i class="fas fa-tachometer-alt mb-1"></i>
                        <span class="text-xs">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center {{ request()->routeIs('admin.users.*') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                        <i class="fas fa-users-cog mb-1"></i>
                        <span class="text-xs">Akun</span>
                    </a>
                    <a href="{{ route('admin.peminjaman-ruangan.index') }}" class="flex flex-col items-center {{ request()->routeIs('admin.peminjaman-ruangan.*') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                        <i class="fas fa-door-open mb-1"></i>
                        <span class="text-xs">Ruangan</span>
                    </a>
                    <a href="{{ route('admin.peminjaman-video') }}" class="flex flex-col items-center {{ request()->routeIs('admin.peminjaman-video') ? 'text-primary-600 font-semibold' : 'text-gray-600' }}">
                        <i class="fas fa-video mb-1"></i>
                        <span class="text-xs">Video</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- =============== JAVASCRIPT =============== -->
    <script>
        // Mobile sidebar toggle
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openMobileSidebar() {
            mobileSidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            mobileSidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        if (openSidebarBtn) {
            openSidebarBtn.addEventListener('click', openMobileSidebar);
        }
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeMobileSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeMobileSidebar);
        }

        // Tutup sidebar mobile saat klik link di dalamnya
        document.querySelectorAll('#mobileSidebar a').forEach(link => {
            link.addEventListener('click', closeMobileSidebar);
        });

        // Responsive: tutup saat layar >= 768px
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeMobileSidebar();
            }
        });

        // Tutup dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });

        // Dropdown notifikasi & pengaturan
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsDropdown = document.getElementById('settingsDropdown');

        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
                settingsDropdown.classList.add('hidden');
            });
        }

        if (settingsBtn && settingsDropdown) {
            settingsBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                settingsDropdown.classList.toggle('hidden');
                notificationDropdown.classList.add('hidden');
            });
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(e) {
            if (notificationBtn && notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
            if (settingsBtn && settingsDropdown && !settingsBtn.contains(e.target) && !settingsDropdown.contains(e.target)) {
                settingsDropdown.classList.add('hidden');
            }
        });

        // Tutup dropdown dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (notificationDropdown) notificationDropdown.classList.add('hidden');
                if (settingsDropdown) settingsDropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>