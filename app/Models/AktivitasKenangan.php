<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasKenangan extends Model
{
    protected $table = 'aktivitas_kenangan';
    protected $fillable = ['siswa_id', 'tahun_ajaran_id', 'semester', 'foto_aktivitas', 'judul_aktivitas', 'narasi_perkembangan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
