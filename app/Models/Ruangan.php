<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jadwal;
use App\Models\PeminjamanRuangan;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'kapasitas',
        'fasilitas',
        'status',
        'lokasi',
        'keterangan',
        'foto',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'ruangan_id');
    }

    public function peminjamanRuangan()
    {
        return $this->hasMany(PeminjamanRuangan::class, 'ruangan_id');
    }

    /**
     * FINAL FIXED VERSION
     * Cek ketersediaan ruangan (tanpa bug parameter & SQL NULL)
     */
    public function isAvailable($tanggal_mulai, $tanggal_selesai, $jam_mulai, $jam_selesai, $excludeJadwalId = null)
    {
        // 1. status ruangan
        if ($this->status !== 'tersedia') {
            return false;
        }

        // 2. validasi data kosong (hindari illegal operator)
        if (!$tanggal_mulai || !$tanggal_selesai || !$jam_mulai || !$jam_selesai) {
            return false;
        }

        // 3. cek konflik jadwal
        $jadwal = Jadwal::where('ruangan_id', $this->id)
            ->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                $q->where('tanggal_mulai', '<=', $tanggal_selesai)
                    ->where('tanggal_selesai', '>=', $tanggal_mulai);
            })
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->where('waktu_mulai', '<', $jam_selesai)
                    ->where('waktu_selesai', '>', $jam_mulai);
            });

if ($excludeJadwalId) {
    $jadwal->where('id', '!=', $excludeJadwalId);
}

$konflikJadwal = $jadwal->exists();

        // 4. cek konflik peminjaman
        $konflikPeminjaman = PeminjamanRuangan::where('ruangan_id', $this->id)
            ->where('status', 'disetujui')
            ->where(function ($q) use ($tanggal_mulai) {
                $q->where('tanggal_mulai', '<=', $tanggal_mulai);
    })
            ->exists();

        return !$konflikJadwal && !$konflikPeminjaman;
    }
}