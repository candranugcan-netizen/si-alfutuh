<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat daftar Role
        $roles = ['Super Admin', 'Kepala Sekolah', 'Guru', 'Bendahara', 'Tata Usaha', 'Orang Tua'];
        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        // 2. Membuat Akun Admin Utama (Pertama) - Diubah ke firstOrCreate agar aman
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@alfutuh.com'], // Kunci pengecekan unik berdasarkan email
            [
                'name' => 'Admin RA Al Futuh 1',
                'password' => Hash::make('alfutuh123'), // Silakan sesuaikan password-nya
            ]
        );
        $admin1->assignRole('Super Admin');


        // 3. [BARU] Membuat Akun Admin Kedua
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@alfutuh.com'], // <-- Ganti dengan email admin kedua Anda
            [
                'name' => 'Admin RA Al Futuh 2', // <-- Ganti dengan nama admin kedua
                'password' => Hash::make('raksasa456'), // <-- Ganti dengan password admin kedua
            ]
        );

        // Menentukan hak akses/role untuk admin kedua
        // Anda bisa memilih dari daftar di atas, misal: 'Super Admin', 'Bendahara', atau 'Tata Usaha'
        $admin2->assignRole('Super Admin');
    }
}
