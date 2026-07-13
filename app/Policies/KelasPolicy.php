<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru', 'Bendahara', 'Tata Usaha']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }
}
