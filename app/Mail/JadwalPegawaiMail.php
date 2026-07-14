<?php

namespace App\Mail;

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JadwalPegawaiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $jadwal;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Jadwal $jadwal)
    {
        $this->user = $user;
        $this->jadwal = $jadwal;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Anda Ditugaskan pada Kegiatan Baru - SIJAKAPRANA')
                    ->view('emails.jadwal_pegawai');
    }
}