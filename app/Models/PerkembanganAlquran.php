<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerkembanganAlquran extends Model
{
    protected $table = 'perkembangan_alquran';
    protected $fillable = ['siswa_id', 'guru_id', 'tanggal', 'jenis', 'jilid', 'halaman', 'status_kelancaran', 'catatan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
