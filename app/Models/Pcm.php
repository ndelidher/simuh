<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pcm extends Model
{
    protected $table = 'pcm';
    protected $fillable = ['pdm_id', 'kode', 'nama', 'kecamatan', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function pdm()     { return $this->belongsTo(Pdm::class); }
    public function prmList() { return $this->hasMany(Prm::class); }
    public function users()   { return $this->hasMany(User::class); }
}