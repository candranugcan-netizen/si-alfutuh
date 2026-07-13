<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'ppdb_pendaftaran';

    // Mengizinkan semua kolom diisi selain ID
    protected $guarded = ['id'];
}
