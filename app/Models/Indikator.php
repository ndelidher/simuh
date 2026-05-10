<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    protected $table = 'indikator';
    protected $fillable = ['kode', 'nama', 'kelompok', 'satuan', 'deskripsi', 'urutan', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function dataIndikator() { return $this->hasMany(DataIndikator::class); }
}