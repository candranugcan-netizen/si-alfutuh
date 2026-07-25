<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model {
    use HasFactory;
    protected $table = 'ruangan';
    protected $guarded = ['id'];

    public function inventaris() {
        return $this->hasMany(Inventaris::class, 'ruangan_id');
    }
}
