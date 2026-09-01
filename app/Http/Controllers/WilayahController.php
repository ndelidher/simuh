<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pwm;
use App\Models\Pdm;
use App\Models\Pcm;
use App\Models\Prm;

class WilayahController extends Controller
{
    // ─── PWM ─────────────────────────────────────────────────────
    public function pwm()
    {
        $data = Pwm::withCount('pdmList')->orderBy('nama')->paginate(20);
        return view('wilayah.pwm', compact('data'));
    }

    // ─── PDM ─────────────────────────────────────────────────────
    public function pdm(Request $request)
    {
        $query = Pdm::with('pwm')->withCount('pcmList');
        if ($request->filled('pwm_id')) {
            $query->where('pwm_id', $request->pwm_id);
        }
        $data    = $query->orderBy('nama')->paginate(20);
        $pwmList = Pwm::orderBy('nama')->get();
        return view('wilayah.pdm', compact('data', 'pwmList'));
    }

    // ─── PCM ─────────────────────────────────────────────────────
    public function pcm(Request $request)
    {
        $query = Pcm::with('pdm.pwm')->withCount('prmList');
        if ($request->filled('pdm_id')) {
            $query->where('pdm_id', $request->pdm_id);
        }
        $data    = $query->orderBy('nama')->paginate(20);
        $pdmList = Pdm::with('pwm')->orderBy('nama')->get();
        return view('wilayah.pcm', compact('data', 'pdmList'));
    }

