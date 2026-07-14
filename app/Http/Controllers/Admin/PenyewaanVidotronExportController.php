<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenyewaanVidotron;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenyewaanVidotronExportController extends Controller
{
    /**
     * Export ke CSV.
     *
     * Untuk saat ini: cukup memenuhi tombol Export agar berfungsi.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        try {
            $query = PenyewaanVidotron::query();

            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('tujuan_pemasangan', 'like', "%{$search}%")
                        ->orWhere('nama_pengusul', 'like', "%{$search}%")
                        ->orWhere('program_studi', 'like', "%{$search}%")
                        ->orWhere('fakultas', 'like', "%{$search}%");
                });
            }

            $rows = $query->orderBy('created_at', 'desc')->get([
                'id',
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
                'status',
                'updated_at',
                'created_at'
            ]);

            $filename = 'export_peminjaman_vidotron_' . now()->format('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename=' . $filename,
            ];

            $callback = function () use ($rows) {
                $out = fopen('php://output', 'w');

                // Header kolom
                fputcsv($out, [
                    'ID', 'User ID', 'Fakultas', 'Program Studi', 'Jenis Pengusul', 'Nama Pengusul',
                    'NIM/NIDN', 'Email', 'No Telepon', 'Tujuan Pemasangan',
                    'Tanggal Mulai', 'Tanggal Selesai', 'Waktu Mulai', 'Waktu Selesai',
                    'Jenis Konten', 'Deskripsi Konten', 'Link Konten', 'Status',
                    'Created At', 'Updated At'
                ]);

                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id,
                        $row->user_id,
                        $row->fakultas,
                        $row->program_studi,
                        $row->jenis_pengusul,
                        $row->nama_pengusul,
                        $row->nim_nidn,
                        $row->email,
                        $row->no_telepon,
                        $row->tujuan_pemasangan,
                        $row->tanggal_mulai,
                        $row->tanggal_selesai,
                        $row->waktu_mulai,
                        $row->waktu_selesai,
                        $row->jenis_konten,
                        $row->deskripsi_konten,
                        $row->link_konten,
                        $row->status,
                        optional($row->created_at)->toDateTimeString(),
                        optional($row->updated_at)->toDateTimeString(),
                    ]);
                }

                fclose($out);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Throwable $e) {
            Log::error('Export CSV error: ' . $e->getMessage());

            return response()->stream(function () {
                echo 'Export gagal';
            }, 500, ['Content-Type' => 'text/plain; charset=UTF-8']);
        }
    }
}

