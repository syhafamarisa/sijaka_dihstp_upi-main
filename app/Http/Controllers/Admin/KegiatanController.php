<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;
use App\Models\PenyewaanVidotron;
use App\Models\Admin;
use App\Models\Ruangan;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        // Hitung total
        $peminjamanCount = PeminjamanRuangan::count();
        $penyewaanCount = PenyewaanVidotron::count();
        $totalKegiatan = $peminjamanCount + $penyewaanCount;

        // Ambil data peminjaman ruangan
        $peminjaman = PeminjamanRuangan::with(['ruangan', 'user'])
            ->when($request->status && $request->status !== 'semua', function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->dari_tanggal, function($query) use ($request) {
                return $query->where('tanggal_mulai', '>=', $request->dari_tanggal);
            })
            ->when($request->sampai_tanggal, function($query) use ($request) {
                return $query->where('tanggal_selesai', '<=', $request->sampai_tanggal);
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get()
            ->map(function($item) {
                $item->jenis = 'peminjaman_ruangan';
                $item->tanggal = $item->tanggal_mulai ? $item->tanggal_mulai->format('Y-m-d') : null;
                $item->nama_pengguna = $item->nama_pengusul ?? ($item->user->name ?? 'User');
                $item->nama_ruangan = $item->ruangan ? $item->ruangan->nama_ruangan : ('Ruangan ID: ' . $item->ruangan_id);
                $item->jenis_ruangan = $item->ruangan ? $item->ruangan->kode_ruangan : '-';
                return $item;
            });

        // Ambil data penyewaan vidotron
        $penyewaan = PenyewaanVidotron::with('user')
            ->when($request->status && $request->status !== 'semua', function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->dari_tanggal, function($query) use ($request) {
                return $query->where('tanggal_mulai', '>=', $request->dari_tanggal);
            })
            ->when($request->sampai_tanggal, function($query) use ($request) {
                return $query->where('tanggal_selesai', '<=', $request->sampai_tanggal);
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->get()
            ->map(function($item) {
                $item->jenis = 'penyewaan_vidotron';
                return $item;
            });

        // Gabungkan data
        $kegiatan = collect([]);
        
        if (!$request->jenis || $request->jenis === 'semua' || $request->jenis === 'peminjaman') {
            $kegiatan = $kegiatan->merge($peminjaman);
        }
        
        if (!$request->jenis || $request->jenis === 'semua' || $request->jenis === 'penyewaan') {
            $kegiatan = $kegiatan->merge($penyewaan);
        }

        // Urutkan berdasarkan tanggal terbaru
        $kegiatan = $kegiatan->sortByDesc(function($item) {
            return $item->jenis == 'peminjaman_ruangan' 
                ? ($item->tanggal . ' ' . $item->jam_mulai)
                : ($item->tanggal_mulai->format('Y-m-d') . ' ' . $item->waktu_mulai);
        });

        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $paginatedData = $kegiatan->slice($offset, $perPage)->all();
        $kegiatan = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedData,
            $kegiatan->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('user.daftar-kegiatan', compact('kegiatan', 'peminjamanCount', 'penyewaanCount', 'totalKegiatan'));
    }
}