<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menegaskan nama tabel agar Laravel tidak mencari tabel 'kelass'
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'kelompok',
        'kapasitas'
    ];

    public function siswa(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Siswa::class);
    }
}
