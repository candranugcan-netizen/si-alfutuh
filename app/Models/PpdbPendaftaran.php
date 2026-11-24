<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PpdbPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'ppdb_pendaftaran';

    // Mengizinkan semua kolom diisi selain ID
    protected $guarded = ['id'];

    /**
     * Dapatkan URL file dokumen PPDB atau Siswa
     */
    public function getFileUrl($field)
    {
        $filename = $this->$field;
        if (!$filename) {
            return null;
        }

        // 1. Cek penyimpanan resmi data siswa (dokumen_siswa) jika sudah diterima
        $siswaDiskPath          = Storage::disk('public')->path('dokumen_siswa/' . $filename);
        $siswaPublicStoragePath = public_path('storage/dokumen_siswa/' . $filename);
        $siswaStoragePath       = storage_path('dokumen_siswa/' . $filename);
        $siswaPublicPath        = public_path('dokumen_siswa/' . $filename);

        if (file_exists($siswaDiskPath) || file_exists($siswaPublicStoragePath) || file_exists($siswaStoragePath) || file_exists($siswaPublicPath)) {
            return asset('storage/dokumen_siswa/' . $filename);
        }

        // 2. Cek penyimpanan PPDB di public/storage/uploads/ppdb (storage disk public)
        $ppdbDiskPath          = Storage::disk('public')->path('uploads/ppdb/' . $filename);
        $ppdbPublicStoragePath = public_path('storage/uploads/ppdb/' . $filename);

        if (file_exists($ppdbDiskPath) || file_exists($ppdbPublicStoragePath)) {
            return asset('storage/uploads/ppdb/' . $filename);
        }

        // 3. Cek penyimpanan PPDB di public/uploads/ppdb
        $ppdbPublicPath = public_path('uploads/ppdb/' . $filename);
        if (file_exists($ppdbPublicPath)) {
            return asset('uploads/ppdb/' . $filename);
        }

        // 4. Fallback URL berdasarkan status pendaftaran
        if ($this->status === 'Diterima') {
            return asset('storage/dokumen_siswa/' . $filename);
        }

        return asset('storage/uploads/ppdb/' . $filename);
    }

    public function getFotoUrlAttribute()
    {
        return $this->getFileUrl('file_foto');
    }

    public function getKkUrlAttribute()
    {
        return $this->getFileUrl('file_kk');
    }

    public function getAktaUrlAttribute()
    {
        return $this->getFileUrl('file_akta');
    }
}
