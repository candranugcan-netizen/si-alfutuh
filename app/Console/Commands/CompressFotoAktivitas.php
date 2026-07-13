<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CompressFotoAktivitas extends Command
{
    // Nama perintah untuk dieksekusi di terminal
    protected $signature = 'image:compress-aktivitas';

    // Deskripsi perintah
    protected $description = 'Melakukan kompresi massal pada foto aktivitas perkembangan anak agar ukuran file kecil namun tetap jernih';

    public function handle()
    {
        $this->info('Mempersiapkan mesin kompresi...');

        // ==============================================================
        // TENTUKAN LOKASI FOLDER FOTO ANDA DI SINI
        // Jika foto di public/uploads/aktivitas: public_path('uploads/aktivitas')
        // Jika foto di storage/app/public/aktivitas: storage_path('app/public/aktivitas')
        // ==============================================================
        $folderPath = public_path('storage/aktivitas_anak/uploads'); // <-- SESUAIKAN DENGAN FOLDER ANDA

        if (!File::exists($folderPath)) {
            $this->error("Folder tidak ditemukan: {$folderPath}");
            return;
        }

        // Ambil semua file di dalam folder tersebut
        $files = File::files($folderPath);
        $totalFiles = count($files);

        if ($totalFiles === 0) {
            $this->warn("Tidak ada file yang ditemukan di dalam folder {$folderPath}.");
            return;
        }

        // Siapkan Progress Bar agar terlihat keren di terminal
        $bar = $this->output->createProgressBar($totalFiles);
        $bar->start();

        // Panggil ImageManager (Intervention Image v3)
        $manager = new ImageManager(new Driver());

        $berhasil = 0;
        $gagal = 0;

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            // Hanya proses file gambar
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                try {
                    $pathAsli = $file->getPathname();
                    $ukuranAwal = filesize($pathAsli);

                    // Hanya kompres jika ukuran file di atas 300 KB (opsional, agar file yang sudah kecil tidak dikompres lagi)
                    if ($ukuranAwal > 300000) {

                        // Baca gambar
                        $image = $manager->read($pathAsli);

                        // 1. RESIZE: Batasi lebar maksimal jadi 1080px (standar HD/Instagram). Tinggi akan menyesuaikan otomatis.
                        // Fungsi scaleDown() memastikan gambar yang lebih kecil dari 1080px tidak akan dipaksa membesar.
                        $image->scaleDown(width: 1080);

                        // 2. KOMPRESI: Simpan ulang dengan kualitas 75% (Mata manusia tidak bisa membedakan 75% vs 100%, tapi size turun drastis!)
                        $image->toJpeg(quality: 75)->save($pathAsli);

                        $berhasil++;
                    }

                } catch (\Exception $e) {
                    $gagal++;
                }
            }
            // Majukan progress bar
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("PROSES SELESAI!");
        $this->line("Berhasil dikompres: <fg=green>{$berhasil} foto</>");
        if ($gagal > 0) {
            $this->line("Gagal diproses (file rusak/bukan gambar asli): <fg=red>{$gagal} foto</>");
        }
    }
}
