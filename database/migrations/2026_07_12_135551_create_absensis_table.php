<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('absensi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
        $table->date('tanggal');
        $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa']);
        $table->string('keterangan')->nullable();
        $table->timestamps();

        // Mencegah duplikasi: 1 siswa hanya punya 1 data absensi di hari yang sama
        $table->unique(['siswa_id', 'tanggal']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
