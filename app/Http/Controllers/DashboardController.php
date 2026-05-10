<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Masjid;
use App\Models\Pwm;
use App\Models\Pdm;
use App\Models\Pcm;
use App\Models\Prm;
use App\Models\DataIndikator;
use App\Models\Indikator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Label 6 bulan terakhir ────────────────────────────────
        $bulanList  = [];
        $bulanLabel = [];
        $now        = \Carbon\Carbon::now();
        $namaBulan  = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

        for ($i = 5; $i >= 0; $i--) {
            $tgl          = $now->copy()->subMonths($i);
            $bulanList[]  = ['tahun' => $tgl->year, 'bulan' => $tgl->month];
            $bulanLabel[] = $namaBulan[$tgl->month] . ' ' . $tgl->year;
        }

        // ── Ambil masjid sesuai scope ─────────────────────────────
        $masjidQuery = $user->accessibleMasjidQuery();
        $masjidIds   = $masjidQuery->pluck('id');

        // ── Tren 6 bulan (7 indikator) ────────────────────────────
        $indikatorKodes = ['JAMAAH_1','JAMAAH_2','JARIYAH_1','JARIYAH_2','JAMIYAH_1','JAMIYAH_2','JAMIYAH_3'];
        $trenData = [];

        foreach ($indikatorKodes as $kode) {
            $trenData[$kode] = [];
            foreach ($bulanList as $bl) {
                $total = DataIndikator::whereIn('masjid_id', $masjidIds)
                    ->whereHas('indikator', fn($q) => $q->where('kode', $kode))
                    ->where('tahun', $bl['tahun'])
                    ->where('bulan', $bl['bulan'])
                    ->where('status', 'terkirim')
                    ->sum('nilai');
                $trenData[$kode][] = round((float)$total, 1);
            }
        }

        // ── Statistik umum ────────────────────────────────────────
        $stats = $this->getStats($user, $masjidIds);

        // ── Data khusus Admin Masjid ──────────────────────────────
        $masjidSaya     = null;
        $indikatorBulan = null;
        $pctIndikator   = null;

        if ($user->isAdminMasjid() && $user->masjid_id) {
            $masjidSaya = Masjid::find($user->masjid_id);
            $bulanIni   = date('n');
            $tahunIni   = date('Y');

            $indikators     = Indikator::where('aktif', true)->orderBy('urutan')->get();
            $dataIndikator  = DataIndikator::where('masjid_id', $user->masjid_id)
                ->where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->where('status', 'terkirim')
                ->with('indikator')
                ->get()
                ->keyBy('indikator.kode');

            $indikatorBulan = $indikators->map(function($ind) use ($dataIndikator) {
                $d = $dataIndikator[$ind->kode] ?? null;
                return [
                    'kode'     => $ind->kode,
                    'nama'     => $ind->nama,
                    'satuan'   => $ind->satuan,
                    'kelompok' => $ind->kelompok,
                    'nilai'    => $d ? $d->nilai : null,
                    'terisi'   => $d !== null,
                ];
            });

            $pctIndikator = $indikatorBulan->where('terisi', true)->count() . '/' . $indikatorBulan->count();
        }

        // ── Masjid belum laporan bulan ini (max 5 untuk widget) ──
        $belumLaporan = collect();
        if (!$user->isAdminMasjid()) {
            $sudahIds = DataIndikator::whereIn('masjid_id', $masjidIds)
                ->where('bulan', $now->month)
                ->where('tahun', $now->year)
                ->where('status', 'terkirim')
                ->distinct('masjid_id')
                ->pluck('masjid_id');

            $belumLaporan = $user->accessibleMasjidQuery()
                ->whereNotIn('id', $sudahIds)
                ->with(['prm.pcm'])
                ->orderBy('nama')
                ->limit(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'user', 'stats', 'trenData', 'bulanLabel',
            'masjidSaya', 'indikatorBulan', 'pctIndikator',
            'belumLaporan'
        ));
    }

    private function getStats($user, $masjidIds): array
    {
        $bulanIni = date('n');
        $tahunIni = date('Y');

        $totalMasjid   = $masjidIds->count();
        $masjidLaporan = DataIndikator::whereIn('masjid_id', $masjidIds)
            ->where('bulan', $bulanIni)->where('tahun', $tahunIni)
            ->where('status', 'terkirim')
            ->distinct('masjid_id')->count('masjid_id');

        $pctLaporan = $totalMasjid > 0 ? round($masjidLaporan / $totalMasjid * 100) : 0;

        // ── Masjid Unggulan per kategori (dari scope masjid user) ──
        $mu = Masjid::whereIn('id', $masjidIds)
            ->whereNotNull('kategori_unggulan')
            ->selectRaw('kategori_unggulan, count(*) as total')
            ->groupBy('kategori_unggulan')
            ->pluck('total', 'kategori_unggulan')
            ->toArray();

        // Kategori yang ditampilkan sesuai level
        $muKategori = $this->getMuKategori($user, $mu);

        $base = [
            'total_masjid'   => $totalMasjid,
            'masjid_laporan' => $masjidLaporan,
            'pct_laporan'    => $pctLaporan,
            'mu_kategori'    => $muKategori,
        ];

        if ($user->isSuperAdmin() || $user->isAdminPP()) {
            return array_merge($base, [
                'total_pwm'  => Pwm::where('aktif', true)->count(),
                'total_pdm'  => Pdm::where('aktif', true)->count(),
                'total_pcm'  => Pcm::where('aktif', true)->count(),
                'total_prm'  => Prm::where('aktif', true)->count(),
                'nama_scope' => 'Nasional',
            ]);
        }

        if ($user->isAdminPWM()) {
            return array_merge($base, [
                'total_pdm'  => Pdm::where('pwm_id', $user->pwm_id)->where('aktif', true)->count(),
                'total_pcm'  => Pcm::whereHas('pdm', fn($q) => $q->where('pwm_id', $user->pwm_id))->where('aktif', true)->count(),
                'total_prm'  => Prm::whereHas('pcm.pdm', fn($q) => $q->where('pwm_id', $user->pwm_id))->where('aktif', true)->count(),
                'nama_scope' => $user->pwm->nama ?? '',
            ]);
        }

        if ($user->isAdminPDM()) {
            return array_merge($base, [
                'total_pcm'  => Pcm::where('pdm_id', $user->pdm_id)->where('aktif', true)->count(),
                'total_prm'  => Prm::whereHas('pcm', fn($q) => $q->where('pdm_id', $user->pdm_id))->where('aktif', true)->count(),
                'nama_scope' => $user->pdm->nama ?? '',
            ]);
        }

        if ($user->isAdminPCM()) {
            return array_merge($base, [
                'total_prm'  => Prm::where('pcm_id', $user->pcm_id)->where('aktif', true)->count(),
                'nama_scope' => $user->pcm->nama ?? '',
            ]);
        }

        if ($user->isAdminPRM()) {
            return array_merge($base, [
                'nama_scope' => $user->prm->nama ?? '',
            ]);
        }

        return $base;
    }

    private function getMuKategori($user, array $mu): array
    {
        // Definisi semua kategori
        $semua = [
            'MU_WILAYAH' => ['label' => 'MU Wilayah (PWM)', 'bg' => '#EEEDFE', 'cl' => '#3C3489'],
            'MU_DAERAH'  => ['label' => 'MU Daerah (PDM)',  'bg' => '#E1F5EE', 'cl' => '#085041'],
            'MU_CABANG'  => ['label' => 'MU Cabang (PCM)',  'bg' => '#FAEEDA', 'cl' => '#633806'],
            'MU_RANTING' => ['label' => 'MU Ranting (PRM)', 'bg' => '#FAECE7', 'cl' => '#4A1B0C'],
        ];

        // Tentukan kategori yang ditampilkan sesuai level
        $tampilkan = match (true) {
            $user->isSuperAdmin() || $user->isAdminPP()
                => ['MU_WILAYAH','MU_DAERAH','MU_CABANG','MU_RANTING'],
            $user->isAdminPWM()
                => ['MU_DAERAH','MU_CABANG','MU_RANTING'],
            $user->isAdminPDM()
                => ['MU_CABANG','MU_RANTING'],
            $user->isAdminPCM() || $user->isAdminPRM()
                => ['MU_RANTING'],
            default => [],
        };

        $result = [];
        foreach ($tampilkan as $kode) {
            $result[] = array_merge($semua[$kode], [
                'kode'  => $kode,
                'total' => $mu[$kode] ?? 0,
            ]);
        }
        return $result;
    }
}