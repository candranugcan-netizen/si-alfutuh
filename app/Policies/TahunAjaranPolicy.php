<?php

namespace App\Policies;

use App\Models\TahunAjaran;
use App\Models\User;

class TahunAjaranPolicy
{
    // Siapa saja yang bisa melihat daftar tahun ajaran
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru', 'Bendahara', 'Tata Usaha']);
    }

    // Hanya Super Admin & Kepala Sekolah yang bisa menambah, mengubah, dan menghapus
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }

    public function update(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }

    public function delete(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah']);
    }
}
