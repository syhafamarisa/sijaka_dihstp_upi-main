<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            $table->string('jenis_pengusul')->nullable()->after('user_id');
            $table->string('nama_pengusul')->nullable()->after('jenis_pengusul');
            $table->string('nim_nip')->nullable()->after('nama_pengusul');
            $table->string('fakultas')->nullable()->after('nim_nip');
            $table->string('program_studi')->nullable()->after('fakultas');
            $table->string('email')->nullable()->after('program_studi');
            $table->string('no_telepon')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_pengusul',
                'nama_pengusul',
                'nim_nip',
                'fakultas',
                'program_studi',
                'email',
                'no_telepon'
            ]);
        });
    }
};