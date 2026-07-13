<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerkembanganAisma extends Model
{
    protected $table = 'perkembangan_aisma';
    protected $fillable = ['siswa_id', 'guru_id', 'tanggal', 'level_jilid', 'halaman_modul', 'catatan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
