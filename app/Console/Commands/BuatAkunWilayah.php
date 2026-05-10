<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Pwm;
use App\Models\Pdm;
use App\Models\Pcm;
use App\Models\Prm;
use App\Models\User;

class BuatAkunWilayah extends Command
{
    protected $signature   = 'simuh:buat-akun-wilayah';
    protected $description = 'Buat akun admin standar untuk semua PWM, PDM, PCM, dan PRM';

    public function handle(): void
    {
        $password = Hash::make('Simuh@2024');
        $total    = 0;
        $skip     = 0;

        // ─── PWM ─────────────────────────────────────────────────
        $this->info('Membuat akun Admin PWM...');
        foreach (Pwm::where('aktif', true)->get() as $pwm) {
            $username = 'pwm_' . strtolower(preg_replace('/[^A-Za-z0-9]/', '', $pwm->kode));
            $email    = strtolower(preg_replace('/[^A-Za-z0-9]/', '.', $pwm->kode)) . '@simuh.id';

            if (User::where('username', $username)->exists()) {
                $skip++;
                continue;
            }

            User::create([
                'name'     => 'Admin ' . $pwm->nama,
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'role'     => 'admin_pwm',
                'pwm_id'   => $pwm->id,
                'aktif'    => true,
            ]);
            $total++;
            $this->line("  ✓ {$username} — {$pwm->nama}");
        }

        // ─── PDM ─────────────────────────────────────────────────
        $this->info('Membuat akun Admin PDM...');
        foreach (Pdm::where('aktif', true)->get() as $pdm) {
            $username = 'pdm_' . strtolower(preg_replace('/[^A-Za-z0-9]/', '', $pdm->kode));
            $email    = strtolower(preg_replace('/[^A-Za-z0-9]/', '.', $pdm->kode)) . '@simuh.id';

            if (User::where('username', $username)->exists()) {
                $skip++;
                continue;
            }

            User::create([
                'name'     => 'Admin ' . $pdm->nama,
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'role'     => 'admin_pdm',
                'pdm_id'   => $pdm->id,
                'aktif'    => true,
            ]);
            $total++;
            $this->line("  ✓ {$username} — {$pdm->nama}");
        }

        // ─── PCM ─────────────────────────────────────────────────
        $this->info('Membuat akun Admin PCM...');
        foreach (Pcm::where('aktif', true)->get() as $pcm) {
            $username = 'pcm_' . strtolower(preg_replace('/[^A-Za-z0-9]/', '', $pcm->kode));
            $email    = strtolower(preg_replace('/[^A-Za-z0-9]/', '.', $pcm->kode)) . '@simuh.id';

            if (User::where('username', $username)->exists()) {
                $skip++;
                continue;
            }

            User::create([
                'name'     => 'Admin ' . $pcm->nama,
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'role'     => 'admin_pcm',
                'pcm_id'   => $pcm->id,
                'aktif'    => true,
            ]);
            $total++;
            $this->line("  ✓ {$username} — {$pcm->nama}");
        }

        // ─── PRM ─────────────────────────────────────────────────
        $this->info('Membuat akun Admin PRM...');
        foreach (Prm::where('aktif', true)->get() as $prm) {
            $username = 'prm_' . strtolower(preg_replace('/[^A-Za-z0-9]/', '', $prm->kode));
            $email    = strtolower(preg_replace('/[^A-Za-z0-9]/', '.', $prm->kode)) . '@simuh.id';

            if (User::where('username', $username)->exists()) {
                $skip++;
                continue;
            }

            User::create([
                'name'     => 'Admin ' . $prm->nama,
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'role'     => 'admin_prm',
                'prm_id'   => $prm->id,
                'aktif'    => true,
            ]);
            $total++;
        }

        $this->newLine();
        $this->info("Selesai! {$total} akun berhasil dibuat, {$skip} akun dilewati (sudah ada).");
        $this->info("Password default semua akun: Simuh@2024");
        $this->warn("Ingatkan setiap admin untuk segera mengganti password setelah login pertama!");
    }
}