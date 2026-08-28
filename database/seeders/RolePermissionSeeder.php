<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Komite;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat daftar Role
        $roles = ['Super Admin', 'Kepala Sekolah', 'Guru', 'Bendahara', 'Tata Usaha', 'Orang Tua', 'Komite'];
        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        // 2. Membuat Akun Admin Utama
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@alfutuh.com'],
            [
                'name' => 'Admin RA Al Futuh 1',
                'password' => Hash::make('alfutuh123'),
            ]
        );
        $admin1->assignRole('Super Admin');

        // 3. Membuat Akun Admin Kedua
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@alfutuh.com'],
            [
                'name' => 'Admin RA Al Futuh 2',
                'password' => Hash::make('raksasa456'),
            ]
        );
        $admin2->assignRole('Super Admin');

        // 4. [BARU] Membuat Akun Komite Utama
        $userKomite = User::firstOrCreate(
            ['email' => 'komite@alfutuh.com'],
            [
                'name' => 'Pengurus Komite RA',
                'password' => Hash::make('komite123'),
            ]
        );
        $userKomite->assignRole('Komite');

        Komite::firstOrCreate(
            ['user_id' => $userKomite->id],
            [
                'nama_komite' => 'Pengurus Komite RA',
                'jabatan' => 'Bendahara Komite',
                'periode' => date('Y') . '/' . (date('Y') + 1),
            ]
        );
    }
}
