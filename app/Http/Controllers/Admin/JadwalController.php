<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\JadwalPegawaiMail;

class JadwalController extends Controller
{

    public function index()
    {
        $jadwal = Jadwal::with([
            'ruangan',
            'user',
            'peserta'
        ])->latest()->paginate(10);

        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $ruangan = Ruangan::orderBy('nama_ruangan')->get();

        $pegawai = User::where('role','pegawai')
            ->where('status','active')
            ->orderBy('name')
            ->get();

        return view('admin.jadwal.create', compact(
            'ruangan',
            'pegawai'
        ));
    }

    public function show($id)
    {
        $jadwal = Jadwal::with(['ruangan', 'user', 'peserta'])->findOrFail($id);

        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'=>'required',
            'ruangan_id'=>'required',
            'tanggal_mulai'=>'required|date',
            'tanggal_selesai'=>'required|date',
            'waktu_mulai'=>'required',
            'waktu_selesai'=>'required',
            'peserta'=>'required|array'
        ]);

        DB::transaction(function() use($request){

            $jadwal = Jadwal::create([
                'user_id'=>auth()->id(),
                'ruangan_id'=>$request->ruangan_id,
                'nama_kegiatan'=>$request->nama_kegiatan,
                'tanggal_mulai'=>$request->tanggal_mulai,
                'tanggal_selesai'=>$request->tanggal_selesai,
                'waktu_mulai'=>$request->waktu_mulai,
                'waktu_selesai'=>$request->waktu_selesai,
                'kapasitas_peserta'=>count($request->peserta),
                'deskripsi'=>$request->deskripsi
            ]);

            // simpan peserta
            $jadwal->peserta()->sync($request->peserta);

            // Buat notifikasi dan kirim email
foreach ($request->peserta as $pegawaiId) {

    Notifikasi::create([
        'user_id'   => $pegawaiId,
        'jadwal_id' => $jadwal->id,
        'judul'     => 'Undangan Kegiatan',
        'pesan'     => 'Anda ditugaskan mengikuti kegiatan "' . $jadwal->nama_kegiatan . '"',
        'is_read'   => 0,
    ]);

    // Ambil data pegawai
    $user = User::find($pegawaiId);

    // Kirim email jika email tersedia
    if ($user && !empty($user->email)) {
        Mail::to($user->email)
            ->send(new JadwalPegawaiMail($user, $jadwal));
    }
}

        });

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success','Jadwal berhasil dibuat');
    }
}

