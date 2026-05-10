<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Prm extends Model
{
    protected $table = 'prm';
    protected $fillable = ['pcm_id', 'kode', 'nama', 'kelurahan', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function pcm()        { return $this->belongsTo(Pcm::class); }
    public function masjidList() { return $this->hasMany(Masjid::class, 'prm_id'); }
    public function users()      { return $this->hasMany(User::class); }
}