<?php

namespace App\Services;

use App\Models\PerkembanganAlquran;
use App\Models\PerkembanganAisma;
use App\Models\AktivitasKenangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AkademikService
{
    public function simpanAlquran(array $data)
    {
        $data['guru_id'] = auth()->id(); // Otomatis mencatat guru yang sedang login
        return PerkembanganAlquran::create($data);
    }

    public function simpanAisma(array $data)
    {
        $data['guru_id'] = auth()->id();
        return PerkembanganAisma::create($data);
    }

    public function simpanKenangan(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['foto_aktivitas'])) {
                $file = $data['foto_aktivitas'];

                // Beri nama unik dengan ekstensi .webp
                $filename = 'kenangan_' . time() . '_' . uniqid() . '.webp';

                // Inisialisasi Intervention Image v3 (Sama seperti di SiswaService Anda)
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);

                // Resizing ke lebar maksimal 1200px (tinggi otomatis proporsional)
                $image->scale(width: 1200);

                // Kompresi ke WebP kualitas 80% (Ringan namun tetap jernih tajam)
                $encoded = $image->toWebp(80);

                // Simpan ke storage public/aktivitas_anak
                Storage::disk('public')->put('aktivitas_anak/' . $filename, $encoded);

                // Ganti data input file menjadi string nama file untuk database
                $data['foto_aktivitas'] = $filename;
            }

            return AktivitasKenangan::create($data);
        });
    }


}
