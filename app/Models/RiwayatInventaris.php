<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatInventaris extends Model
{
    use HasFactory;

    protected $table = 'riwayat_inventaris';
    protected $guarded = ['id'];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }
}
