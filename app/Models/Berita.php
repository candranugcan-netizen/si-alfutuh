<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $guarded = ['id'];

    // Relasi ke penulis (Admin)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
