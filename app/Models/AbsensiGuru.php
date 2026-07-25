<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara spesifik
    protected $table = 'absensi_guru';

    // Mengizinkan semua kolom diisi secara massal (kecuali ID)
    protected $guarded = ['id'];

    /**
     * Relasi ke tabel User
     * Setiap record absensi dimiliki oleh satu user (Guru/Pegawai)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
