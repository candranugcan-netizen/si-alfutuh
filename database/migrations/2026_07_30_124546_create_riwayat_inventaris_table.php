<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->string('jenis_perubahan'); // Contoh: 'Mutasi Ruangan', 'Perubahan Kondisi', 'Penyesuaian Stok'
            $table->string('keterangan'); // Detail penjelasan perubahannya
            $table->string('petugas')->nullable(); // Siapa yang merubah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_inventaris');
    }
};
