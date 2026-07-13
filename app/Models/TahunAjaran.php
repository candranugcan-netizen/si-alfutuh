<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'semester',
        'is_active'
    ];

    // Otomatis mengubah nilai 1/0 di database menjadi true/false di Laravel
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
