<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas_kenangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('foto_aktivitas'); // Menyimpan path/nama file gambar terkompresi
            $table->string('judul_aktivitas', 150); // Contoh: Praktik Manasik Haji
            $table->text('narasi_perkembangan'); // Pesan emosional/perkembangan anak untuk ortu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_kenangan');
    }
};
