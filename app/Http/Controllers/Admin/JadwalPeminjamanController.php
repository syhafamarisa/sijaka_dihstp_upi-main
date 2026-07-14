<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use App\Models\PenyewaanVidotron;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalPeminjamanController extends Controller
{
    public function index()
    {
        try {
            // Ambil data dengan informasi pengusul
            $peminjamanRuangan = PeminjamanRuangan::with(['user', 'ruangan'])
                ->where('status', 'disetujui')
                ->orderBy('tanggal_mulai', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->limit(5)
                ->get();

            $peminjamanVidotron = PenyewaanVidotron::with(['user'])
                ->where('status', 'disetujui')
                ->orderBy('tanggal_mulai', 'desc')
                ->orderBy('waktu_mulai', 'desc')
                ->limit(5)
                ->get();

            // Gabungkan dan urutkan
            $recentBookings = $peminjamanRuangan->concat($peminjamanVidotron)
                ->sortByDesc(function($item) {
                    return $item->tanggal ?? $item->tanggal_mulai;
                })
                ->take(3); // Hanya 3 untuk preview

            // Stats
            $stats = [
                'total_ruangan' => PeminjamanRuangan::count(),
                'total_vidotron' => PenyewaanVidotron::count(),
                'disetujui_ruangan' => PeminjamanRuangan::where('status', 'disetujui')->count(),
                'disetujui_vidotron' => PenyewaanVidotron::where('status', 'disetujui')->count(),
            ];

            return view('admin.jadwal-peminjaman', compact('recentBookings', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Error in JadwalPeminjamanController: ' . $e->getMessage());
            return view('admin.jadwal-peminjaman', [
                'recentBookings' => collect(),
                'stats' => []
            ]);
        }
    }

    public function semuaData(Request $request)
    {
        try {
            // Query untuk ruangan
            $ruanganQuery = PeminjamanRuangan::with(['user', 'ruangan'])
                ->where('status', 'disetujui')
                ->orderBy('tanggal_mulai', 'desc')
                ->orderBy('jam_mulai', 'desc');

            // Query untuk vidotron
            $vidotronQuery = PenyewaanVidotron::with(['user'])
                ->where('status', 'disetujui')
                ->orderBy('tanggal_mulai', 'desc')
                ->orderBy('waktu_mulai', 'desc');

            // Filter bulan
            if ($request->has('bulan') && $request->bulan != '') {
                $bulan = $request->bulan;
                $ruanganQuery->whereYear('tanggal_mulai', substr($bulan, 0, 4))
                           ->whereMonth('tanggal_mulai', substr($bulan, 5, 2));
                $vidotronQuery->whereYear('tanggal_selesai', substr($bulan, 0, 4))
                            ->whereMonth('tanggal_selesai', substr($bulan, 5, 2));
            }

            // Filter jenis
            if ($request->has('jenis') && $request->jenis != '') {
                if ($request->jenis == 'ruangan') {
                    $vidotronQuery->whereRaw('1=0');
                } else {
                    $ruanganQuery->whereRaw('1=0');
                }
            }

            $peminjamanRuangan = $ruanganQuery->get();
            $peminjamanVidotron = $vidotronQuery->get();

            // Gabungkan semua data
            $allBookings = $peminjamanRuangan->concat($peminjamanVidotron)
                ->sortByDesc(function($item) {
                    return $item->tanggal ?? $item->tanggal_mulai;
                });

            // Stats untuk filter
            $totalData = $allBookings->count();
            $selectedBulan = $request->bulan;
            $selectedJenis = $request->jenis;

            // List bulan untuk dropdown filter
            $bulanList = $this->getBulanList();

            return view('admin.semua-jadwal', compact(
                'allBookings', 
                'totalData',
                'selectedBulan',
                'selectedJenis',
                'bulanList'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in semuaData: ' . $e->getMessage());
            return redirect()->route('admin.jadwal-peminjaman')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getBulanList()
    {
        $bulanList = [];
        
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $bulanList[$date->format('Y-m')] = $date->translatedFormat('F Y');
        }
        
        return $bulanList;
    }
}