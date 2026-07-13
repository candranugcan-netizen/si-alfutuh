<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrateOldDataCommand extends Command
{
    protected $signature = 'migrate:old-data';
    protected $description = 'Migrasi data dari sekh2888_db_ra ke db_ra_alfutuh';

    public function handle()
    {
        $this->info('Memulai proses migrasi data lama...');

        // 1. MIGRASI SISWA, USERS, DAN ORANG TUA
        $this->info('1. Memindahkan data Siswa & membuat akun Orang Tua...');
        $oldSiswas = DB::connection('mysql_old')->table('siswa')->get();

        foreach ($oldSiswas as $oldSiswa) {
            // Cek apakah siswa sudah ada di DB baru untuk menghindari duplikasi
            $exists = DB::table('siswa')->where('id', $oldSiswa->id)->exists();
            if ($exists) continue;

            // Buat akun User untuk login Orang Tua (karena di sistem lama login melekat di siswa)
            $userId = DB::table('users')->insertGetId([
                'name' => 'Wali ' . $oldSiswa->nama,
                'email' => $oldSiswa->username . '@alfutuh.com', // fallback email tiruan
                'password' => Hash::make($oldSiswa->password), // encrypt password lama
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign Role Orang Tua (Sesuaikan ID role Orang Tua di sistem baru Anda, misal: 6)
            DB::table('model_has_roles')->insert([
                'role_id' => 6,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ]);

            // Buat data Orang Tua
            $orangTuaId = DB::table('orang_tua')->insertGetId([
                'user_id' => $userId,
                'nama_wali' => 'Wali dari ' . $oldSiswa->nama,
                'no_hp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat data Siswa (Gunakan ID asli agar relasi tabel lain aman)
            DB::table('siswa')->insert([
                'id' => $oldSiswa->id,
                'kelas_id' => 2, // Default masukkan ke Kelas A (Sesuaikan ID Kelas yang ada)
                'orang_tua_id' => $orangTuaId,
                'nama_siswa' => $oldSiswa->nama,
                'status_siswa' => $oldSiswa->status == 'Lulus' ? 'Lulus' : 'Aktif',
                'nominal_spp' => 90000.00, // Default nominal SPP baru
                'jenis_kelamin' => 'L', // Default fallback
                'tempat_lahir' => 'Sistem Lama',
                'tanggal_lahir' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. MIGRASI KEUANGAN -> TRANSAKSI
        $this->info('2. Memindahkan data Keuangan ke Transaksi...');
        $oldKeuangans = DB::connection('mysql_old')->table('keuangan')->get();

        foreach ($oldKeuangans as $old) {
            $exists = DB::table('transaksi')->where('id', $old->id)->exists();
            if ($exists) continue;

            // Mapping kategori default: 1 = SPP (Pemasukan), 4 = Gaji Guru (Pengeluaran)
            $kategoriId = ($old->jenis == 'Masuk') ? 1 : 4;

            DB::table('transaksi')->insert([
                'id' => $old->id,
                'no_transaksi' => 'TRX-OLD-' . str_pad($old->id, 6, '0', STR_PAD_LEFT),
                'tanggal' => $old->tanggal ? date('Y-m-d', strtotime($old->tanggal)) : now()->format('Y-m-d'),
                'jenis' => ($old->jenis == 'Masuk') ? 'Pemasukan' : 'Pengeluaran',
                'kategori_transaksi_id' => $kategoriId,
                'siswa_id' => $old->siswa_id, // Terhubung aman karena ID siswa dipertahankan
                'user_id' => 1, // Default diinput oleh Super Admin (ID 1)
                'nominal' => $old->jumlah,
                'keterangan' => $old->keterangan ?? $old->sumber,
                'created_at' => $old->tanggal ?? now(),
                'updated_at' => $old->tanggal ?? now(),
            ]);
        }

        // 3. MIGRASI MEMORI -> AKTIVITAS KENANGAN
        $this->info('3. Memindahkan data Memori ke Aktivitas Kenangan...');
        $oldMemoris = DB::connection('mysql_old')->table('memori')->get();

        foreach ($oldMemoris as $old) {
            $exists = DB::table('aktivitas_kenangan')->where('id', $old->id)->exists();
            if ($exists) continue;

            DB::table('aktivitas_kenangan')->insert([
                'id' => $old->id,
                'siswa_id' => $old->siswa_id,
                'tahun_ajaran_id' => 1, // Default Tahun Ajaran pertama/aktif
                'semester' => 'Ganjil',
                'foto_aktivitas' => $old->foto ?? 'default.jpg',
                'judul_aktivitas' => 'Arsip Sistem Lama',
                'narasi_perkembangan' => $old->keterangan ?? '-',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. MIGRASI PERKEMBANGAN -> AL-QURAN & AISMA
        $this->info('4. Memindahkan data Perkembangan Belajar...');
        $oldPerkembangans = DB::connection('mysql_old')->table('perkembangan')->get();

        foreach ($oldPerkembangans as $old) {
            // Jika ada riwayat Al-Quran lama, pindah ke tabel perkembangan_alquran baru
            if (!empty($old->al_quran)) {
                DB::table('perkembangan_alquran')->insert([
                    'siswa_id' => $old->siswa_id,
                    'guru_id' => 1, // Default Guru Pengampu sementara
                    'tanggal' => $old->tanggal ? date('Y-m-d', strtotime($old->tanggal)) : now()->format('Y-m-d'),
                    'jenis' => 'Iqro',
                    'jilid' => '-',
                    'halaman' => '-',
                    'status_kelancaran' => 'Cukup Lancar',
                    'catatan' => 'Arsip Lama: ' . $old->al_quran . ($old->catatan ? ' | Ket: ' . $old->catatan : ''),
                    'created_at' => $old->tanggal ?? now(),
                    'updated_at' => $old->tanggal ?? now(),
                ]);
            }

            // Jika ada riwayat Baca Tulis lama, pindah ke tabel perkembangan_aisma baru
            if (!empty($old->baca_tulis)) {
                DB::table('perkembangan_aisma')->insert([
                    'siswa_id' => $old->siswa_id,
                    'guru_id' => 1,
                    'tanggal' => $old->tanggal ? date('Y-m-d', strtotime($old->tanggal)) : now()->format('Y-m-d'),
                    'level_jilid' => '-',
                    'halaman_modul' => '-',
                    'catatan' => 'Arsip Lama: ' . $old->baca_tulis . ($old->catatan ? ' | Ket: ' . $old->catatan : ''),
                    'created_at' => $old->tanggal ?? now(),
                    'updated_at' => $old->tanggal ?? now(),
                ]);
            }
        }

        $this->info('Migrasi Selesai dengan Sukses!');
    }
}
