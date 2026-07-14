<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        \DB::statement("ALTER TABLE penyewaan_vidotron MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'selesai', 'dibatalkan') DEFAULT 'menunggu'");
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        \DB::statement("ALTER TABLE penyewaan_vidotron MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'dibatalkan') DEFAULT 'menunggu'");
        Schema::enableForeignKeyConstraints();
    }
};
