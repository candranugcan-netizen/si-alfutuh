<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriTransaksi extends Model
{
    protected $table = 'kategori_transaksi';
    protected $fillable = ['nama_kategori', 'jenis'];

    public function transaksi() { return $this->hasMany(Transaksi::class); }
}
