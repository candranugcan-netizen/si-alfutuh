<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 50)->unique();
            $table->date('tanggal');
            $table->enum('jenis', ['Pemasukan', 'Pengeluaran']);

            // Relasi ke Kategori
            $table->foreignId('kategori_transaksi_id')->constrained('kategori_transaksi')->onDelete('restrict');

            // Relasi ke Siswa (Hanya terisi jika ini Pemasukan SPP/Siswa)
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('set null');
            $table->string('untuk_bulan', 20)->nullable(); // Misal: "Juli 2026"

            // Relasi ke User (Bendahara pencatat)
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
