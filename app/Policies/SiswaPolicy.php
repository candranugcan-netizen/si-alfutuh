<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru', 'Bendahara', 'Tata Usaha']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Tata Usaha', 'Guru']);
    }

    public function update(User $user, Siswa $siswa): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Tata Usaha', 'Guru']);
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru']);
    }
}
