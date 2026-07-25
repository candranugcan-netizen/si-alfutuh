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
        Schema::create('peminjaman_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');

            $table->string('nama_peminjam'); // Bisa guru atau wali murid
            $table->string('peran_peminjam')->nullable(); // cth: Guru Kelas, Wali Murid

            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('batas_waktu_kembali')->nullable();
            $table->date('tanggal_dikembalikan')->nullable();

            $table->enum('status', ['Dipinjam', 'Dikembalikan'])->default('Dipinjam');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barangs');
    }
};
