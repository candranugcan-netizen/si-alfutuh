<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->string('semester', 20); // 'I' atau 'II'
            $table->string('fase', 50)->default('Pondasi');
            $table->date('tanggal_rapor')->nullable();
            $table->string('tempat_cetak', 100)->default('Semin');
            $table->string('nama_kepala_sekolah', 150)->default('Pradita Ovi Agustina, S.Pd');
            $table->string('nip_kepala_sekolah', 50)->nullable()->default('-');
            $table->string('nama_guru_kelas', 150)->nullable();
            $table->string('nip_guru_kelas', 50)->nullable()->default('-');

            // Snapshot biodata siswa untuk rapor
            $table->string('agama', 30)->nullable()->default('Islam');
            $table->string('anak_ke', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_ayah', 100)->nullable();
            $table->string('agama_ayah', 30)->nullable()->default('Islam');
            $table->string('nama_ibu', 100)->nullable();
            $table->string('agama_ibu', 30)->nullable()->default('Islam');

            // Pemeriksaan Kesehatan Fisik (Kurang / Cukup / Baik atau keterangan)
            $table->string('status_gizi', 30)->nullable()->default('Baik');
            $table->string('kondisi_rambut', 30)->nullable()->default('Baik');
            $table->string('kondisi_mata', 30)->nullable()->default('Baik');
            $table->string('kondisi_telinga', 30)->nullable()->default('Baik');
            $table->string('kondisi_hidung', 30)->nullable()->default('Baik');
            $table->string('kondisi_mulut', 30)->nullable()->default('Baik');
            $table->string('kondisi_kulit', 30)->nullable()->default('Baik');
            $table->string('kebersihan_badan', 30)->nullable()->default('Baik');
            $table->string('kebersihan_pakaian', 30)->nullable()->default('Baik');
            $table->string('berat_badan', 30)->nullable();
            $table->string('tinggi_badan', 30)->nullable();
            $table->string('lingkar_kepala', 30)->nullable();

            // Absensi / Ketidakhadiran
            $table->integer('kehadiran_sakit')->default(0);
            $table->integer('kehadiran_izin')->default(0);
            $table->integer('kehadiran_alfa')->default(0);

            // Catatan
            $table->text('catatan_guru')->nullable();
            $table->text('umpan_balik_ortu')->nullable();

            // Laporan Perkembangan Kurikulum Merdeka dengan Foto Bukti (Lampiran 4)
            // 1. Nilai Agama Islam dan Budi Pekerti
            $table->longText('narasi_agama')->nullable();
            $table->string('foto_agama_1')->nullable();
            $table->string('foto_agama_2')->nullable();

            // 2. Jati Diri
            $table->longText('narasi_jati_diri')->nullable();
            $table->string('foto_jati_diri_1')->nullable();
            $table->string('foto_jati_diri_2')->nullable();

            // 3. Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, Seni
            $table->longText('narasi_literasi_steam')->nullable();
            $table->string('foto_literasi_steam_1')->nullable();
            $table->string('foto_literasi_steam_2')->nullable();

            // 4. Kokurikuler / P5-PPRA
            $table->string('tema_projek', 255)->nullable();
            $table->longText('narasi_projek')->nullable();
            $table->string('foto_projek_1')->nullable();
            $table->string('foto_projek_2')->nullable();

            $table->enum('status', ['Draft', 'Final'])->default('Draft');
            $table->timestamps();

            $table->unique(['siswa_id', 'tahun_ajaran_id', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erapor');
    }
};
