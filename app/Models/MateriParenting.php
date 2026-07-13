<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriParenting extends Model
{
    use HasFactory;

    protected $table = 'materi_parenting';
    protected $guarded = ['id'];
}
