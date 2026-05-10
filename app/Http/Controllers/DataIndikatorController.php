<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Masjid;
use App\Models\Indikator;
use App\Models\DataIndikator;

class DataIndikatorController extends Controller
{
    public function input(Request $request, Masjid $masjid)
    {
        $user = Auth::user();
        abort_unless($user->canManageMasjid($masjid) || $user->isAdminPP() || $user->isSuperAdmin(), 403);

        $bulan = (int) $request->get('bulan', date('n'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $indikators    = Indikator::where('aktif', true)->orderBy('urutan')->get();
        $dataExisting  = DataIndikator::where('masjid_id', $masjid->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $sudahKirim = $dataExisting->where('status', 'terkirim')->count() > 0;
        $adaDraft   = $dataExisting->where('status', 'draft')->count() > 0;

        return view('indikator.input', compact(
            'masjid', 'indikators', 'dataExisting',
            'bulan', 'tahun', 'sudahKirim', 'adaDraft'
        ));
    }

    public function simpan(Request $request, Masjid $masjid)
    {
        $user = Auth::user();
        abort_unless($user->canManageMasjid($masjid) || $user->isAdminPP() || $user->isSuperAdmin(), 403);

        $bulan  = (int) $request->get('bulan', date('n'));
        $tahun  = (int) $request->get('tahun', date('Y'));
        $status = $request->input('status', 'draft'); // 'draft' atau 'terkirim'

        // Kalau request edit ulang (reset dari terkirim ke draft)
        if ($request->has('_reset')) {
            DataIndikator::where('masjid_id', $masjid->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->update(['status' => 'draft']);
            return redirect()->route('indikator.input', [$masjid, 'bulan' => $bulan, 'tahun' => $tahun])
                ->with('info', 'Data dikembalikan ke draft, silakan edit ulang.');
        }

        // Cek apakah sudah terkirim — tidak boleh overwrite
        $sudahKirim = DataIndikator::where('masjid_id', $masjid->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'terkirim')
            ->exists();

        if ($sudahKirim) {
            return back()->with('error', 'Data bulan ini sudah terkirim dan tidak dapat diubah.');
        }

        $indikatorData = $request->input('indikator', []);
        $catatan       = $request->input('catatan', '');

        foreach ($indikatorData as $indikatorId => $nilai) {
            if ($nilai === null || $nilai === '') continue;

            DataIndikator::updateOrCreate(
                [
                    'masjid_id'    => $masjid->id,
                    'indikator_id' => $indikatorId,
                    'bulan'        => $bulan,
                    'tahun'        => $tahun,
                ],
                [
                    'nilai'        => (float) $nilai,
                    'status'       => $status,
                    'catatan'      => $catatan,
                    'input_oleh'   => $user->id,
                ]
            );
        }

        $pesan = $status === 'terkirim'
            ? 'Data indikator berhasil dikirim!'
            : 'Draft tersimpan.';

        return redirect()->route('indikator.input', [$masjid, 'bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', $pesan);
    }

    public function rekapLaporan(\Illuminate\Http\Request $request)
    {
        $user   = Auth::user();
        $bulan  = (int) $request->get('bulan', date('n'));
        $tahun  = (int) $request->get('tahun', date('Y'));
        $filter = $request->get('status', 'semua'); // semua, sudah, belum

        $query = $user->accessibleMasjidQuery()
            ->with(['prm.pcm.pdm.pwm']);

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        $masjids = $query->orderBy('nama')->get();

        // Ambil masjid yang sudah laporan
        $sudahIds = \App\Models\DataIndikator::whereIn('masjid_id', $masjids->pluck('id'))
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'terkirim')
            ->distinct('masjid_id')
            ->pluck('masjid_id')
            ->toArray();

        // Gabungkan status ke collection
        $masjids = $masjids->map(function($m) use ($sudahIds) {
            $m->sudah_laporan = in_array($m->id, $sudahIds);
            return $m;
        });

        // Filter status
        if ($filter === 'sudah') {
            $masjids = $masjids->where('sudah_laporan', true);
        } elseif ($filter === 'belum') {
            $masjids = $masjids->where('sudah_laporan', false);
        }

        $totalSudah = collect($masjids)->where('sudah_laporan', true)->count();
        $totalBelum = collect($masjids)->where('sudah_laporan', false)->count();

        return view('indikator.rekap_laporan', compact(
            'masjids', 'bulan', 'tahun', 'filter',
            'totalSudah', 'totalBelum', 'user'
        ));
    }

    public function belumLaporan(\Illuminate\Http\Request $request)
    {
        $user  = Auth::user();
        $bulan = (int) $request->get('bulan', date('n'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $sudahIds = \App\Models\DataIndikator::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'terkirim')
            ->distinct('masjid_id')
            ->pluck('masjid_id');

        return $user->accessibleMasjidQuery()
            ->whereNotIn('id', $sudahIds)
            ->orderBy('nama')
            ->limit(10)
            ->get(['id', 'nama']);
    }

    public function rekap(Request $request)
    {
        $user  = Auth::user();
        $tahun = (int) $request->get('tahun', date('Y'));

        $masjids = $user->accessibleMasjidQuery()
            ->with(['dataIndikator' => fn($q) => $q->where('tahun', $tahun)->where('status','terkirim')->with('indikator')])
            ->orderBy('nama')
            ->get();

        $masjidList = $masjids; // alias untuk kompatibilitas view
        $indikators = Indikator::where('aktif', true)->orderBy('urutan')->get();

        return view('indikator.rekap', compact('masjids', 'masjidList', 'indikators', 'tahun'));
    }
}