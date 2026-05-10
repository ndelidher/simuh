<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DataIndikator extends Model
{
    protected $table = 'data_indikator';
    protected $fillable = [
        'masjid_id', 'indikator_id', 'tahun', 'bulan', 'nilai',
        'keterangan', 'input_oleh', 'update_oleh', 'status',
        'terkirim_at', 'diverifikasi_at', 'diverifikasi_oleh',
    ];
    protected $casts = [
        'nilai'           => 'float',
        'terkirim_at'     => 'datetime',
        'diverifikasi_at' => 'datetime',
    ];

    public function masjid()    { return $this->belongsTo(Masjid::class); }
    public function indikator() { return $this->belongsTo(Indikator::class); }
    public function inputOleh() { return $this->belongsTo(User::class, 'input_oleh'); }
    public function updateOleh(){ return $this->belongsTo(User::class, 'update_oleh'); }
}