<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ruangan;

class StoreJadwalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'nama_kegiatan' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date|after_or_equal:today',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'waktu_mulai' => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        'ruangan_id' => 'required|exists:ruangan,id',
        'kapasitas_peserta' => 'nullable|integer|min:1',
        'deskripsi' => 'nullable|string',
    ];
}

    public function messages()
    {
        return [
            'nama_kegiatan.required'         => 'Nama kegiatan wajib diisi.',
            'tanggal_mulai.required'         => 'Tanggal mulai wajib dipilih.',
            'tanggal_mulai.after_or_equal'   => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib dipilih.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'waktu_mulai.required'           => 'Waktu mulai wajib diisi.',
            'waktu_mulai.date_format'        => 'Format waktu mulai tidak valid.',
            'waktu_selesai.required'         => 'Waktu selesai wajib diisi.',
            'waktu_selesai.date_format'      => 'Format waktu selesai tidak valid.',
            'waktu_selesai.after'            => 'Waktu selesai harus setelah waktu mulai.',
            'ruangan_id.required'            => 'Pilih ruangan terlebih dahulu.',
            'ruangan_id.exists'              => 'Ruangan tidak ditemukan.',
            'kapasitas_peserta.integer'      => 'Kapasitas peserta harus berupa angka.',
            'kapasitas_peserta.min'          => 'Jumlah peserta minimal 1 orang.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (
                $this->filled('ruangan_id') &&
                $this->filled('tanggal_mulai') &&
                $this->filled('tanggal_selesai') &&
                $this->filled('waktu_mulai') &&
                $this->filled('waktu_selesai')
            ) {
                $ruangan = Ruangan::find($this->ruangan_id);


                if (!$ruangan) {
                    $validator->errors()->add(
                        'ruangan_id',
                        'Ruangan tidak ditemukan.'
                    );
                    return;
                }

                $tersedia = $ruangan->isAvailable(
                    $this->tanggal_mulai,
                    $this->tanggal_selesai,
                    $this->waktu_mulai,
                    $this->waktu_selesai
                );

                if (!$tersedia) {
                    $validator->errors()->add(
                        'ruangan_id',
                        'Ruangan tidak tersedia pada tanggal dan jam tersebut.'
                    );
                }

                if (
                    $this->filled('kapasitas_peserta') &&
                    $this->kapasitas_peserta > $ruangan->kapasitas
                ) {
                    $validator->errors()->add(
                        'kapasitas_peserta',
                        'Jumlah peserta melebihi kapasitas ruangan (' . $ruangan->kapasitas . ' orang).'
                    );
                }
            }
        });
    }
}