@extends('layouts.app')
@section('title', 'Rekap Laporan Bulanan')

@section('content')

@php
    $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Rekap Laporan Bulanan</h1>
        <p style="font-size:13px;color:#718096;">
            Status pengiriman indikator · {{ $namaBulan[$bulan] }} {{ $tahun }}
        </p>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('indikator.rekap.laporan') }}">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:12px 16px;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:14px;">

    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Bulan</label>
        <select name="bulan" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
            @for($b=1;$b<=12;$b++)
            <option value="{{ $b }}" {{ $bulan==$b?'selected':'' }}>{{ $namaBulan[$b] }}</option>
            @endfor
        </select>
    </div>

    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Tahun</label>
        <select name="tahun" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
            @for($t=date('Y');$t>=date('Y')-3;$t--)
            <option value="{{ $t }}" {{ $tahun==$t?'selected':'' }}>{{ $t }}</option>
            @endfor
        </select>
    </div>

    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Status</label>
        <select name="status" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:130px;">
            <option value="semua" {{ $filter=='semua'?'selected':'' }}>Semua</option>
            <option value="sudah" {{ $filter=='sudah'?'selected':'' }}>✅ Sudah Laporan</option>
            <option value="belum" {{ $filter=='belum'?'selected':'' }}>⚠ Belum Laporan</option>
        </select>
    </div>

    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Cari Masjid</label>
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama masjid..."
            style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;width:180px;">
    </div>

    <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;">Tampilkan</button>
    <a href="{{ route('indikator.rekap.laporan') }}" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">Reset</a>

</div>
</form>

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px;">
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:26px;font-weight:500;color:#1C4A2A;">{{ $totalSudah + $totalBelum }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Total Masjid</div>
    </div>
    <div style="background:#EAF3DE;border:0.5px solid #a8d070;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:26px;font-weight:500;color:#27500A;">{{ $totalSudah }}</div>
        <div style="font-size:11px;color:#27500A;margin-top:4px;">✅ Sudah Laporan</div>
        @php $pct = ($totalSudah + $totalBelum) > 0 ? round($totalSudah / ($totalSudah + $totalBelum) * 100) : 0; @endphp
        <div style="font-size:11px;color:#3B6D11;font-weight:500;">{{ $pct }}%</div>
    </div>
    <div style="background:#FFF4E6;border:0.5px solid #e8c97a;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:26px;font-weight:500;color:#633806;">{{ $totalBelum }}</div>
        <div style="font-size:11px;color:#633806;margin-top:4px;">⚠ Belum Laporan</div>
        <div style="font-size:11px;color:#633806;font-weight:500;">{{ 100 - $pct }}%</div>
    </div>
</div>

{{-- Progress Bar --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:12px 16px;margin-bottom:14px;">
    <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;color:#718096;">
        <span>Progress Laporan {{ $namaBulan[$bulan] }} {{ $tahun }}</span>
        <span style="font-weight:500;color:#1C4A2A;">{{ $pct }}%</span>
    </div>
    <div style="height:10px;background:#f0f4ec;border-radius:5px;">
        <div style="height:100%;width:{{ $pct }}%;background:#1C4A2A;border-radius:5px;"></div>
    </div>
</div>

{{-- Tabel --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:36px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama Masjid</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">PRM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">PCM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">PDM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:120px;">Status</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($masjids as $i => $m)
            <tr style="border-bottom:0.5px solid #f0f4ec;">
                <td style="padding:8px 12px;font-size:12px;color:#718096;">{{ $i + 1 }}</td>
                <td style="padding:8px 12px;">
                    <div style="font-size:13px;font-weight:500;color:#1a202c;">{{ $m->nama }}</div>
                    @if($m->kategori_unggulan)
                    @php $katLabel = ['MU_WILAYAH'=>'MU Wilayah','MU_DAERAH'=>'MU Daerah','MU_CABANG'=>'MU Cabang','MU_RANTING'=>'MU Ranting'][$m->kategori_unggulan] ?? ''; @endphp
                    <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:1px 6px;border-radius:10px;">⭐ {{ $katLabel }}</span>
                    @endif
                </td>
                <td style="padding:8px 12px;font-size:12px;color:#718096;">{{ $m->prm->nama ?? '—' }}</td>
                <td style="padding:8px 12px;font-size:12px;color:#718096;">{{ $m->prm->pcm->nama ?? '—' }}</td>
                <td style="padding:8px 12px;font-size:12px;color:#718096;">{{ $m->prm->pcm->pdm->nama ?? '—' }}</td>
                <td style="padding:8px 12px;text-align:center;">
                    @if($m->sudah_laporan)
                        <span style="background:#EAF3DE;color:#27500A;font-size:11px;padding:3px 10px;border-radius:20px;">✅ Sudah</span>
                    @else
                        <span style="background:#FFF4E6;color:#633806;font-size:11px;padding:3px 10px;border-radius:20px;">⚠ Belum</span>
                    @endif
                </td>
                <td style="padding:8px 12px;text-align:center;">
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <a href="{{ route('masjid.show', $m) }}"
                            style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;" title="Detail">👁</a>
                        @if(!$m->sudah_laporan)
                        <a href="{{ route('indikator.input', [$m, 'bulan'=>$bulan, 'tahun'=>$tahun]) }}"
                            style="width:26px;height:26px;border:0.5px solid #1C4A2A;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#1C4A2A;text-decoration:none;font-size:13px;" title="Input Indikator">📋</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                    Tidak ada data ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        Menampilkan {{ count($masjids) }} masjid
    </div>
</div>

@endsection