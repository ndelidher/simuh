@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php
    $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $bulanIni  = date('n');
    $tahunIni  = date('Y');
@endphp

{{-- Header --}}
<div style="margin-bottom:16px;">
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Dashboard</h1>
    <p style="font-size:13px;color:#718096;">
        Selamat datang, <strong>{{ auth()->user()->name }}</strong> ·
        {{ auth()->user()->role_label }}
        @if(!empty($stats['nama_scope'])) · {{ $stats['nama_scope'] }} @endif
        · {{ $namaBulan[$bulanIni] }} {{ $tahunIni }}
    </p>
</div>

{{-- ══════════════════════════════════════════════════════════════
     DASHBOARD ADMIN MASJID
══════════════════════════════════════════════════════════════ --}}
@if($user->isAdminMasjid() && $masjidSaya)

{{-- Info masjid --}}
<div style="background:#1C4A2A;border-radius:12px;padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div style="font-size:11px;color:#97C459;margin-bottom:4px;">MASJID ANDA</div>
        <div style="font-size:18px;font-weight:500;color:#fff;">{{ $masjidSaya->nama }}</div>
        <div style="font-size:12px;color:#a0c080;margin-top:2px;">
            {{ $masjidSaya->prm->nama ?? '' }} · {{ $masjidSaya->prm->pcm->nama ?? '' }}
        </div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:11px;color:#97C459;margin-bottom:4px;">INDIKATOR BULAN INI</div>
        <div style="font-size:28px;font-weight:500;color:#fff;">{{ $pctIndikator }}</div>
        <div style="font-size:11px;color:#a0c080;">terisi</div>
    </div>
</div>

{{-- 7 Indikator bulan ini --}}
@php
    $kelompokLabel = ['jamaah'=>'🕌 Jamaah','jariyah'=>'💰 Jariyah','jamiyah'=>'🤝 Jamiyah'];
    $kelompokWarna = [
        'jamaah'  => ['#EAF3DE','#27500A','#1C4A2A'],
        'jariyah' => ['#E1F5EE','#085041','#0F6E56'],
        'jamiyah' => ['#EEEDFE','#3C3489','#534AB7'],
    ];
    $grouped = $indikatorBulan->groupBy('kelompok');
@endphp

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
@foreach($grouped as $kel => $items)
@php [$bgK,$clK,$acK] = $kelompokWarna[$kel] ?? ['#f0f4ec','#333','#333']; @endphp
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <div style="background:{{ $bgK }};padding:10px 14px;font-size:12px;font-weight:500;color:{{ $clK }};">
        {{ $kelompokLabel[$kel] ?? $kel }}
    </div>
    <div style="padding:10px 14px;">
        @foreach($items as $ind)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:0.5px solid #f0f4ec;">
            <div>
                <span style="background:{{ $bgK }};color:{{ $clK }};font-size:10px;padding:1px 6px;border-radius:10px;margin-right:4px;">{{ $ind['kode'] }}</span>
                <span style="font-size:12px;color:#4a5568;">{{ $ind['nama'] }}</span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:13px;font-weight:500;color:{{ $ind['terisi'] ? $acK : '#a0aec0' }};">
                    {{ $ind['nilai'] !== null ? number_format($ind['nilai'], 1).' '.$ind['satuan'] : '—' }}
                </span>
                @if($ind['terisi'])
                <span style="color:#27500A;font-size:14px;">✓</span>
                @else
                <span style="color:#a0aec0;font-size:14px;">○</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
</div>

{{-- Tombol input indikator --}}
@if($indikatorBulan->where('terisi', false)->count() > 0)
<div style="background:#FAEEDA;border:0.5px solid #e8c97a;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:13px;color:#633806;">⚠ Ada {{ $indikatorBulan->where('terisi', false)->count() }} indikator belum diisi bulan ini.</span>
    <a href="{{ route('indikator.input', $masjidSaya) }}"
        style="background:#1C4A2A;color:#fff;border-radius:8px;padding:6px 14px;font-size:13px;text-decoration:none;">
        📋 Input Sekarang
    </a>
</div>
@else
<div style="background:#EAF3DE;border:0.5px solid #a8d070;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:13px;color:#27500A;">✅ Semua indikator bulan ini sudah terisi!</span>
    <a href="{{ route('masjid.show', $masjidSaya) }}"
        style="border:0.5px solid #ccc;background:#fff;color:#718096;border-radius:8px;padding:6px 14px;font-size:13px;text-decoration:none;">
        Lihat Detail
    </a>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════
     DASHBOARD SEMUA LEVEL LAIN
══════════════════════════════════════════════════════════════ --}}
@else

