<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanRuanganController;
use App\Http\Controllers\Admin\JadwalPeminjamanController;
use App\Http\Controllers\User\PenyewaanVidotronController;
use App\Http\Controllers\Admin\PeminjamanVidotronController;
use App\Http\Controllers\Pegawai\JadwalController;
use App\Http\Controllers\User\KegiatanController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\Admin\PenyewaanVidotronController as AdminPenyewaanVidotronController;
use App\Http\Controllers\Admin\PenyewaanVidotronExportController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTE PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTIKASI GOOGLE
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| DASHBOARD UMUM
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| ROUTE USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('peminjaman-ruangan')->name('peminjaman-ruangan.')->group(function () {
            Route::get('/', [PeminjamanRuanganController::class, 'create'])->name('create');
            Route::post('/', [PeminjamanRuanganController::class, 'store'])->name('store');
            Route::get('/riwayat', [PeminjamanRuanganController::class, 'indexUser'])->name('riwayat');
            Route::get('/{id}/detail', [PeminjamanRuanganController::class, 'getDetailUser'])->name('detail');
            Route::post('/{id}/cancel', [PeminjamanRuanganController::class, 'cancelUser'])->name('cancel');
            Route::get('/{id}/edit', [PeminjamanRuanganController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PeminjamanRuanganController::class, 'update'])->name('update');
            Route::delete('/{id}', [PeminjamanRuanganController::class, 'destroy'])->name('destroy');
        });

        Route::get('/peminjaman-video', function () {
            return redirect()->back()->with('error', 'Maaf, Videotron sedang dalam perbaikan.');
        })->name('peminjaman-video');

        Route::get('/lihat-jadwal', function () {
            return view('user.lihat-jadwal');
        })->name('lihat-jadwal');

        Route::prefix('penyewaan-vidotron')->name('penyewaan-vidotron.')->group(function () {
            Route::get('/', function () {
                return redirect()->back()->with('error', 'Maaf, Videotron sedang dalam perbaikan.');
            })->name('index');
            Route::get('/create', function () {
                return redirect()->back()->with('error', 'Maaf, Videotron sedang dalam perbaikan.');
            })->name('create');
            Route::post('/', [PenyewaanVidotronController::class, 'store'])->name('store');
            Route::get('/status', [PenyewaanVidotronController::class, 'status'])->name('status');
            Route::get('/riwayat', [PenyewaanVidotronController::class, 'riwayat'])->name('riwayat');
        });

        Route::get('/daftar-kegiatan', [KegiatanController::class, 'index'])->name('daftar-kegiatan');

        Route::get('/peminjaman-video-trone', [PenyewaanVidotronController::class, 'create'])
            ->name('peminjaman.video-trone');
        Route::post('/peminjaman-video-trone', [PenyewaanVidotronController::class, 'store'])
            ->name('peminjaman.video-trone.store');
    });

