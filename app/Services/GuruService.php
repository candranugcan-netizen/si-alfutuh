<?php

namespace App\Services;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruService
{
    public function createGuru(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat Akun User Terlebih Dahulu
            $user = User::create([
                'name' => $data['nama_guru'],
                'email' => $data['email'],
                'password' => Hash::make('password123'), // Password default guru baru
            ]);

            // 2. Berikan Role Guru dari Spatie
            $user->assignRole('Guru');

            // 3. Buat Profil Gurunya
            return Guru::create([
                'user_id' => $user->id,
                'nik' => $data['nik'],
                'nama_guru' => $data['nama_guru'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
            ]);
        });
    }

    public function updateGuru(Guru $guru, array $data)
    {
        return DB::transaction(function () use ($guru, $data) {
            // 1. Update data akun loginnya
            $guru->user->update([
                'name' => $data['nama_guru'],
                'email' => $data['email']
            ]);

            // 2. Update data profil gurunya
            $guru->update([
                'nik' => $data['nik'],
                'nama_guru' => $data['nama_guru'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
            ]);

            return $guru;
        });
    }
}
