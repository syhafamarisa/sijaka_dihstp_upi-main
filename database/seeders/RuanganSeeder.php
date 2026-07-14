<?php
// database/seeders/RuanganSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ruangan')->delete();

        $ruangan = [
            [
                'kode_ruangan' => 'A101',
                'nama_ruangan' => 'Ruang Rapat Besar',
                'kapasitas' => 50,
                'fasilitas' => 'meja(15), kursi(24), seperangkat alat sound(audio, sound system, mic(2)), TV 90inc',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_ruangan' => 'A102',
                'nama_ruangan' => 'Ruang Conference',
                'kapasitas' => 20,
                'fasilitas' => 'meja(2), kursi(15), TV 60inc',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_ruangan' => 'B201',
                'nama_ruangan' => 'Ruang Digital Corner',
                'kapasitas' => 25,
                'fasilitas' => 'Meja(10), beanbag(12), TV 65inc',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('ruangan')->insert($ruangan);
        
        $this->command->info('Ruangan seeded successfully!');
        $this->command->info('Total ruangan: ' . count($ruangan));
    }
}