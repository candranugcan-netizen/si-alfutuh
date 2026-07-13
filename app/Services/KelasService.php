<?php

namespace App\Services;

use App\Models\Kelas;

class KelasService
{
    public function createKelas(array $data)
    {
        return Kelas::create($data);
    }

    public function updateKelas(Kelas $kelas, array $data)
    {
        $kelas->update($data);
        return $kelas;
    }
}
