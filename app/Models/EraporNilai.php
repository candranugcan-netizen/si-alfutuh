<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EraporNilai extends Model
{
    use HasFactory;

    protected $table = 'erapor_nilai';

    protected $fillable = [
        'erapor_id',
        'kategori_kode',
        'kategori_nama',
        'sub_kategori',
        'kode',
        'indikator',
        'nilai',
        'is_selected',
        'urutan',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    public function erapor(): BelongsTo
    {
        return $this->belongsTo(Erapor::class, 'erapor_id');
    }
}
