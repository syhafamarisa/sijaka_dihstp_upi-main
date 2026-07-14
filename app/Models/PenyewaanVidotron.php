<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyewaanVidotron extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'penyewaan_vidotron'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'user_id',
        'fakultas',
        'program_studi',
        'jenis_pengusul',
        'nama_pengusul',
        'nim_nidn',
        'email',
        'no_telepon',
        'tujuan_pemasangan',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'jenis_konten',
        'deskripsi_konten',
        'link_konten',
        'surat_pengajuan',
        'status',
        'alasan_penolakan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}