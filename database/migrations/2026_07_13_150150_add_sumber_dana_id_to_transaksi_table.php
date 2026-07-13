<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menambahkan kolom sumber_dana_id setelah kolom keterangan
            $table->unsignedBigInteger('sumber_dana_id')->nullable()->after('keterangan');

            // Relasi foreign key ke tabel kategori_transaksi
            $table->foreign('sumber_dana_id')
                  ->references('id')
                  ->on('kategori_transaksi')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['sumber_dana_id']);
            $table->dropColumn('sumber_dana_id');
        });
    }
};
