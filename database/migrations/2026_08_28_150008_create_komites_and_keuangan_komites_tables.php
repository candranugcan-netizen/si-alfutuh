<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nik', 20)->nullable();
            $table->string('nama_komite', 100);
            $table->string('jabatan', 50);
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('periode', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('keuangan_komites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komite_id')->constrained('komites')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->string('kategori', 50);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('bukti_nota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan_komites');
        Schema::dropIfExists('komites');
    }
};
