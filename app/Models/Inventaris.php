<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model {
    use HasFactory;
    protected $table = 'inventaris';
    protected $guarded = ['id'];

    public function kategori() {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function ruangan() {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function riwayatPeminjaman() {
        return $this->hasMany(PeminjamanBarang::class, 'inventaris_id');
    }

    public function riwayatPerubahan() {
        return $this->hasMany(RiwayatInventaris::class, 'inventaris_id')->latest();
    }
}
