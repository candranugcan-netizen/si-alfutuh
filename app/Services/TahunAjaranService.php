<?php

namespace App\Services;

use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class TahunAjaranService
{
    public function createTahunAjaran(array $data)
    {
        return DB::transaction(function () use ($data) {
            $isActive = isset($data['is_active']) && $data['is_active'] == '1';

            if ($isActive) {
                // Nonaktifkan semua tahun ajaran lain
                TahunAjaran::query()->update(['is_active' => false]);
            }

            return TahunAjaran::create([
                'nama' => $data['nama'],
                'semester' => $data['semester'],
                'is_active' => $isActive
            ]);
        });
    }

    public function updateTahunAjaran(TahunAjaran $tahunAjaran, array $data)
    {
        return DB::transaction(function () use ($tahunAjaran, $data) {
            $isActive = isset($data['is_active']) && $data['is_active'] == '1';

            if ($isActive) {
                TahunAjaran::where('id', '!=', $tahunAjaran->id)->update(['is_active' => false]);
            }

            return $tahunAjaran->update([
                'nama' => $data['nama'],
                'semester' => $data['semester'],
                'is_active' => $isActive
            ]);
        });
    }
}
