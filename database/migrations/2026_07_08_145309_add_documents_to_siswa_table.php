<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('foto_anak')->nullable()->after('nominal_spp');
            $table->string('akta_kelahiran')->nullable()->after('foto_anak');
            $table->string('kartu_keluarga')->nullable()->after('akta_kelahiran');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['foto_anak', 'akta_kelahiran', 'kartu_keluarga']);
        });
    }
};