{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:16px;">

    {{-- Total Masjid --}}
    <div style="background:#1C4A2A;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#fff;">{{ number_format($stats['total_masjid']) }}</div>
        <div style="font-size:11px;color:#97C459;margin-top:4px;">Total Masjid</div>
    </div>

    {{-- Laporan Bulan Ini --}}
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ $stats['masjid_laporan'] }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Sudah Laporan</div>
        <div style="font-size:11px;color:#97C459;">{{ $stats['pct_laporan'] }}%</div>
    </div>

    @if($user->isSuperAdmin() || $user->isAdminPP())
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_pwm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PWM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_pdm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PDM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_pcm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PCM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_prm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    @elseif($user->isAdminPWM())
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_pdm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PDM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_pcm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PCM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_prm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    @elseif($user->isAdminPDM())
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_pcm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PCM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_prm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    @elseif($user->isAdminPCM())
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;">{{ number_format($stats['total_prm']) }}</div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    @endif

</div>

{{-- Masjid Unggulan per Kategori --}}
@if(!empty($stats['mu_kategori']))
<div style="margin-bottom:16px;">
    <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:8px;">⭐ Masjid Unggulan</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;">
        @foreach($stats['mu_kategori'] as $mu)
        <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;">
            <div style="width:44px;height:44px;background:{{ $mu['bg'] }};border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:20px;font-weight:600;color:{{ $mu['cl'] }};">{{ $mu['total'] }}</span>
            </div>
            <div>
                <div style="font-size:11px;font-weight:500;color:{{ $mu['cl'] }};">{{ $mu['label'] }}</div>
                <div style="font-size:10px;color:#718096;margin-top:2px;">masjid unggulan</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Progress laporan bulan ini --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px 16px;margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
        <span style="font-size:13px;font-weight:500;color:#1C4A2A;">📊 Progress Laporan {{ $namaBulan[$bulanIni] }} {{ $tahunIni }}</span>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:13px;font-weight:500;color:#1C4A2A;">{{ $stats['masjid_laporan'] }} / {{ $stats['total_masjid'] }} masjid</span>
            <a href="{{ route('indikator.rekap.laporan', ['bulan'=>$bulanIni,'tahun'=>$tahunIni,'status'=>'belum']) }}"
                style="font-size:11px;color:#3B6D11;text-decoration:none;border:0.5px solid #a8d070;border-radius:6px;padding:2px 8px;">
                Lihat Semua →
            </a>
        </div>
    </div>
    <div style="height:10px;background:#f0f4ec;border-radius:5px;margin-bottom:6px;">
        <div style="height:100%;width:{{ $stats['pct_laporan'] }}%;background:#1C4A2A;border-radius:5px;transition:width 0.5s;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;font-size:11px;">
        <span style="color:#718096;">{{ $stats['pct_laporan'] }}% masjid sudah mengirim laporan bulan ini</span>
        @php $belum = $stats['total_masjid'] - $stats['masjid_laporan']; @endphp
        @if($belum > 0)
        <span style="color:#633806;">⚠ {{ $belum }} masjid belum laporan</span>
        @else
        <span style="color:#27500A;">✅ Semua masjid sudah laporan</span>
        @endif
    </div>
</div>

{{-- Widget: Masjid Belum Laporan (max 5) --}}
@if(isset($belumLaporan) && count($belumLaporan) > 0)
<div style="background:#fff;border:0.5px solid #e8c97a;border-radius:12px;overflow:hidden;margin-bottom:16px;">
    <div style="background:#FFF4E6;padding:10px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:0.5px solid #e8c97a;">
        <span style="font-size:13px;font-weight:500;color:#633806;">⚠ Masjid Belum Laporan {{ $namaBulan[$bulanIni] }} {{ $tahunIni }}</span>
        <a href="{{ route('indikator.rekap.laporan', ['bulan'=>$bulanIni,'tahun'=>$tahunIni,'status'=>'belum']) }}"
            style="font-size:12px;color:#633806;text-decoration:none;border:0.5px solid #e8c97a;border-radius:6px;padding:3px 10px;">
            Lihat Semua ({{ $belum }}) →
        </a>
    </div>
    @foreach($belumLaporan as $m)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 16px;border-bottom:0.5px solid #f0f4ec;">
        <div>
            <div style="font-size:13px;color:#1a202c;">{{ $m->nama }}</div>
            <div style="font-size:11px;color:#718096;">{{ $m->prm->nama ?? '—' }} · {{ $m->prm->pcm->nama ?? '—' }}</div>
        </div>
        <a href="{{ route('indikator.input', [$m, 'bulan'=>$bulanIni, 'tahun'=>$tahunIni]) }}"
            style="font-size:11px;background:#1C4A2A;color:#fff;border-radius:6px;padding:4px 10px;text-decoration:none;">
            📋 Input
        </a>
    </div>
    @endforeach
