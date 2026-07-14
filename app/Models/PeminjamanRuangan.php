<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeminjamanRuangan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_ruangan';

    protected $fillable = [
        'user_id',
        'jenis_pengusul',
        'nama_pengusul',
        'nim_nip',
        'fakultas',
        'program_studi',
        'email',
        'no_telepon',
        'ruangan_id',
        'acara',
        'hari',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'jumlah_peserta',
        'keterangan',
        'lampiran_surat',
        'status',
        'alasan_penolakan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_mulai', today());
    }

    public function scopeMendatang($query)
    {
        return $query->whereDate('tanggal_mulai', '>=', today())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereYear('tanggal_mulai', now()->year)
            ->whereMonth('tanggal_mulai', now()->month);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getStatusRealTimeAttribute()
    {
        $now = now();

        if ($this->status === 'dibatalkan') {
            return 'dibatalkan';
        }

        if ($this->status !== 'disetujui') {
            return $this->status;
        }

        if ($this->tanggal_mulai->isFuture()) {
            return 'akan_datang';
        }

        if ($this->tanggal_selesai->isPast()) {
            return 'selesai';
        }

        $mulai = Carbon::parse($this->tanggal_mulai->format('Y-m-d') . ' ' . $this->jam_mulai);
        $selesai = Carbon::parse($this->tanggal_selesai->format('Y-m-d') . ' ' . $this->jam_selesai);

        if ($now->between($mulai, $selesai)) {
            return 'berlangsung';
        }

        if ($now->lt($mulai)) {
            return 'akan_datang';
        }

        return 'selesai';
    }

    public function getTanggalLengkapAttribute()
    {
        return $this->tanggal_mulai->translatedFormat('l, d F Y');
    }

    public function getJamMulaiShortAttribute()
    {
        return substr($this->jam_mulai, 0, 5);
    }

    public function getJamSelesaiShortAttribute()
    {
        return substr($this->jam_selesai, 0, 5);
    }

    public function getRentangWaktuAttribute()
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }

    public function getSedangBerlangsungAttribute()
    {
        if ($this->status !== 'disetujui') {
            return false;
        }

        $now = now();

        $mulai = Carbon::parse($this->tanggal_mulai->format('Y-m-d') . ' ' . $this->jam_mulai);
        $selesai = Carbon::parse($this->tanggal_selesai->format('Y-m-d') . ' ' . $this->jam_selesai);

        return $now->between($mulai, $selesai);
    }

    public function getInformasiPengusulAttribute()
    {
        $info = [];

        if ($this->nama_pengusul) {
            $info[] = $this->nama_pengusul;
        }

        if ($this->jenis_pengusul) {
            $info[] = $this->jenis_pengusul;
        }

        if ($this->nim_nip) {
            $info[] = $this->nim_nip;
        }

        if ($this->fakultas && $this->program_studi) {
            $info[] = $this->fakultas . ' - ' . $this->program_studi;
        } elseif ($this->fakultas) {
            $info[] = $this->fakultas;
        } elseif ($this->program_studi) {
            $info[] = $this->program_studi;
        }

        return implode(' | ', $info);
    }
}