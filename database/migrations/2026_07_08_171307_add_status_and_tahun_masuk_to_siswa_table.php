<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Menambahkan status siswa dengan default 'Aktif'
            $table->enum('status_siswa', ['Aktif', 'Lulus', 'Pindah', 'Drop Out'])
                  ->default('Aktif')
                  ->after('nominal_spp');

            // Menambahkan tahun masuk sekolah
            $table->year('tahun_masuk')->nullable()->after('status_siswa');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['status_siswa', 'tahun_masuk']);
        });
    }
};
