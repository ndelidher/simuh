<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Masjid;
use App\Models\Prm;
use App\Models\Pcm;
use App\Models\Pdm;
use App\Models\Pwm;

class MasjidController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = $user->accessibleMasjidQuery()->with(['prm.pcm.pdm.pwm']);

        // Filter nama
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter kategori unggulan
        if ($request->filled('kategori')) {
            $query->where('kategori_unggulan', $request->kategori);
        }

        // Filter status
        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        // Filter wilayah cascading
        if ($request->filled('prm_id')) {
            $query->where('prm_id', $request->prm_id);
        } elseif ($request->filled('pcm_id')) {
            $query->whereHas('prm', fn($q) => $q->where('pcm_id', $request->pcm_id));
        } elseif ($request->filled('pdm_id')) {
            $query->whereHas('prm.pcm', fn($q) => $q->where('pdm_id', $request->pdm_id));
        } elseif ($request->filled('pwm_id')) {
            $query->whereHas('prm.pcm.pdm', fn($q) => $q->where('pwm_id', $request->pwm_id));
        }

        $masjid = $query->orderBy('nama')->paginate(20)->withQueryString();

        // Data untuk filter wilayah (superadmin/admin_pp)
        $pwmList = collect();
        $pdmList = collect();
        $pcmList = collect();
        $prmList = collect();

        if ($user->isSuperAdmin() || $user->isAdminPP()) {
            $pwmList = \App\Models\Pwm::orderBy('nama')->get(['id','nama']);
            if ($request->filled('pwm_id')) {
                $pdmList = \App\Models\Pdm::where('pwm_id', $request->pwm_id)->orderBy('nama')->get(['id','nama']);
            }
            if ($request->filled('pdm_id')) {
                $pcmList = \App\Models\Pcm::where('pdm_id', $request->pdm_id)->orderBy('nama')->get(['id','nama']);
            }
            if ($request->filled('pcm_id')) {
                $prmList = \App\Models\Prm::where('pcm_id', $request->pcm_id)->orderBy('nama')->get(['id','nama']);
            }
        }

        return view('masjid.index', compact('masjid', 'pwmList', 'pdmList', 'pcmList', 'prmList', 'user'));
    }

    public function create()
    {
        $user    = Auth::user();
        $pwmList = Pwm::where('aktif', true)->orderBy('nama')->get();

        // Tentukan scope wilayah otomatis sesuai role
        $scopePwm = null;
        $scopePdm = null;
        $scopePcm = null;
        $scopePrm = null;

        if ($user->isAdminPWM()) {
            $scopePwm = $user->pwm;
        } elseif ($user->isAdminPDM()) {
            $scopePdm = $user->pdm;
            $scopePwm = $user->pdm?->pwm;
        } elseif ($user->isAdminPCM()) {
            $scopePcm = $user->pcm;
            $scopePdm = $user->pcm?->pdm;
            $scopePwm = $user->pcm?->pdm?->pwm;
        } elseif ($user->isAdminPRM() || $user->isAdminMasjid()) {
            $scopePrm = $user->prm;
            $scopePcm = $user->prm?->pcm;
            $scopePdm = $user->prm?->pcm?->pdm;
            $scopePwm = $user->prm?->pcm?->pdm?->pwm;
        }

        return view('masjid.create', compact(
            'pwmList', 'user',
            'scopePwm', 'scopePdm', 'scopePcm', 'scopePrm'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if($user->isAdminPP(), 403);

        $data = $request->validate([
            'prm_id'            => 'required|exists:prm,id',
            'kode'              => 'required|string|max:20|unique:masjid,kode',
            'nama'              => 'required|string|max:150',
            'alamat'            => 'nullable|string',
            'kota_kabupaten'    => 'nullable|string|max:60',
            'provinsi'          => 'nullable|string|max:60',
            'nama_pengelola'    => 'nullable|string|max:100',
            'hp_pengelola'      => 'nullable|string|max:20',
            'email_pengelola'   => 'nullable|email|max:100',
            'website'           => 'nullable|url|max:150',
            'tahun_berdiri'     => 'nullable|integer|min:1800|max:' . date('Y'),
            'luas_tanah'        => 'nullable|integer|min:0',
            'luas_bangunan'     => 'nullable|integer|min:0',
            'kapasitas_jamaah'  => 'nullable|integer|min:0',
            'status_tanah'      => 'nullable|string|max:50',
            'kategori_unggulan' => 'nullable|in:MU_WILAYAH,MU_DAERAH,MU_CABANG,MU_RANTING',
            'tanggal_penetapan' => 'nullable|date',
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('masjid/foto', 'public');
        }

        Masjid::create($data);
        return redirect()->route('masjid.index')->with('success', 'Data masjid berhasil ditambahkan.');
    }

    public function show(Masjid $masjid)
    {
        $user = Auth::user();
        abort_unless($user->canManageMasjid($masjid) || $user->isAdminPP() || $user->isSuperAdmin(), 403);
        $masjid->load('prm.pcm.pdm.pwm');

        // Data tren 6 bulan terakhir
        $bulanList  = [];
        $bulanLabel = [];
        $now        = \Carbon\Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $tgl          = $now->copy()->subMonths($i);
            $bulanList[]  = ['tahun' => $tgl->year, 'bulan' => $tgl->month];
            $bulanLabel[] = $tgl->locale('id')->shortMonthName . ' ' . $tgl->year;
        }

        $indikatorKodes = ['JAMAAH_1','JAMAAH_2','JARIYAH_1','JARIYAH_2','JAMIYAH_1','JAMIYAH_2','JAMIYAH_3'];
        $trenData = [];

        foreach ($indikatorKodes as $kode) {
            $trenData[$kode] = [];
            foreach ($bulanList as $bl) {
                $d = $masjid->dataIndikator()
                    ->whereHas('indikator', fn($q) => $q->where('kode', $kode))
                    ->where('tahun', $bl['tahun'])
                    ->where('bulan', $bl['bulan'])
                    ->first();
                $trenData[$kode][] = $d ? round($d->nilai, 1) : 0;
            }
        }

        return view('masjid.show', compact('masjid', 'user', 'trenData', 'bulanLabel'));
    }

    public function edit(Masjid $masjid)
    {
        $user    = Auth::user();
        abort_unless($user->canManageMasjid($masjid), 403);
        $masjid->load('prm.pcm.pdm.pwm');
        $pwmList = Pwm::where('aktif', true)->orderBy('nama')->get();
        return view('masjid.edit', compact('masjid', 'pwmList', 'user'));
    }

    public function update(Request $request, Masjid $masjid)
    {
        $user = Auth::user();
        abort_unless($user->canManageMasjid($masjid), 403);

        $data = $request->validate([
            'nama'              => 'required|string|max:150',
            'alamat'            => 'nullable|string',
            'kota_kabupaten'    => 'nullable|string|max:60',
            'provinsi'          => 'nullable|string|max:60',
            'nama_pengelola'    => 'nullable|string|max:100',
            'hp_pengelola'      => 'nullable|string|max:20',
            'email_pengelola'   => 'nullable|email|max:100',
            'website'           => 'nullable|url|max:150',
            'tahun_berdiri'     => 'nullable|integer|min:1800|max:' . date('Y'),
            'luas_tanah'        => 'nullable|integer|min:0',
            'luas_bangunan'     => 'nullable|integer|min:0',
            'kapasitas_jamaah'  => 'nullable|integer|min:0',
            'status_tanah'      => 'nullable|string|max:50',
            'kategori_unggulan' => 'nullable|in:MU_WILAYAH,MU_DAERAH,MU_CABANG,MU_RANTING',
            'tanggal_penetapan' => 'nullable|date',
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($masjid->foto) Storage::disk('public')->delete($masjid->foto);
            $data['foto'] = $request->file('foto')->store('masjid/foto', 'public');
        }

        $masjid->update($data);
        return redirect()->route('masjid.show', $masjid)->with('success', 'Data masjid berhasil diperbarui.');
    }

    public function destroy(Masjid $masjid)
    {
        $user = Auth::user();
        abort_unless($user->canManageMasjid($masjid), 403);
        $masjid->delete();
        return redirect()->route('masjid.index')->with('success', 'Data masjid berhasil dihapus.');
    }

    private function getPrmOptions($user): \Illuminate\Database\Eloquent\Collection
    {
        return match ($user->role) {
            'super_admin', 'admin_pp' => Prm::with('pcm.pdm.pwm')->where('aktif', true)->orderBy('nama')->get(),
            'admin_pwm'  => Prm::whereHas('pcm.pdm', fn($q) => $q->where('pwm_id', $user->pwm_id))->where('aktif', true)->orderBy('nama')->get(),
            'admin_pdm'  => Prm::whereHas('pcm', fn($q) => $q->where('pdm_id', $user->pdm_id))->where('aktif', true)->orderBy('nama')->get(),
            'admin_pcm'  => Prm::where('pcm_id', $user->pcm_id)->where('aktif', true)->orderBy('nama')->get(),
            'admin_prm'  => Prm::where('id', $user->prm_id)->get(),
            default      => collect(),
        };
    }
}