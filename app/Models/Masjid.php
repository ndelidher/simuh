<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Masjid extends Model
{
    use SoftDeletes;

    protected $table = 'masjid';
    protected $fillable = [
        'prm_id', 'kode', 'nama', 'alamat', 'kelurahan', 'kecamatan',
        'kota_kabupaten', 'provinsi', 'kode_pos', 'latitude', 'longitude',
        'telepon', 'email', 'website', 'tahun_berdiri', 'luas_tanah',
        'luas_bangunan', 'kapasitas_jamaah', 'status_tanah', 'foto',
        'kategori_unggulan', 'tanggal_penetapan', 'aktif',
    ];
    protected $casts = [
        'aktif'             => 'boolean',
        'tanggal_penetapan' => 'date',
        'latitude'          => 'float',
        'longitude'         => 'float',
    ];

    public function prm()           { return $this->belongsTo(Prm::class); }
    public function users()         { return $this->hasMany(User::class); }
    public function dataIndikator() { return $this->hasMany(DataIndikator::class); }
    public function pcm()           { return $this->hasOneThrough(Pcm::class, Prm::class, 'id', 'id', 'prm_id', 'pcm_id'); }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori_unggulan) {
            'MU_WILAYAH' => 'MU Wilayah (PWM)',
            'MU_DAERAH'  => 'MU Daerah (PDM)',
            'MU_CABANG'  => 'MU Cabang (PCM)',
            'MU_RANTING' => 'MU Ranting (PRM)',
            default      => '-',
        };
    }
}