    // ─── PRM ─────────────────────────────────────────────────────
    public function prm(Request $request)
    {
        $query = Prm::with('pcm.pdm.pwm')->withCount('masjidList');
        if ($request->filled('pcm_id')) {
            $query->where('pcm_id', $request->pcm_id);
        }
        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%'.$request->cari.'%');
        }
        $data    = $query->orderBy('nama')->paginate(20);
        $pcmList = Pcm::with('pdm')->orderBy('nama')->get();
        return view('wilayah.prm', compact('data', 'pcmList'));
    }

    // ─── Helper: generate kode unik ──────────────────────────────
    private function generateKode(string $prefix, string $nama, string $model): string
    {
        $base     = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $nama), 0, 8));
        $kode     = $prefix . '-' . $base;
        $kodeAsli = $kode;
        $counter  = 1;
        while ($model::where('kode', $kode)->exists()) {
            $kode = $kodeAsli . $counter;
            $counter++;
        }
        return $kode;
    }

    // ─── IMPORT FORM ─────────────────────────────────────────────
    public function importForm()
    {
        return view('wilayah.import');
    }

    // ─── IMPORT PROSES ───────────────────────────────────────────
    public function importProses(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10240',
        ]);

        try {
            $file        = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca: ' . $e->getMessage()]);
        }

        $berhasil = 0;
        $skip     = 0;
        $errList  = [];

        // Lewati baris pertama (header)
        foreach (array_slice($rows, 1) as $index => $row) {
            $idPrm       = trim((string)($row[0] ?? ''));
            $namaRanting = trim((string)($row[1] ?? ''));
            $namaCabang  = trim((string)($row[2] ?? ''));
            $namaDaerah  = trim((string)($row[3] ?? ''));
            $namaWilayah = trim((string)($row[4] ?? ''));

            // Skip baris kosong
            if (empty($namaCabang) || empty($namaDaerah) || empty($namaWilayah)) {
                $skip++;
                continue;
            }

            // Kalau nama ranting kosong atau '-', isi dengan nama PCM
            if (empty($namaRanting) || $namaRanting === '-') {
                $namaRanting = $namaCabang;
            }

            try {
                // 1. PWM — tanpa closure agar $this bisa diakses
                $pwm = Pwm::firstOrCreate(
                    ['nama' => $namaWilayah],
                    ['kode' => $this->generateKode('PWM', $namaWilayah, Pwm::class), 'aktif' => true]
                );

                // 2. PDM
                $pdm = Pdm::firstOrCreate(
                    ['nama' => $namaDaerah, 'pwm_id' => $pwm->id],
                    ['kode' => $this->generateKode('PDM', $namaDaerah, Pdm::class), 'aktif' => true]
                );

                // 3. PCM
                $pcm = Pcm::firstOrCreate(
                    ['nama' => $namaCabang, 'pdm_id' => $pdm->id],
                    ['kode' => $this->generateKode('PCM', $namaCabang, Pcm::class), 'aktif' => true]
                );

                // 4. PRM
                $kodePrm = !empty($idPrm)
                    ? $idPrm
                    : $this->generateKode('PRM', $namaRanting, Prm::class);

                Prm::updateOrCreate(
                    ['kode' => $kodePrm],
                    ['nama' => $namaRanting, 'pcm_id' => $pcm->id, 'aktif' => true]
                );

                $berhasil++;

            } catch (\Exception $e) {
                $errList[] = "Baris " . ($index + 2) . " ({$namaRanting}): " . $e->getMessage();
            }
        }

        $pesan = "Import selesai! {$berhasil} baris berhasil, {$skip} baris dilewati.";
        if (!empty($errList)) {
            $pesan .= " " . count($errList) . " baris error.";
            session(['import_errors' => array_slice($errList, 0, 20)]);
        }

        return redirect()->route('wilayah.prm')->with('success', $pesan);
    }

    // ── PWM CRUD ──────────────────────────────────────────────────
    public function pwmCreate() {
        return view('wilayah.pwm_form', ['mode'=>'create','data'=>null]);
    }
    public function pwmStore(Request $request) {
        $data = $request->validate(['kode'=>'required|string|max:20|unique:pwm,kode','nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        \App\Models\Pwm::create($data);
        return redirect()->route('wilayah.pwm')->with('success','Data PWM berhasil ditambahkan.');
    }
    public function pwmEdit(\App\Models\Pwm $pwm) {
        return view('wilayah.pwm_form', ['mode'=>'edit','data'=>$pwm]);
    }
    public function pwmUpdate(Request $request, \App\Models\Pwm $pwm) {
        $data = $request->validate(['kode'=>'required|string|max:20|unique:pwm,kode,'.$pwm->id,'nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        $pwm->update($data);
        return redirect()->route('wilayah.pwm')->with('success','Data PWM berhasil diperbarui.');
    }
    public function pwmDestroy(\App\Models\Pwm $pwm) {
        $pwm->delete();
        return redirect()->route('wilayah.pwm')->with('success','Data PWM berhasil dihapus.');
    }

    // ── PDM CRUD ──────────────────────────────────────────────────
    public function pdmCreate() {
        $pwmList = \App\Models\Pwm::where('aktif',true)->orderBy('nama')->get();
        return view('wilayah.pdm_form', ['mode'=>'create','data'=>null,'pwmList'=>$pwmList]);
    }
    public function pdmStore(Request $request) {
        $data = $request->validate(['pwm_id'=>'required|exists:pwm,id','kode'=>'required|string|max:20|unique:pdm,kode','nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        \App\Models\Pdm::create($data);
        return redirect()->route('wilayah.pdm')->with('success','Data PDM berhasil ditambahkan.');
    }
    public function pdmEdit(\App\Models\Pdm $pdm) {
        $pwmList = \App\Models\Pwm::where('aktif',true)->orderBy('nama')->get();
        return view('wilayah.pdm_form', ['mode'=>'edit','data'=>$pdm,'pwmList'=>$pwmList]);
    }
    public function pdmUpdate(Request $request, \App\Models\Pdm $pdm) {
        $data = $request->validate(['pwm_id'=>'required|exists:pwm,id','kode'=>'required|string|max:20|unique:pdm,kode,'.$pdm->id,'nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        $pdm->update($data);
        return redirect()->route('wilayah.pdm')->with('success','Data PDM berhasil diperbarui.');
    }
    public function pdmDestroy(\App\Models\Pdm $pdm) {
        $pdm->delete();
        return redirect()->route('wilayah.pdm')->with('success','Data PDM berhasil dihapus.');
    }

    // ── PCM CRUD ──────────────────────────────────────────────────
    public function pcmCreate() {
        $pwmList = \App\Models\Pwm::where('aktif',true)->orderBy('nama')->get();
        return view('wilayah.pcm_form', ['mode'=>'create','data'=>null,'pwmList'=>$pwmList,'pdmList'=>collect()]);
    }
    public function pcmStore(Request $request) {
        $data = $request->validate(['pdm_id'=>'required|exists:pdm,id','kode'=>'required|string|max:20|unique:pcm,kode','nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        \App\Models\Pcm::create($data);
        return redirect()->route('wilayah.pcm')->with('success','Data PCM berhasil ditambahkan.');
    }
    public function pcmEdit(\App\Models\Pcm $pcm) {
        $pwmList = \App\Models\Pwm::where('aktif',true)->orderBy('nama')->get();
        $pdmList = \App\Models\Pdm::where('pwm_id',$pcm->pdm->pwm_id)->where('aktif',true)->orderBy('nama')->get();
        return view('wilayah.pcm_form', ['mode'=>'edit','data'=>$pcm,'pwmList'=>$pwmList,'pdmList'=>$pdmList]);
    }
    public function pcmUpdate(Request $request, \App\Models\Pcm $pcm) {
        $data = $request->validate(['pdm_id'=>'required|exists:pdm,id','kode'=>'required|string|max:20|unique:pcm,kode,'.$pcm->id,'nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        $pcm->update($data);
        return redirect()->route('wilayah.pcm')->with('success','Data PCM berhasil diperbarui.');
    }
    public function pcmDestroy(\App\Models\Pcm $pcm) {
        $pcm->delete();
        return redirect()->route('wilayah.pcm')->with('success','Data PCM berhasil dihapus.');
    }

    // ── PRM CRUD ──────────────────────────────────────────────────
    public function prmCreate() {
        $pwmList = \App\Models\Pwm::where('aktif',true)->orderBy('nama')->get();
        return view('wilayah.prm_form', ['mode'=>'create','data'=>null,'pwmList'=>$pwmList,'pdmList'=>collect(),'pcmList'=>collect()]);
    }
    public function prmStore(Request $request) {
        $data = $request->validate(['pcm_id'=>'required|exists:pcm,id','kode'=>'required|string|max:20|unique:prm,kode','nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        \App\Models\Prm::create($data);
        return redirect()->route('wilayah.prm')->with('success','Data PRM berhasil ditambahkan.');
    }
    public function prmEdit(\App\Models\Prm $prm) {
        $pwmList = \App\Models\Pwm::where('aktif',true)->orderBy('nama')->get();
        $pdmList = \App\Models\Pdm::where('pwm_id',$prm->pcm->pdm->pwm_id)->where('aktif',true)->orderBy('nama')->get();
        $pcmList = \App\Models\Pcm::where('pdm_id',$prm->pcm->pdm_id)->where('aktif',true)->orderBy('nama')->get();
        return view('wilayah.prm_form', ['mode'=>'edit','data'=>$prm,'pwmList'=>$pwmList,'pdmList'=>$pdmList,'pcmList'=>$pcmList]);
    }
    public function prmUpdate(Request $request, \App\Models\Prm $prm) {
        $data = $request->validate(['pcm_id'=>'required|exists:pcm,id','kode'=>'required|string|max:20|unique:prm,kode,'.$prm->id,'nama'=>'required|string|max:150','aktif'=>'boolean']);
        $data['aktif'] = $request->boolean('aktif', true);
        $prm->update($data);
        return redirect()->route('wilayah.prm')->with('success','Data PRM berhasil diperbarui.');
    }
    public function prmDestroy(\App\Models\Prm $prm) {
        $prm->delete();
        return redirect()->route('wilayah.prm')->with('success','Data PRM berhasil dihapus.');
    }
}