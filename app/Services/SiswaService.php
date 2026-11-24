<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class SiswaService
{
    public function createSiswa(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Cek apakah akun Orang Tua dengan email ini sudah ada (fitur cerdas untuk kakak-adik)
            $user = User::where('email', $data['email_ortu'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $data['nama_wali'],
                    'email' => $data['email_ortu'],
                    'password' => Hash::make('ortu123'), // Password default akun Ortu
                ]);
                $user->assignRole('Orang Tua');
            }

            // 2. Ambil atau buat data profil Orang Tua
            $orangTua = OrangTua::where('user_id', $user->id)->first();
            if (!$orangTua) {
                $orangTua = OrangTua::create([
                    'user_id' => $user->id,
                    'nama_wali' => $data['nama_wali'],
                    'no_hp' => $data['no_hp_ortu'],
                ]);
            }

            // 3. Daftarkan data siswa (SIMPAN KE VARIABEL, JANGAN LANGSUNG RETURN)
            $siswa = Siswa::create([
                'kelas_id' => $data['kelas_id'],
                'orang_tua_id' => $orangTua->id,
                'nis' => $data['nis'],
                'nama_siswa' => $data['nama_siswa'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'nominal_spp' => $data['nominal_spp'],
                'alamat' => $data['alamat'] ?? null,
            ]);

            // 4. Proses Loop Kompresi & Upload Dokumen Siswa
            $manager = new ImageManager(new Driver());
            $dokumenFields = ['foto_anak', 'akta_kelahiran', 'kartu_keluarga'];

            // Array penampung nama file yang berhasil di-upload
            $dokumenYangDiupdate = [];

            foreach ($dokumenFields as $field) {
                // Gunakan isset() pada array $data, dan pastikan itu instance dari UploadedFile
                if (isset($data[$field]) && $data[$field] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $data[$field];
                    $filename = time() . '_' . $field . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png'])) {
                        $image = $manager->read($file);
                        $image->scale(width: 1200);
                        $encoded = $image->toJpeg(75);
                        Storage::disk('public')->put('dokumen_siswa/' . $filename, $encoded);
                    } else {
                        $file->storeAs('dokumen_siswa', $filename, 'public');
                    }

                    $dokumenYangDiupdate[$field] = $filename;
                }
            }

            // 5. Update data siswa dengan dokumen (jika ada file yang diupload)
            if (!empty($dokumenYangDiupdate)) {
                $siswa->update($dokumenYangDiupdate);
            }

            // 6. Kembalikan data siswa yang sudah lengkap di akhir transaksi
            return $siswa;
        });
    }

    public function updateSiswa(Siswa $siswa, array $data)
    {
        return DB::transaction(function () use ($siswa, $data) {
            // 1. Proses Manajemen Berkas Dokumen (Upload Baru & Hapus Berkas Lama jika ada)
            $manager = new ImageManager(new Driver());
            $dokumenFields = ['foto_anak', 'akta_kelahiran', 'kartu_keluarga'];
            $dokumenYangDiupdate = [];

            foreach ($dokumenFields as $field) {
                // Cek apakah ada file baru yang diunggah untuk field ini
                if (isset($data[$field]) && $data[$field] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $data[$field];
                    $filename = time() . '_' . $field . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // HAPUS BERKAS LAMA DARI SERVER (Jika sebelumnya sudah pernah ada berkas)
                    if ($siswa->$field && Storage::disk('public')->exists('dokumen_siswa/' . $siswa->$field)) {
                        Storage::disk('public')->delete('dokumen_siswa/' . $siswa->$field);
                    }

                    // PROSES KOMPRESI BERKAS BARU
                    if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png'])) {
                        // Kompresi jika berkas berupa Gambar
                        $image = $manager->read($file);
                        $image->scale(width: 1200);
                        $encoded = $image->toJpeg(75);
                        Storage::disk('public')->put('dokumen_siswa/' . $filename, $encoded);
                    } else {
                        // Simpan langsung jika berkas berupa PDF
                        $file->storeAs('dokumen_siswa', $filename, 'public');
                    }

                    // Tampung nama berkas baru ke dalam array penampung
                    $dokumenYangDiupdate[$field] = $filename;
                }
            }

            // 2. Siapkan data teks utama untuk update data Siswa
            $updateDataSiswa = [
                'kelas_id' => $data['kelas_id'],
                'nis' => $data['nis'],
                'nama_siswa' => $data['nama_siswa'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'nominal_spp' => $data['nominal_spp'],
                'alamat' => $data['alamat'] ?? null,
            ];

            // Gabungkan data teks dengan nama file dokumen baru (jika ada dokumen yang diunggah)
            if (!empty($dokumenYangDiupdate)) {
                $updateDataSiswa = array_merge($updateDataSiswa, $dokumenYangDiupdate);
            }

            // Eksekusi update data Siswa ke database
            $siswa->update($updateDataSiswa);

            // 3. Update data Orang Tua & Akun Login yang terikat
            $orangTua = $siswa->orangTua;
            if ($orangTua) {
                $orangTua->update([
                    'nama_wali' => $data['nama_wali'],
                    'no_hp' => $data['no_hp_ortu']
                ]);

                if ($orangTua->user) {
                    $orangTua->user->update([
                        'name' => $data['nama_wali'],
                        'email' => $data['email_ortu']
                    ]);
                }
            }

            return $siswa;
        });
    }
}
