<?php

namespace App\Policies;

use App\Models\Guru;
use App\Models\User;

class GuruPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru', 'Bendahara', 'Tata Usaha']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }

    public function update(User $user, Guru $guru): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }

    public function delete(User $user, Guru $guru): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }
}
