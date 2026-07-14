<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PeminjamanRuangan;
use App\Models\PenyewaanVidotron;
use Carbon\Carbon;

class DashboardController extends Controller

{
    public function index()
    {
        try {
            // Update status untuk peminjaman ruangan yang sudah selesai
            $today = Carbon::today()->format('Y-m-d');
            $nowTime = Carbon::now()->format('H:i:s');

            PeminjamanRuangan::where('status', 'disetujui')
                ->where(function($query) use ($today, $nowTime) {
                    $query->where('tanggal_selesai', '<', $today)
                          ->orWhere(function($q) use ($today, $nowTime) {
                              $q->where('tanggal_selesai', '=', $today)
                                ->where('jam_selesai', '<', $nowTime);
                          });
                })
                ->update(['status' => 'selesai']);

            // Update status untuk penyewaan videotron yang sudah selesai
            PenyewaanVidotron::where('status', 'disetujui')
                ->where(function($query) use ($today, $nowTime) {
                    $query->where('tanggal_selesai', '<', $today)
                          ->orWhere(function($q) use ($today, $nowTime) {
                              $q->where('tanggal_selesai', '=', $today)
                                ->where('waktu_selesai', '<', $nowTime);
                          });
                })
                ->update(['status' => 'selesai']);

            // Query peminjaman selesai baru-baru ini untuk notifikasi
            $recentCompletedRuangan = PeminjamanRuangan::with(['ruangan', 'user'])
                ->where('status', 'selesai')
                ->where('tanggal_selesai', '>=', Carbon::today()->subDays(2))
                ->orderBy('tanggal_selesai', 'desc')
                ->orderBy('jam_selesai', 'desc')
                ->get();

            $recentCompletedVidotron = PenyewaanVidotron::with('user')
                ->where('status', 'selesai')
                ->where('tanggal_selesai', '>=', Carbon::today()->subDays(2))
                ->orderBy('tanggal_selesai', 'desc')
                ->orderBy('waktu_selesai', 'desc')
                ->get();

            $completedNotifications = collect();

            foreach ($recentCompletedRuangan as $r) {
                $completedNotifications->push([
                    'id' => 'ruangan_' . $r->id,
                    'title' => 'Peminjaman Ruangan Selesai',
                    'message' => 'Peminjaman ' . ($r->ruangan->nama_ruangan ?? 'Ruangan') . ' untuk acara "' . $r->acara . '" oleh ' . ($r->nama_pengusul ?? $r->user->name) . ' telah selesai.',
                    'time' => Carbon::parse($r->tanggal_selesai->format('Y-m-d') . ' ' . $r->jam_selesai)
                ]);
            }

            foreach ($recentCompletedVidotron as $v) {
                $completedNotifications->push([
                    'id' => 'vidotron_' . $v->id,
                    'title' => 'Penyewaan Videotron Selesai',
                    'message' => 'Penyewaan Videotron untuk kegiatan "' . $v->tujuan_pemasangan . '" oleh ' . ($v->nama_pengusul ?? $v->user->name) . ' telah selesai.',
                    'time' => Carbon::parse($v->tanggal_selesai->format('Y-m-d') . ' ' . $v->waktu_selesai)
                ]);
            }

            $completedNotifications = $completedNotifications->sortByDesc('time')->take(5);
            view()->share('completedNotifications', $completedNotifications);

            // Data statistik utama
            $data = [
                'totalUsers' => User::count(),
                'totalPegawai' => User::where('role', 'pegawai')->where('status', 'active')->count(),
                'totalPeminjamanRuangan' => PeminjamanRuangan::where('status', 'disetujui')->count(),
                'totalPeminjamanVidotron' => PenyewaanVidotron::where('status', 'disetujui')->count(),
                'completedNotifications' => $completedNotifications,
            ];
            
            // DEBUG: Cek data di database
            \Log::info('=== DASHBOARD DEBUG ===');
            \Log::info('Total peminjaman ruangan: ' . PeminjamanRuangan::count());
            \Log::info('Total peminjaman ruangan disetujui: ' . PeminjamanRuangan::where('status', 'disetujui')->count());
            
            $peminjamanDisetujui = PeminjamanRuangan::where('status', 'disetujui')->get();
            \Log::info('Data peminjaman disetujui:');
            foreach ($peminjamanDisetujui as $p) {
                \Log::info('- ID: ' . $p->id . ', Tanggal_mulai: ' . $p->tanggal_mulai . ', Status: ' . $p->status);
            }
            // END DEBUG
            
            // Data untuk Jadwal yang Disetujui (SEMUA yang disetujui, tidak peduli tanggal)
            $data['jadwalDisetujui'] = PeminjamanRuangan::with(['user', 'ruangan'])
                ->where('status', 'disetujui')
                ->orderBy('tanggal_mulai', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->limit(5)
                ->get();
            
            // DEBUG data jadwal disetujui
            \Log::info('Jadwal disetujui yang akan ditampilkan: ' . $data['jadwalDisetujui']->count());
            foreach ($data['jadwalDisetujui'] as $jadwal) {
                \Log::info('Jadwal: ' . $jadwal->id . ' - ' . $jadwal->tanggal_mulai . ' - ' . ($jadwal->ruangan->nama_ruangan ?? 'No ruangan'));
            }
            
            $data['vidotronDisetujui'] = PenyewaanVidotron::with('user')
                ->where('status', 'disetujui')
                ->orderBy('tanggal_mulai', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->limit(5)
                ->get();
            
            // Data untuk Recent Users
            $data['recentUsers'] = User::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            // Data untuk Jadwal Mendatang (hanya yang tanggalnya >= hari ini)
            $data['jadwalMendatang'] = PeminjamanRuangan::with(['user', 'ruangan'])
                ->where('status', 'disetujui')
                ->where('tanggal_mulai', '>=', Carbon::today())
                ->orderBy('tanggal_mulai', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->limit(10)
                ->get();

            return view('admin.dashboard', $data);

        } catch (\Exception $e) {
            \Log::error('Error in DashboardController: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return default data jika error
            return view('admin.dashboard', [
                'totalUsers' => 0,
                'totalPegawai' => 0,
                'totalPeminjamanRuangan' => 0,
                'totalPeminjamanVidotron' => 0,
                'jadwalDisetujui' => collect(),
                'vidotronDisetujui' => collect(),
                'recentUsers' => collect(),
                'jadwalMendatang' => collect()
            ]);
        }
    }
}