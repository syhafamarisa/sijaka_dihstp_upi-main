<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use App\Models\PenyewaanVidotron;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show user dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Get active bookings (peminjaman ruangan dengan status disetujui)
        $activeBookings = PeminjamanRuangan::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->count();

        // Get pending bookings
        $pendingBookings = PeminjamanRuangan::where('user_id', $userId)
            ->where('status', 'menunggu')
            ->count();

        // Get total bookings
        $totalBookings = PeminjamanRuangan::where('user_id', $userId)->count();

        // Get recent activities (gabungan peminjaman ruangan dan penyewaan vidotron)
        $recentActivities = collect();

        // Get recent peminjaman ruangan
        $peminjamanRecent = PeminjamanRuangan::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($peminjamanRecent as $item) {
            $item->type = 'ruangan';
            $recentActivities->push($item);
        }

        // Get recent penyewaan vidotron
        $penyewaanRecent = PenyewaanVidotron::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($penyewaanRecent as $item) {
            $item->type = 'vidotron';
            $recentActivities->push($item);
        }

        // Sort by created_at
        $recentActivities = $recentActivities->sortByDesc('created_at')->take(10);

        return view('user.dashboard', [
            'user' => $user,
            'activeBookings' => $activeBookings,
            'pendingBookings' => $pendingBookings,
            'totalBookings' => $totalBookings,
            'recentActivities' => $recentActivities,
        ]);
    }
}