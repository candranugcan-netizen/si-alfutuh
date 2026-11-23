<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
