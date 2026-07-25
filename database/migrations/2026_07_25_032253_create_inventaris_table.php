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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique(); // Untuk men-generate QR Code
            $table->foreignId('kategori_barang_id')->constrained('kategori_barang')->onDelete('cascade');
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->onDelete('set null');

            $table->string('nama_barang');
            $table->string('merk')->nullable();
            $table->integer('jumlah_total'); // Total barang fisik
            $table->integer('jumlah_tersedia'); // Sisa barang yang bisa dipinjam

            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->string('sumber_dana')->nullable(); // Bantuan, BOS, Kas Mandiri
            $table->date('tanggal_pengadaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
