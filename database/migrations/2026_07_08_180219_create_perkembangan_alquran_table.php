<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perkembangan_alquran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade'); // Mengikat ke user/guru yang input
            $table->date('tanggal');
            $table->enum('jenis', ['Iqro', 'Al-Quran']);
            $table->string('jilid', 50); // Contoh: Jilid 1, Juz 30
            $table->string('halaman', 100); // Contoh: Hal. 10, Surah Al-Baqarah 1-5
            $table->enum('status_kelancaran', ['Belum Lancar', 'Cukup Lancar', 'Lancar'])->default('Cukup Lancar');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkembangan_alquran');
    }
};
