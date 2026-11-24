<?php

namespace App\Services;

use App\Models\Erapor;
use App\Models\EraporNilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EraporService
{
    /**
     * Dapatkan atau inisialisasi record Erapor untuk Siswa, Tahun Ajaran, dan Semester
     */
    public function getOrInitErapor(int $siswaId, ?int $tahunAjaranId = null, ?string $semester = null): Erapor
    {
        $siswa = Siswa::with(['kelas', 'orangTua'])->findOrFail($siswaId);

        // Jika tahun ajaran tidak ditentukan, ambil yang sedang aktif atau yang terbaru
        if (!$tahunAjaranId) {
            $tahunAktif = TahunAjaran::where('is_active', true)->first() ?? TahunAjaran::latest()->first();
            $tahunAjaranId = $tahunAktif ? $tahunAktif->id : null;
        }

        // Jika semester tidak ditentukan, ambil dari tahun ajaran aktif atau default 'II' (Genap)
        if (!$semester) {
            $tahunAktif = TahunAjaran::find($tahunAjaranId);
            $semester = $tahunAktif && in_array(strtoupper($tahunAktif->semester), ['1', 'GANJIL', 'I']) ? 'I' : 'II';
        }

        $erapor = Erapor::firstOrNew([
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $tahunAjaranId,
            'semester' => $semester,
        ]);

        if (!$erapor->exists) {
            // Isi default biodata rapor dari data master siswa
            $erapor->fase = 'Pondasi';
            $erapor->tempat_cetak = 'Semin';
            $erapor->tanggal_rapor = now()->toDateString();
            $erapor->nama_kepala_sekolah = 'Pradita Ovi Agustina, S.Pd';
            $erapor->nip_kepala_sekolah = '-';
            $erapor->nama_guru_kelas = auth()->user()->name ?? 'Guru Kelas';
            $erapor->nip_guru_kelas = '-';

            $erapor->agama = $siswa->agama ?? 'Islam';
            $erapor->anak_ke = $siswa->anak_ke ?? '1';
            $erapor->alamat = $siswa->alamat ?? 'Klepu RT 4 RW 4 Rejosari Semin Gunungkidul';
            $erapor->nama_ayah = $siswa->nama_ayah ?? ($siswa->orangTua->nama_wali ?? '-');
            $erapor->agama_ayah = $siswa->agama_ayah ?? 'Islam';
            $erapor->nama_ibu = $siswa->nama_ibu ?? '-';
            $erapor->agama_ibu = $siswa->agama_ibu ?? 'Islam';

            // Hitung absensi otomatis dari tabel absensi jika ada
            $erapor->kehadiran_sakit = Absensi::where('siswa_id', $siswa->id)->where('status', 'Sakit')->count();
            $erapor->kehadiran_izin  = Absensi::where('siswa_id', $siswa->id)->where('status', 'Izin')->count();
            $erapor->kehadiran_alfa  = Absensi::where('siswa_id', $siswa->id)->where('status', 'Alfa')->count();

            $erapor->status_gizi = 'Baik';
            $erapor->kondisi_rambut = 'Baik';
            $erapor->kondisi_mata = 'Baik';
            $erapor->kondisi_telinga = 'Baik';
            $erapor->kondisi_hidung = 'Baik';
            $erapor->kondisi_mulut = 'Baik';
            $erapor->kondisi_kulit = 'Baik';
            $erapor->kebersihan_badan = 'Baik';
            $erapor->kebersihan_pakaian = 'Baik';

            $erapor->status = 'Draft';
            $erapor->save();
        } else {
            // Jika e-rapor sudah ada namun alamat di e-rapor masih memakai default atau kosong, sinkronkan dengan alamat Data Siswa
            if (!empty($siswa->alamat) && (empty($erapor->alamat) || $erapor->alamat === 'Klepu RT 4 RW 4 Rejosari Semin Gunungkidul')) {
                $erapor->alamat = $siswa->alamat;
                $erapor->save();
            }
        }

        // Pastikan indikator default sudah terisi di tabel erapor_nilai
        $this->ensureIndikatorDefaults($erapor);

        return $erapor->load(['siswa.kelas', 'tahunAjaran', 'nilai']);
    }

    /**
     * Memastikan semua butir indikator dari lampiran format sudah tersimpan pada erapor ini
     * Mendeteksi otomatis apakah siswa merupakan Kelas A atau Kelas B
     */
    public function ensureIndikatorDefaults(Erapor $erapor, bool $forceReset = false): void
    {
        if (!$forceReset) {
            $existingCount = EraporNilai::where('erapor_id', $erapor->id)->count();
            if ($existingCount > 0) {
                return;
            }
        } else {
            EraporNilai::where('erapor_id', $erapor->id)->delete();
        }

        $siswa = $erapor->siswa ?? Siswa::with('kelas')->find($erapor->siswa_id);
        $kelompok = 'A';
        if ($siswa && $siswa->kelas) {
            $namaKelas = strtoupper(trim($siswa->kelas->kelompok ?? $siswa->kelas->nama_kelas ?? 'A'));
            if (str_contains($namaKelas, 'B')) {
                $kelompok = 'B';
            }
        }

        $catalog = Erapor::getDaftarIndikatorDefault($kelompok);
        $urutan = 1;
        $insertData = [];
        $now = now();

        foreach ($catalog as $cat) {
            foreach ($cat['items'] as $item) {
                $insertData[] = [
                    'erapor_id' => $erapor->id,
                    'kategori_kode' => $cat['kategori_kode'],
                    'kategori_nama' => $cat['kategori_nama'],
                    'sub_kategori' => $cat['sub_kategori'],
                    'kode' => $item['kode'],
                    'indikator' => $item['indikator'],
                    'nilai' => '-', // Default belum diisi
                    'is_selected' => true,
                    'urutan' => $urutan++,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($insertData)) {
            EraporNilai::insert($insertData);
        }
    }

    /**
     * Simpan pembaruan e-Rapor dan nilai indikator beserta foto-foto bukti perkembangan
     */
    public function updateErapor(Erapor $erapor, array $data, $files = []): Erapor
    {
        return DB::transaction(function () use ($erapor, $data, $files) {
            $manager = new ImageManager(new Driver());

            // Daftar field foto bukti perkembangan
            $fotoFields = [
                'foto_agama_1', 'foto_agama_2',
                'foto_jati_diri_1', 'foto_jati_diri_2',
                'foto_literasi_steam_1', 'foto_literasi_steam_2',
                'foto_projek_1', 'foto_projek_2',
            ];

            foreach ($fotoFields as $field) {
                if (isset($files[$field]) && $files[$field]->isValid()) {
                    $file = $files[$field];
                    $filename = 'erapor_' . $field . '_' . time() . '_' . uniqid() . '.webp';

                    $img = $manager->read($file);
                    $img->scale(width: 1200);
                    $encoded = $img->toWebp(82);

                    Storage::disk('public')->put('erapor_foto/' . $filename, $encoded);

                    // Hapus foto lama jika ada
                    if ($erapor->$field && Storage::disk('public')->exists('erapor_foto/' . $erapor->$field)) {
                        Storage::disk('public')->delete('erapor_foto/' . $erapor->$field);
                    }

                    $data[$field] = $filename;
                }
            }

            // Update data header & narasi e-Rapor
            $erapor->fill($data);
            $erapor->save();

            // Update nilai-nilai indikator
            if (isset($data['nilai']) && is_array($data['nilai'])) {
                foreach ($data['nilai'] as $nilaiId => $val) {
                    $cleanVal = in_array($val, ['BB', 'MB', 'BSH', '-']) ? $val : '-';
                    EraporNilai::where('id', $nilaiId)
                        ->where('erapor_id', $erapor->id)
                        ->update(['nilai' => $cleanVal]);
                }
            }

            // Update seleksi indikator yang akan dicetak di rapor
            if (isset($data['indikator_selection_present'])) {
                $selectedIds = isset($data['selected_indikator']) && is_array($data['selected_indikator'])
                    ? array_map('intval', $data['selected_indikator'])
                    : [];

                // Set semua indikator rapor ini menjadi tidak terpilih terlebih dahulu
                EraporNilai::where('erapor_id', $erapor->id)->update(['is_selected' => false]);

                // Aktifkan indikator yang dicentang
                if (!empty($selectedIds)) {
                    EraporNilai::where('erapor_id', $erapor->id)
                        ->whereIn('id', $selectedIds)
                        ->update(['is_selected' => true]);
                }
            }

            // Juga update data biodata master siswa jika diisi agar selalu sinkron
            if ($erapor->siswa) {
                $siswa = $erapor->siswa;
                $updateSiswa = [];
                if (!empty($data['agama'])) $updateSiswa['agama'] = $data['agama'];
                if (!empty($data['anak_ke'])) $updateSiswa['anak_ke'] = $data['anak_ke'];
                if (!empty($data['alamat'])) $updateSiswa['alamat'] = $data['alamat'];
                if (!empty($data['nama_ayah'])) $updateSiswa['nama_ayah'] = $data['nama_ayah'];
                if (!empty($data['nama_ibu'])) $updateSiswa['nama_ibu'] = $data['nama_ibu'];
                if (!empty($data['agama_ayah'])) $updateSiswa['agama_ayah'] = $data['agama_ayah'];
                if (!empty($data['agama_ibu'])) $updateSiswa['agama_ibu'] = $data['agama_ibu'];

                if (!empty($updateSiswa)) {
                    $siswa->update($updateSiswa);
                }
            }

            return $erapor;
        });
    }
}
