<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati jika data inti kosong
        if (empty($row['nama_siswa']) || empty($row['id_kelas']) || empty($row['email_ortu'])) {
            return null;
        }

        return DB::transaction(function () use ($row) {
            // 1. Ambil atau Buat Akun User untuk Orang Tua
            $user = User::where('email', $row['email_ortu'])->first();
            if (!$user) {
                $user = User::create([
                    'name'     => $row['nama_wali'], // Key sudah disesuaikan
                    'email'    => $row['email_ortu'],
                    'password' => Hash::make('ortu123'),
                ]);
                $user->assignRole('Orang Tua');
            }

            // 2. Ambil atau Buat Profil Orang Tua
            $orangTua = OrangTua::where('user_id', $user->id)->first();
            if (!$orangTua) {
                $orangTua = OrangTua::create([
                    'user_id'   => $user->id,
                    'nama_wali' => $row['nama_wali'], // Key sudah disesuaikan
                    'no_hp'     => $row['no_hp_ortu'] ?? null
                ]);
            }

            // 3. Penanganan format tanggal lahir yang aman dari error
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                if (is_numeric($row['tanggal_lahir'])) {
                    $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
                } else {
                    $tanggalLahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            }

            // 4. Daftarkan Data Siswa (beserta nominal_spp)
            return new Siswa([
                'kelas_id'      => $row['id_kelas'],
                'orang_tua_id'  => $orangTua->id,
                'nis'           => $row['nis'] ?? null,
                'nama_siswa'    => $row['nama_siswa'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tempat_lahir'  => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $tanggalLahir,
                'nominal_spp'   => $row['nominal_spp'] ?? 0, // <-- Input dari file excel
            ]);
        });
    }
}



