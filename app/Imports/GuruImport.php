<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati jika baris nama_guru atau email kosong
        if (empty($row['nama_guru']) || empty($row['email_login'])) {
            return null;
        }

        return DB::transaction(function () use ($row) {
            // Cek jika email sudah punya akun users
            $user = User::where('email', $row['email_login'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $row['nama_guru'],
                    'email' => $row['email_login'],
                    'password' => Hash::make('password123'),
                ]);
                $user->assignRole('Guru');
            }

            return new Guru([
                'user_id'       => $user->id,
                'nik'           => $row['nik'],
                'nama_guru'     => $row['nama_guru'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'no_hp'         => $row['no_hp'],
                'alamat'        => $row['alamat'],
            ]);
    });
    }
}
