<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
        'pwm_id', 'pdm_id', 'pcm_id', 'prm_id', 'masjid_id',
        'telepon', 'aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'aktif'         => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function pwm()    { return $this->belongsTo(Pwm::class); }
    public function pdm()    { return $this->belongsTo(Pdm::class); }
    public function pcm()    { return $this->belongsTo(Pcm::class); }
    public function prm()    { return $this->belongsTo(Prm::class); }
    public function masjid() { return $this->belongsTo(Masjid::class); }

    public function isSuperAdmin(): bool  { return $this->role === 'super_admin'; }
    public function isAdminPP(): bool     { return $this->role === 'admin_pp'; }
    public function isAdminPWM(): bool    { return $this->role === 'admin_pwm'; }
    public function isAdminPDM(): bool    { return $this->role === 'admin_pdm'; }
    public function isAdminPCM(): bool    { return $this->role === 'admin_pcm'; }
    public function isAdminPRM(): bool    { return $this->role === 'admin_prm'; }
    public function isAdminMasjid(): bool { return $this->role === 'admin_masjid'; }

    public function hasFullNationalAccess(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_pp']);
    }

    public function accessibleMasjidQuery()
    {
        $q = Masjid::query()->where('aktif', true);
        return match ($this->role) {
            'super_admin', 'admin_pp' => $q,
            'admin_pwm'   => $q->whereHas('prm.pcm.pdm', fn($x) => $x->where('pwm_id', $this->pwm_id)),
            'admin_pdm'   => $q->whereHas('prm.pcm', fn($x) => $x->where('pdm_id', $this->pdm_id)),
            'admin_pcm'   => $q->whereHas('prm', fn($x) => $x->where('pcm_id', $this->pcm_id)),
            'admin_prm'   => $q->where('prm_id', $this->prm_id),
            'admin_masjid'=> $q->where('id', $this->masjid_id),
            default       => $q->whereRaw('1=0'),
        };
    }

    public function canManageMasjid(Masjid $masjid): bool
    {
        if ($this->isSuperAdmin()) return true;
        if ($this->isAdminPP())   return false;
        return $this->accessibleMasjidQuery()->where('id', $masjid->id)->exists();
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin'  => 'Super Admin',
            'admin_pp'     => 'Admin PP',
            'admin_pwm'    => 'Admin PWM',
            'admin_pdm'    => 'Admin PDM',
            'admin_pcm'    => 'Admin PCM',
            'admin_prm'    => 'Admin PRM',
            'admin_masjid' => 'Admin Masjid',
            default        => '-',
        };
    }
}