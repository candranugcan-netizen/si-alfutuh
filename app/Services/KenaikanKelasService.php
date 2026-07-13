<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\RiwayatKelas;
use Illuminate\Support\Facades\DB;

class KenaikanKelasService
{
    /**
     * Memproses mutasi/kenaikan kelas siswa secara massal
     */
    public function prosesMutasiMassal(array $data)
    {
        return DB::transaction(function () use ($data) {
            $siswaIds = $data['siswa_ids'];
            $keterangan = $data['keterangan'];
            $tahunAjaranId = $data['tahun_ajaran_id'];
            $kelasTujuanId = $data['kelas_tujuan_id'] ?? null;

            foreach ($siswaIds as $siswaId) {
                // Ambil data siswa saat ini (kunci baris agar tidak diedit proses lain / lockForUpdate)
                $siswa = Siswa::lockForUpdate()->find($siswaId);

                if (!$siswa) continue;

                $kelasLamaId = $siswa->kelas_id;

                // 1. Catat di Buku Riwayat (Merekam jejak kelas SEBELUM dipindah)
                RiwayatKelas::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasLamaId,
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'keterangan' => $keterangan,
                ]);

                // 2. Update Data Utama Siswa (Current State)
                if ($keterangan === 'Lulus') {
                    // Jika lulus, kelas_id dibiarkan di kelas terakhir, tapi status berubah
                    $siswa->update([
                        'status_siswa' => 'Lulus'
                    ]);
                } else {
                    // Jika Naik / Tinggal Kelas, status tetap Aktif, tapi kelas_id berubah
                    $siswa->update([
                        'kelas_id' => $kelasTujuanId,
                        'status_siswa' => 'Aktif' // Berjaga-jaga siapa tahu sebelumnya salah set
                    ]);
                }
            }

            return true;
        });
    }
}
