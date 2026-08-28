<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeuanganKomite extends Model
{
    use HasFactory;

    protected $fillable = [
        'komite_id',
        'tanggal',
        'jenis_transaksi',
        'kategori',
        'nominal',
        'keterangan',
        'bukti_nota',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    public function komite(): BelongsTo
    {
        return $this->belongsTo(Komite::class);
    }
}
