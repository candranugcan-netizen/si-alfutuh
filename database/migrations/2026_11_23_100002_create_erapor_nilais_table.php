<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erapor_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('erapor_id')->constrained('erapor')->cascadeOnDelete();
            $table->string('kategori_kode', 20); // A, B, C, D, E, F, G
            $table->string('kategori_nama', 100);
            $table->string('sub_kategori', 100)->nullable();
            $table->string('kode', 30); // DQ 1, MH 1, DD 1, 2.1.1, etc.
            $table->text('indikator');
            $table->enum('nilai', ['BB', 'MB', 'BSH', '-'])->default('-');
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index(['erapor_id', 'kategori_kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erapor_nilai');
    }
};
