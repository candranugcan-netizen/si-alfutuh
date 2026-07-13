<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cari bagian di dalam Schema::create('siswa', ...) lalu ubah menjadi seperti ini:
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('restrict');
            // UBAH BARIS INI: Hubungkan siswa ke tabel orang tua
            $table->foreignId('orang_tua_id')->constrained('orang_tua')->onDelete('cascade');
            $table->string('nis', 20)->unique()->nullable();
            $table->string('nama_siswa', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
