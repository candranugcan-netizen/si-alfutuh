<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'no_transaksi', 'tanggal', 'jenis', 'kategori_transaksi_id',
        'siswa_id', 'untuk_bulan', 'user_id', 'nominal', 'keterangan'
    ];

    public function kategori() { return $this->belongsTo(KategoriTransaksi::class, 'kategori_transaksi_id'); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function pencatat() { return $this->belongsTo(User::class, 'user_id'); }
    public function kategoriTransaksi()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'kategori_transaksi_id');
    }
}
