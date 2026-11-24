<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'kelas_id', 'orang_tua_id', 'nominal_spp', 'nis', 'nama_siswa', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'foto_anak',
        'akta_kelahiran', 'kartu_keluarga', 'status_siswa', 'tahun_masuk',
        'agama', 'anak_ke', 'alamat', 'nama_ayah', 'nama_ibu', 'agama_ayah', 'agama_ibu'
    ];

public function orangTua(): BelongsTo
{
    return $this->belongsTo(OrangTua::class, 'orang_tua_id');
}

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function transaksi(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class, 'siswa_id');
    }

    /**
     * Relasi ke data pelacakan Iqro / Al-Quran
     */
    public function perkembanganAlquran()
    {
        return $this->hasMany(PerkembanganAlquran::class, 'siswa_id')->latest('tanggal');
    }

    /**
     * Relasi ke data pelacakan Sempoa AISMA
     */
    public function perkembanganAisma()
    {
        return $this->hasMany(PerkembanganAisma::class, 'siswa_id')->latest('tanggal');
    }

    /**
     * Relasi ke galeri foto dan narasi kenangan sekolah
     */
    public function aktivitasKenangan()
    {
        return $this->hasMany(AktivitasKenangan::class, 'siswa_id')->latest();
    }

    public function erapor()
    {
        return $this->hasMany(Erapor::class, 'siswa_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id')->latest();
    }

    /**
     * Dapatkan URL file dokumen Siswa
     */
    public function getFileUrl($field)
    {
        $filename = $this->$field;
        if (!$filename) {
            return null;
        }

        // 1. Cek penyimpanan dokumen_siswa (storage disk public)
        $diskPath = Storage::disk('public')->path('dokumen_siswa/' . $filename);
        $publicStoragePath = public_path('storage/dokumen_siswa/' . $filename);
        $storagePath = storage_path('dokumen_siswa/' . $filename);
        $publicPath = public_path('dokumen_siswa/' . $filename);

        if (file_exists($diskPath) || file_exists($publicStoragePath) || file_exists($storagePath) || file_exists($publicPath)) {
            return asset('storage/dokumen_siswa/' . $filename);
        }

        // 2. Cek penyimpanan PPDB (jika file berasal dari PPDB yang masih di uploads/ppdb)
        $ppdbDiskPath = Storage::disk('public')->path('uploads/ppdb/' . $filename);
        $ppdbPublicStoragePath = public_path('storage/uploads/ppdb/' . $filename);
        $ppdbPublicPath = public_path('uploads/ppdb/' . $filename);

        if (file_exists($ppdbDiskPath) || file_exists($ppdbPublicStoragePath)) {
            return asset('storage/uploads/ppdb/' . $filename);
        }
        if (file_exists($ppdbPublicPath)) {
            return asset('uploads/ppdb/' . $filename);
        }

        return asset('storage/dokumen_siswa/' . $filename);
    }

    public function getFotoUrlAttribute()
    {
        return $this->getFileUrl('foto_anak');
    }

    public function getAktaUrlAttribute()
    {
        return $this->getFileUrl('akta_kelahiran');
    }

    public function getKkUrlAttribute()
    {
        return $this->getFileUrl('kartu_keluarga');
    }
}