/*
|--------------------------------------------------------------------------
| ROUTE PEGAWAI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:pegawai'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {

        Route::get('/dashboard', [JadwalController::class, 'dashboardPegawai'])->name('dashboard');

        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/', [JadwalController::class, 'index'])->name('index');
            Route::get('/create', [JadwalController::class, 'create'])->name('create');
            Route::post('/', [JadwalController::class, 'store'])->name('store');
            Route::post('/check-availability', [JadwalController::class, 'checkAvailability'])->name('checkAvailability');
            Route::get('/semua', [JadwalController::class, 'semuaKegiatan'])->name('semua');
            Route::get('/{jadwal}', [JadwalController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [JadwalController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JadwalController::class, 'update'])->name('update');
            Route::delete('/{id}', [JadwalController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/data', [JadwalController::class, 'getJadwalData'])->name('data');
        });

        Route::get('/jadwal-staff', [JadwalController::class, 'index'])->name('jadwal-staff');
        Route::get('/buat-jadwal', [JadwalController::class, 'create'])->name('buat-jadwal');
        Route::get('/semua-kegiatan', [JadwalController::class, 'semuaKegiatan'])->name('semua-kegiatan');
    });

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'showProfile'])->name('show');
            Route::put('/', [AdminProfileController::class, 'updateProfile'])->name('update');
            Route::get('/change-password', [AdminProfileController::class, 'showChangePassword'])->name('change-password');
            Route::put('/change-password', [AdminProfileController::class, 'updatePassword'])->name('update-password');
        });

        Route::prefix('preferences')->name('preferences.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'showPreferences'])->name('show');
            Route::put('/', [AdminProfileController::class, 'updatePreferences'])->name('update');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'daftarUsers'])->name('index');
            Route::get('/{user}/data', [AdminController::class, 'getUserData'])->name('data');
            Route::post('/', [AdminController::class, 'storeUser'])->name('store');
            Route::put('/{user}', [AdminController::class, 'updateUserData'])->name('update');
            Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('destroy');
            Route::patch('/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('toggle-status');
        });

        Route::prefix('ruangan')->name('ruangan.')->group(function () {
            Route::get('/', [RuanganController::class, 'index'])->name('index');
            Route::get('/statistics', [RuanganController::class, 'getStatistics'])->name('statistics');
            Route::get('/{id}/detail', [RuanganController::class, 'show'])->name('detail');
            Route::post('/', [RuanganController::class, 'store'])->name('store');
            Route::put('/{id}', [RuanganController::class, 'update'])->name('update');
            Route::delete('/{id}', [RuanganController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('peminjaman-ruangan')->name('peminjaman-ruangan.')->group(function () {
            Route::get('/', [PeminjamanRuanganController::class, 'indexAdmin'])->name('index');
            Route::get('/{id}/detail', [PeminjamanRuanganController::class, 'detail'])->name('detail');
            Route::post('/{id}/approve', [PeminjamanRuanganController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [PeminjamanRuanganController::class, 'reject'])->name('reject');
            Route::post('/{id}/cancel', [PeminjamanRuanganController::class, 'cancel'])->name('cancel');
            Route::post('/update-status', [PeminjamanRuanganController::class, 'updateStatusRealTime'])->name('update-status');
            Route::get('/{id}/download-surat', [PeminjamanRuanganController::class, 'downloadSurat'])->name('download-surat');
        });

        Route::get('/jadwal-pegawai', [AdminController::class, 'jadwalPegawai'])->name('jadwal-pegawai');

        // ===== PEMINJAMAN VIDEO (DI-NONAKTIFKAN) =====
        Route::get('/peminjaman-video', function () {
            return redirect()->back()->with('error', 'Maaf, Videotron sedang dalam perbaikan.');
        })->name('peminjaman-video');

        // Grup Penyewaan Vidotron (tetap ada untuk route lain, tapi index di-nonaktifkan)
        Route::prefix('penyewaan-vidotron')->name('penyewaan-vidotron.')->group(function () {
            // Index dinonaktifkan
            Route::get('/', function () {
                return redirect()->back()->with('error', 'Maaf, Videotron sedang dalam perbaikan.');
            })->name('index');
            // Route lain tetap aktif (untuk detail, approve, reject, dll.) jika diperlukan
            Route::get('/{id}/detail', [AdminPenyewaanVidotronController::class, 'detail'])->name('detail');
            Route::post('/{id}/approve', [AdminPenyewaanVidotronController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [AdminPenyewaanVidotronController::class, 'reject'])->name('reject');
            Route::post('/{id}/cancel', [AdminPenyewaanVidotronController::class, 'cancel'])->name('cancel');
            Route::delete('/{id}', [AdminPenyewaanVidotronController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/download-surat', [AdminPenyewaanVidotronController::class, 'downloadSurat'])->name('download-surat');
            Route::get('/export', [PenyewaanVidotronExportController::class, 'exportCsv'])->name('export');
        });

        Route::get('/daftar-kegiatan', [AdminKegiatanController::class, 'index'])->name('daftar-kegiatan');

        Route::get('/semua-jadwal', [JadwalPeminjamanController::class, 'semuaData'])->name('semua-jadwal');
        Route::get('/jadwal-peminjaman', [JadwalPeminjamanController::class, 'index'])->name('jadwal-peminjaman');

        Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
    });

/*
|--------------------------------------------------------------------------
| ALIAS ROUTE UNTUK KOMPATIBILITAS (di blade admin/jadwal-peminjaman)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])
    ->get('/admin/penyewaan-vidotron', function () {
        return redirect()->back()->with('error', 'Maaf, Videotron sedang dalam perbaikan.');
    })->name('penyewaan-vidotron.index');

/*
|--------------------------------------------------------------------------
| ROUTE TEST
|--------------------------------------------------------------------------
*/
Route::get('/test-role', function () {
    $user = auth()->user();
    return response()->json([
        'user' => $user->name,
        'role' => $user->role,
        'is_admin' => $user->role === 'admin'
    ]);
})->middleware('auth');