</div>
@endif

@endif

{{-- ── 3 Grafik Tren 6 Bulan (semua level) ──────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;">

    {{-- Grafik 1: Jamaah --}}
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px;">
        <div style="font-size:12px;font-weight:500;color:#1C4A2A;margin-bottom:4px;">🕌 Jamaah</div>
        <div style="display:flex;gap:10px;margin-bottom:8px;font-size:10px;color:#718096;">
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#1C4A2A;display:inline-block;border-radius:2px;"></span>Subuh
            </span>
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#5DA632;display:inline-block;border-radius:2px;"></span>Pengajian
            </span>
        </div>
        <div style="position:relative;height:160px;">
            <canvas id="chartJamaah"></canvas>
        </div>
    </div>

    {{-- Grafik 2: Jariyah --}}
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px;">
        <div style="font-size:12px;font-weight:500;color:#0F6E56;margin-bottom:4px;">💰 Jariyah (jt Rp)</div>
        <div style="display:flex;gap:10px;margin-bottom:8px;font-size:10px;color:#718096;">
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#0F6E56;display:inline-block;border-radius:2px;"></span>Infaq
            </span>
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#97C459;display:inline-block;border-radius:2px;"></span>Alokasi
            </span>
        </div>
        <div style="position:relative;height:160px;">
            <canvas id="chartJariyah"></canvas>
        </div>
    </div>

    {{-- Grafik 3: Jamiyah --}}
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px;">
        <div style="font-size:12px;font-weight:500;color:#534AB7;margin-bottom:4px;">🤝 Jamiyah</div>
        <div style="display:flex;gap:10px;margin-bottom:8px;font-size:10px;color:#718096;flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#534AB7;display:inline-block;border-radius:2px;"></span>Rapat
            </span>
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#9B8FE0;display:inline-block;border-radius:2px;"></span>Konten
            </span>
            <span style="display:flex;align-items:center;gap:3px;">
                <span style="width:10px;height:3px;background:#3C3489;display:inline-block;border-radius:2px;"></span>Kegiatan
            </span>
        </div>
        <div style="position:relative;height:160px;">
            <canvas id="chartJamiyah"></canvas>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const trenData  = @json($trenData);
const labels    = @json($bulanLabel);

const chartOpts = (yLabel) => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { ticks: { font: { size: 10 } } },
        y: { ticks: { font: { size: 10 } }, title: { display: !!yLabel, text: yLabel, font: { size: 9 }, color: '#888' } }
    }
});

// ── Grafik 1: Jamaah ──────────────────────────────────────────────
new Chart(document.getElementById('chartJamaah'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Subuh',
                data: trenData['JAMAAH_1'] ?? [],
                borderColor: '#1C4A2A', backgroundColor: 'rgba(28,74,42,0.08)',
                borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true
            },
            {
                label: 'Pengajian',
                data: trenData['JAMAAH_2'] ?? [],
                borderColor: '#5DA632', backgroundColor: 'transparent',
                borderWidth: 2, pointRadius: 3, tension: 0.4
            },
        ]
    },
    options: chartOpts('Orang')
});

// ── Grafik 2: Jariyah ─────────────────────────────────────────────
new Chart(document.getElementById('chartJariyah'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Infaq',
                data: trenData['JARIYAH_1'] ?? [],
                backgroundColor: '#0F6E56',
                borderRadius: 4
            },
            {
                label: 'Alokasi',
                data: trenData['JARIYAH_2'] ?? [],
                backgroundColor: '#97C459',
                borderRadius: 4
            },
        ]
    },
    options: chartOpts('Juta Rp')
});

// ── Grafik 3: Jamiyah ─────────────────────────────────────────────
new Chart(document.getElementById('chartJamiyah'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Rapat',
                data: trenData['JAMIYAH_1'] ?? [],
                borderColor: '#534AB7', backgroundColor: 'transparent',
                borderWidth: 2, pointRadius: 3, tension: 0.4
            },
            {
                label: 'Konten',
                data: trenData['JAMIYAH_2'] ?? [],
                borderColor: '#9B8FE0', backgroundColor: 'transparent',
                borderWidth: 2, pointRadius: 3, tension: 0.4
            },
            {
                label: 'Kegiatan',
                data: trenData['JAMIYAH_3'] ?? [],
                borderColor: '#3C3489', backgroundColor: 'rgba(60,52,137,0.07)',
                borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true
            },
        ]
    },
    options: chartOpts('Kegiatan')
});
</script>
@endpush