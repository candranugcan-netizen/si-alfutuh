<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('agama', 30)->default('Islam')->after('tanggal_lahir');
            $table->string('anak_ke', 20)->nullable()->after('agama');
            $table->text('alamat')->nullable()->after('anak_ke');
            $table->string('nama_ayah', 100)->nullable()->after('alamat');
            $table->string('nama_ibu', 100)->nullable()->after('nama_ayah');
            $table->string('agama_ayah', 30)->default('Islam')->after('nama_ayah');
            $table->string('agama_ibu', 30)->default('Islam')->after('nama_ibu');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn([
                'agama',
                'anak_ke',
                'alamat',
                'nama_ayah',
                'nama_ibu',
                'agama_ayah',
                'agama_ibu',
            ]);
        });
    }
};
