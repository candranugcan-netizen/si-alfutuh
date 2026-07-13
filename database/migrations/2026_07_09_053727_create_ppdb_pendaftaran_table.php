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
    Schema::create('ppdb_pendaftaran', function (Blueprint $table) {
        $table->id();
        $table->string('no_pendaftaran')->unique();

        // Data Calon Siswa
        $table->string('nama_lengkap');
        $table->string('nama_panggilan');
        $table->string('nik_atau_akta')->unique();
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
        $table->string('agama')->default('Islam');

        // Data Orang Tua / Wali
        $table->string('nama_ayah');
        $table->string('nama_ibu');
        $table->string('pekerjaan_orang_tua');
        $table->string('no_hp_ortu');
        $table->string('email_ortu'); // Digunakan untuk Akun Login
        $table->text('alamat_lengkap');

        // Dokumen Fisik (Menyimpan Nama File)
        $table->string('file_kk');
        $table->string('file_akta');
        $table->string('file_foto');

        // Status Seleksi
        $table->enum('status', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_pendaftaran');
    }
};
