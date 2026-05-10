<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pwm extends Model
{
    protected $table = 'pwm';
    protected $fillable = ['kode', 'nama', 'provinsi', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function pdmList() { return $this->hasMany(Pdm::class); }
    public function users()   { return $this->hasMany(User::class); }
}