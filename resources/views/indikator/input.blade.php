@extends('layouts.app')
@section('title', 'Input Indikator Bulanan')

@section('content')

@php
    $bulanList = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:6px;">
            <a href="{{ route('masjid.index') }}" style="color:#3B6D11;text-decoration:none;">Daftar Masjid</a>
            <span>›</span>
            <a href="{{ route('masjid.show', $masjid) }}" style="color:#3B6D11;text-decoration:none;">{{ $masjid->nama }}</a>
            <span>›</span>
            <span>Input Indikator</span>
        </div>
        <h1 style="font-size:18px;font-weight:500;margin-bottom:4px;">Input Indikator Bulanan</h1>
        <p style="font-size:13px;color:#718096;">{{ $masjid->nama }} · {{ $bulanList[$bulan] }} {{ $tahun }}</p>
    </div>
    <a href="{{ route('masjid.show', $masjid) }}"
        style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:7px 14px;font-size:13px;color:#718096;text-decoration:none;">
        ← Kembali
    </a>
</div>

{{-- Pilih Bulan & Tahun --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
    <span style="font-size:13px;color:#718096;">Periode:</span>
    <form method="GET" action="{{ route('indikator.input', $masjid) }}" style="display:flex;gap:8px;align-items:center;">
        <select name="bulan" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
            @for($b = 1; $b <= 12; $b++)
            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ $bulanList[$b] }}</option>
            @endfor
        </select>
        <select name="tahun" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
            @for($t = date('Y'); $t >= date('Y') - 3; $t--)
            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endfor
        </select>
        <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 14px;height:32px;font-size:13px;cursor:pointer;">Tampilkan</button>
    </form>

    @if($sudahKirim)
    <span style="background:#EAF3DE;color:#27500A;font-size:12px;padding:4px 12px;border-radius:20px;margin-left:auto;">
        ✅ Data sudah terkirim
    </span>
    @elseif($adaDraft)
    <span style="background:#FAEEDA;color:#633806;font-size:12px;padding:4px 12px;border-radius:20px;margin-left:auto;">
        📝 Ada draft tersimpan
    </span>
    @endif
</div>

@if($sudahKirim)
{{-- Tampilkan data yang sudah terkirim --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        📊 Data Indikator — {{ $bulanList[$bulan] }} {{ $tahun }}
    </div>
    @foreach($indikators as $ind)
    @php $d = $dataExisting->firstWhere('indikator_id', $ind->id); @endphp
    <div style="display:flex;align-items:center;padding:10px 0;border-bottom:0.5px solid #f0f4ec;gap:12px;">
        <div style="width:100px;flex-shrink:0;">
            @php
                $bg = ['jamaah'=>'#EAF3DE','jariyah'=>'#E1F5EE','jamiyah'=>'#EEEDFE'][$ind->kelompok] ?? '#eee';
                $cl = ['jamaah'=>'#27500A','jariyah'=>'#085041','jamiyah'=>'#3C3489'][$ind->kelompok] ?? '#333';
            @endphp
            <span style="background:{{ $bg }};color:{{ $cl }};font-size:10px;padding:2px 8px;border-radius:10px;">{{ $ind->kode }}</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:13px;color:#1a202c;">{{ $ind->nama }}</div>
            <div style="font-size:11px;color:#718096;">{{ $ind->deskripsi }}</div>
        </div>
        <div style="font-size:15px;font-weight:500;color:#1C4A2A;min-width:80px;text-align:right;">
            {{ $d ? number_format($d->nilai, 1) . ' ' . $ind->satuan : '—' }}
        </div>
    </div>
    @endforeach

    <div style="margin-top:14px;display:flex;gap:8px;">
        <form method="POST" action="{{ route('indikator.simpan', $masjid) }}?bulan={{ $bulan }}&tahun={{ $tahun }}&_reset=1">
            @csrf
            <button type="submit" onclick="return confirm('Batalkan pengiriman dan edit ulang data ini?')"
                style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:7px 14px;font-size:13px;color:#718096;cursor:pointer;">
                ✎ Edit Ulang
            </button>
        </form>
    </div>
</div>

@else
{{-- Form Input --}}
<form method="POST" action="{{ route('indikator.simpan', $masjid) }}?bulan={{ $bulan }}&tahun={{ $tahun }}">
@csrf

@php
    $kelompokLabel = ['jamaah' => '🕌 Kelompok Jamaah', 'jariyah' => '💰 Kelompok Jariyah', 'jamiyah' => '🤝 Kelompok Jamiyah'];
    $kelompokWarna = ['jamaah' => ['#EAF3DE','#27500A','#1C4A2A'], 'jariyah' => ['#E1F5EE','#085041','#0F6E56'], 'jamiyah' => ['#EEEDFE','#3C3489','#534AB7']];
    $grouped = $indikators->groupBy('kelompok');
@endphp

@foreach($grouped as $kelompok => $items)
@php [$bgK, $clK, $accentK] = $kelompokWarna[$kelompok] ?? ['#f0f4ec','#333','#333']; @endphp
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;margin-bottom:14px;">
    <div style="background:{{ $bgK }};padding:12px 16px;border-bottom:0.5px solid {{ $bgK }};">
        <span style="font-size:13px;font-weight:500;color:{{ $clK }};">{{ $kelompokLabel[$kelompok] ?? $kelompok }}</span>
    </div>
    <div style="padding:16px;">
        @foreach($items as $ind)
        @php $d = $dataExisting->firstWhere('indikator_id', $ind->id); @endphp
        <div style="display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:0.5px solid #f0f4ec;">
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="background:{{ $bgK }};color:{{ $clK }};font-size:10px;padding:1px 7px;border-radius:10px;">{{ $ind->kode }}</span>
                    <span style="font-size:13px;font-weight:500;color:#1a202c;">{{ $ind->nama }}</span>
                </div>
                @if($ind->deskripsi)
                <div style="font-size:11px;color:#718096;margin-bottom:6px;">{{ $ind->deskripsi }}</div>
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                <input type="number" name="indikator[{{ $ind->id }}]"
                    value="{{ $d ? $d->nilai : old('indikator.'.$ind->id) }}"
                    step="0.01" min="0" placeholder="0"
                    style="width:100px;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:14px;text-align:right;font-weight:500;">
                <span style="font-size:12px;color:#718096;min-width:40px;">{{ $ind->satuan }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

{{-- Catatan --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:16px;margin-bottom:16px;">
    <label style="font-size:13px;font-weight:500;color:#1C4A2A;display:block;margin-bottom:8px;">📝 Catatan (opsional)</label>
    <textarea name="catatan" rows="3" placeholder="Tambahkan catatan jika ada..."
        style="width:100%;border:0.5px solid #ccc;border-radius:8px;padding:8px 10px;font-size:13px;resize:vertical;">{{ $dataExisting->first()?->catatan }}</textarea>
</div>

{{-- Tombol --}}
<div style="display:flex;gap:10px;align-items:center;">
    <button type="submit" name="status" value="draft"
        style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;cursor:pointer;">
        💾 Simpan Draft
    </button>
    <button type="submit" name="status" value="terkirim"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;"
        onclick="return confirm('Kirim data indikator {{ $bulanList[$bulan] }} {{ $tahun }}? Data tidak dapat diubah setelah dikirim.')">
        📤 Kirim Data
    </button>
    <span style="font-size:12px;color:#718096;">Data yang sudah dikirim tidak dapat diubah.</span>
</div>

</form>
@endif

@endsection