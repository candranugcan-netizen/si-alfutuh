<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('materi_parenting', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('deskripsi')->nullable();
        $table->enum('tipe', ['pdf', 'video']); // Membedakan tipe materi
        $table->string('file_pdf')->nullable(); // Menyimpan nama file PDF
        $table->string('link_youtube')->nullable(); // Menyimpan ID/Link Video
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_parentings');
    }
};
