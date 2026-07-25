<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model {
    use HasFactory;
    protected $table = 'kategori_barang';
    protected $guarded = ['id'];

    public function inventaris() {
        return $this->hasMany(Inventaris::class, 'kategori_barang_id');
    }
}
