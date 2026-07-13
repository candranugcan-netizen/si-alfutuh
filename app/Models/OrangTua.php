<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    use HasFactory;
    protected $table = 'orang_tua';
    protected $fillable = ['user_id', 'nama_wali', 'no_hp'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function siswa(): HasMany { return $this->hasMany(Siswa::class, 'orang_tua_id'); }
}
