@extends('layouts.app')
@section('title', 'Rekap Pemenuhan Indikator')

@section('content')
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Rekap Pemenuhan Indikator</h1>
        <p style="font-size:13px;color:#718096;">Data indikator masjid unggulan · Tahun {{ $tahun }}</p>
    </div>
    <a href="{{ route('indikator.rekap', ['tahun' => $tahun, 'export' => 1]) }}"
        style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:8px 16px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;gap:6px;">
        ⬇ Export Excel
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('indikator.rekap') }}">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:12px 16px;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:12px;">
    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Tahun</label>
        <select name="tahun" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:100px;">
            @foreach(range(date('Y'), 2020, -1) as $y)
            <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Cari Masjid</label>
        <input type="text" name="masjid_id" placeholder="Nama masjid..."
            style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;width:180px;">
    </div>
    <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;">
        Terapkan
    </button>
    <a href="{{ route('indikator.rekap') }}" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">
        Reset
    </a>
</div>
</form>

{{-- Legenda --}}
<div style="display:flex;gap:16px;font-size:11px;color:#718096;margin-bottom:10px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:20px;height:12px;background:#EAF3DE;border-radius:3px;display:inline-block;"></span> Tinggi (≥ target)
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:20px;height:12px;background:#FAEEDA;border-radius:3px;display:inline-block;"></span> Sedang (50–99%)
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:20px;height:12px;background:#FCEBEB;border-radius:3px;display:inline-block;"></span> Rendah (< 50%)
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <span style="width:20px;height:12px;background:#F1F0EC;border-radius:3px;display:inline-block;"></span> Belum input
    </div>
</div>

{{-- Tabel --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:900px;">
            <thead>
                <tr>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:30px;">#</th>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;min-width:160px;">Nama Masjid</th>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:80px;">PRM</th>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:80px;">Kategori</th>
                    @foreach($indikators as $ind)
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 6px;border-bottom:0.5px solid #dde8d5;text-align:center;width:50px;"
                        title="{{ $ind->nama }}">
                        {{ $ind->kode }}
                    </th>
                    @endforeach
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:center;width:60px;">Skor</th>
                </tr>
                {{-- Sub header satuan --}}
                <tr style="background:#F4F7F1;">
                    <td colspan="4" style="font-size:10px;color:#a0aec0;padding:4px 10px;">Satuan →</td>
                    @foreach($indikators as $ind)
                    <td style="font-size:10px;color:#a0aec0;padding:4px 6px;text-align:center;">{{ $ind->satuan }}</td>
                    @endforeach
                    <td style="font-size:10px;color:#a0aec0;padding:4px 10px;text-align:center;">%</td>
                </tr>
            </thead>
            <tbody>
                @forelse($masjidList as $i => $m)
                @php
                    $dataM = $dataRekap[$m->id] ?? collect();
                    $terisi = 0;
                    $total  = $indikators->count();
                @endphp
                <tr style="border-bottom:0.5px solid #f0f4ec;">
                    <td style="padding:8px 10px;font-size:12px;color:#718096;">{{ $i + 1 }}</td>
                    <td style="padding:8px 10px;font-size:13px;font-weight:500;">
                        <a href="{{ route('masjid.show', $m) }}" style="color:#1C4A2A;text-decoration:none;">{{ $m->nama }}</a>
                    </td>
                    <td style="padding:8px 10px;font-size:12px;color:#718096;">{{ $m->prm->nama ?? '-' }}</td>
                    <td style="padding:8px 10px;">
                        @if($m->kategori_unggulan == 'MU_WILAYAH')
                            <span style="background:#EEEDFE;color:#3C3489;font-size:10px;padding:2px 6px;border-radius:20px;">Wilayah</span>
                        @elseif($m->kategori_unggulan == 'MU_DAERAH')
                            <span style="background:#E1F5EE;color:#085041;font-size:10px;padding:2px 6px;border-radius:20px;">Daerah</span>
                        @elseif($m->kategori_unggulan == 'MU_CABANG')
                            <span style="background:#FAEEDA;color:#633806;font-size:10px;padding:2px 6px;border-radius:20px;">Cabang</span>
                        @elseif($m->kategori_unggulan == 'MU_RANTING')
                            <span style="background:#FAECE7;color:#4A1B0C;font-size:10px;padding:2px 6px;border-radius:20px;">Ranting</span>
                        @else
                            <span style="background:#F1EFE8;color:#5F5E5A;font-size:10px;padding:2px 6px;border-radius:20px;">—</span>
                        @endif
                    </td>
                    @foreach($indikators as $ind)
                    @php
                        $d = $dataM->firstWhere('indikator_id', $ind->id);
                        $val = $d ? round($d->rata_tahun ?? $d->total_tahun, 1) : null;
                        if ($val !== null) $terisi++;
                        // Warna pill sederhana berdasarkan ada/tidak data
                        $bg  = $val === null ? '#F1F0EC' : ($val > 0 ? '#EAF3DE' : '#FCEBEB');
                        $clr = $val === null ? '#a0aec0' : ($val > 0 ? '#27500A' : '#A32D2D');
                    @endphp
                    <td style="padding:8px 6px;text-align:center;">
                        <span style="background:{{ $bg }};color:{{ $clr }};font-size:11px;font-weight:500;padding:2px 6px;border-radius:4px;display:inline-block;min-width:34px;">
                            {{ $val !== null ? $val : '—' }}
                        </span>
                    </td>
                    @endforeach
                    @php
                        $skor = $total > 0 ? round(($terisi / $total) * 100) : 0;
                        $skorBg  = $skor >= 80 ? '#EAF3DE' : ($skor >= 50 ? '#FAEEDA' : '#FCEBEB');
                        $skorClr = $skor >= 80 ? '#27500A' : ($skor >= 50 ? '#633806' : '#A32D2D');
                    @endphp
                    <td style="padding:8px 10px;text-align:center;">
                        <span style="background:{{ $skorBg }};color:{{ $skorClr }};font-size:12px;font-weight:500;padding:3px 8px;border-radius:20px;">
                            {{ $skor }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 5 + $indikators->count() }}" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                        Belum ada data masjid untuk ditampilkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        Menampilkan {{ $masjidList->count() }} masjid · Tahun {{ $tahun }}
    </div>
</div>

{{-- Keterangan indikator --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:16px;margin-top:14px;">
    <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:10px;">Keterangan Kode Indikator</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
        @foreach($indikators as $ind)
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span style="background:#EAF3DE;color:#27500A;padding:2px 7px;border-radius:4px;font-size:11px;font-weight:500;flex-shrink:0;">{{ $ind->kode }}</span>
            <span style="color:#4a5568;">{{ $ind->nama }} <span style="color:#a0aec0;">({{ $ind->satuan }})</span></span>
        </div>
        @endforeach
    </div>
</div>
@endsection