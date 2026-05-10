<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pdm extends Model
{
    protected $table = 'pdm';
    protected $fillable = ['pwm_id', 'kode', 'nama', 'kota_kabupaten', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function pwm()     { return $this->belongsTo(Pwm::class); }
    public function pcmList() { return $this->hasMany(Pcm::class); }
    public function users()   { return $this->hasMany(User::class); }
}