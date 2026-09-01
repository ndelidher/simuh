<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $bulanIni  = date('n');
    $tahunIni  = date('Y');
?>


<div style="margin-bottom:16px;">
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Dashboard</h1>
    <p style="font-size:13px;color:#718096;">
        Selamat datang, <strong><?php echo e(auth()->user()->name); ?></strong> ·
        <?php echo e(auth()->user()->role_label); ?>

        <?php if(!empty($stats['nama_scope'])): ?> · <?php echo e($stats['nama_scope']); ?> <?php endif; ?>
        · <?php echo e($namaBulan[$bulanIni]); ?> <?php echo e($tahunIni); ?>

    </p>
</div>


<?php if($user->isAdminMasjid() && $masjidSaya): ?>


<div style="background:#1C4A2A;border-radius:12px;padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div style="font-size:11px;color:#97C459;margin-bottom:4px;">MASJID ANDA</div>
        <div style="font-size:18px;font-weight:500;color:#fff;"><?php echo e($masjidSaya->nama); ?></div>
        <div style="font-size:12px;color:#a0c080;margin-top:2px;">
            <?php echo e($masjidSaya->prm->nama ?? ''); ?> · <?php echo e($masjidSaya->prm->pcm->nama ?? ''); ?>

        </div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:11px;color:#97C459;margin-bottom:4px;">INDIKATOR BULAN INI</div>
        <div style="font-size:28px;font-weight:500;color:#fff;"><?php echo e($pctIndikator); ?></div>
        <div style="font-size:11px;color:#a0c080;">terisi</div>
    </div>
</div>


<?php
    $kelompokLabel = ['jamaah'=>'🕌 Jamaah','jariyah'=>'💰 Jariyah','jamiyah'=>'🤝 Jamiyah'];
    $kelompokWarna = [
        'jamaah'  => ['#EAF3DE','#27500A','#1C4A2A'],
        'jariyah' => ['#E1F5EE','#085041','#0F6E56'],
        'jamiyah' => ['#EEEDFE','#3C3489','#534AB7'],
    ];
    $grouped = $indikatorBulan->groupBy('kelompok');
?>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
<?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kel => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php [$bgK,$clK,$acK] = $kelompokWarna[$kel] ?? ['#f0f4ec','#333','#333']; ?>
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <div style="background:<?php echo e($bgK); ?>;padding:10px 14px;font-size:12px;font-weight:500;color:<?php echo e($clK); ?>;">
        <?php echo e($kelompokLabel[$kel] ?? $kel); ?>

    </div>
    <div style="padding:10px 14px;">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:0.5px solid #f0f4ec;">
            <div>
                <span style="background:<?php echo e($bgK); ?>;color:<?php echo e($clK); ?>;font-size:10px;padding:1px 6px;border-radius:10px;margin-right:4px;"><?php echo e($ind['kode']); ?></span>
                <span style="font-size:12px;color:#4a5568;"><?php echo e($ind['nama']); ?></span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:13px;font-weight:500;color:<?php echo e($ind['terisi'] ? $acK : '#a0aec0'); ?>;">
                    <?php echo e($ind['nilai'] !== null ? number_format($ind['nilai'], 1).' '.$ind['satuan'] : '—'); ?>

                </span>
                <?php if($ind['terisi']): ?>
                <span style="color:#27500A;font-size:14px;">✓</span>
                <?php else: ?>
                <span style="color:#a0aec0;font-size:14px;">○</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php if($indikatorBulan->where('terisi', false)->count() > 0): ?>
<div style="background:#FAEEDA;border:0.5px solid #e8c97a;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:13px;color:#633806;">⚠ Ada <?php echo e($indikatorBulan->where('terisi', false)->count()); ?> indikator belum diisi bulan ini.</span>
    <a href="<?php echo e(route('indikator.input', $masjidSaya)); ?>"
        style="background:#1C4A2A;color:#fff;border-radius:8px;padding:6px 14px;font-size:13px;text-decoration:none;">
        📋 Input Sekarang
    </a>
</div>
<?php else: ?>
<div style="background:#EAF3DE;border:0.5px solid #a8d070;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:13px;color:#27500A;">✅ Semua indikator bulan ini sudah terisi!</span>
    <a href="<?php echo e(route('masjid.show', $masjidSaya)); ?>"
        style="border:0.5px solid #ccc;background:#fff;color:#718096;border-radius:8px;padding:6px 14px;font-size:13px;text-decoration:none;">
        Lihat Detail
    </a>
</div>
<?php endif; ?>


<?php else: ?>


<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:16px;">

    
    <div style="background:#1C4A2A;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#fff;"><?php echo e(number_format($stats['total_masjid'])); ?></div>
        <div style="font-size:11px;color:#97C459;margin-top:4px;">Total Masjid</div>
    </div>

    
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e($stats['masjid_laporan']); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Sudah Laporan</div>
        <div style="font-size:11px;color:#97C459;"><?php echo e($stats['pct_laporan']); ?>%</div>
    </div>

    <?php if($user->isSuperAdmin() || $user->isAdminPP()): ?>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_pwm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PWM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_pdm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PDM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_pcm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PCM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_prm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    <?php elseif($user->isAdminPWM()): ?>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_pdm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PDM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_pcm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PCM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_prm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    <?php elseif($user->isAdminPDM()): ?>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_pcm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PCM</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_prm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    <?php elseif($user->isAdminPCM()): ?>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:28px;font-weight:500;color:#1C4A2A;"><?php echo e(number_format($stats['total_prm'])); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">PRM</div>
    </div>
    <?php endif; ?>

</div>


<?php if(!empty($stats['mu_kategori'])): ?>
<div style="margin-bottom:16px;">
    <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:8px;">⭐ Masjid Unggulan</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;">
        <?php $__currentLoopData = $stats['mu_kategori']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;">
            <div style="width:44px;height:44px;background:<?php echo e($mu['bg']); ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:20px;font-weight:600;color:<?php echo e($mu['cl']); ?>;"><?php echo e($mu['total']); ?></span>
            </div>
            <div>
                <div style="font-size:11px;font-weight:500;color:<?php echo e($mu['cl']); ?>;"><?php echo e($mu['label']); ?></div>
                <div style="font-size:10px;color:#718096;margin-top:2px;">masjid unggulan</div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px 16px;margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
        <span style="font-size:13px;font-weight:500;color:#1C4A2A;">📊 Progress Laporan <?php echo e($namaBulan[$bulanIni]); ?> <?php echo e($tahunIni); ?></span>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:13px;font-weight:500;color:#1C4A2A;"><?php echo e($stats['masjid_laporan']); ?> / <?php echo e($stats['total_masjid']); ?> masjid</span>
            <a href="<?php echo e(route('indikator.rekap.laporan', ['bulan'=>$bulanIni,'tahun'=>$tahunIni,'status'=>'belum'])); ?>"
                style="font-size:11px;color:#3B6D11;text-decoration:none;border:0.5px solid #a8d070;border-radius:6px;padding:2px 8px;">
                Lihat Semua →
            </a>
        </div>
    </div>
    <div style="height:10px;background:#f0f4ec;border-radius:5px;margin-bottom:6px;">
        <div style="height:100%;width:<?php echo e($stats['pct_laporan']); ?>%;background:#1C4A2A;border-radius:5px;transition:width 0.5s;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;font-size:11px;">
        <span style="color:#718096;"><?php echo e($stats['pct_laporan']); ?>% masjid sudah mengirim laporan bulan ini</span>
        <?php $belum = $stats['total_masjid'] - $stats['masjid_laporan']; ?>
        <?php if($belum > 0): ?>
        <span style="color:#633806;">⚠ <?php echo e($belum); ?> masjid belum laporan</span>
        <?php else: ?>
        <span style="color:#27500A;">✅ Semua masjid sudah laporan</span>
        <?php endif; ?>
    </div>
</div>


<?php if(isset($belumLaporan) && count($belumLaporan) > 0): ?>
<div style="background:#fff;border:0.5px solid #e8c97a;border-radius:12px;overflow:hidden;margin-bottom:16px;">
    <div style="background:#FFF4E6;padding:10px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:0.5px solid #e8c97a;">
        <span style="font-size:13px;font-weight:500;color:#633806;">⚠ Masjid Belum Laporan <?php echo e($namaBulan[$bulanIni]); ?> <?php echo e($tahunIni); ?></span>
        <a href="<?php echo e(route('indikator.rekap.laporan', ['bulan'=>$bulanIni,'tahun'=>$tahunIni,'status'=>'belum'])); ?>"
            style="font-size:12px;color:#633806;text-decoration:none;border:0.5px solid #e8c97a;border-radius:6px;padding:3px 10px;">
            Lihat Semua (<?php echo e($belum); ?>) →
        </a>
    </div>
    <?php $__currentLoopData = $belumLaporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 16px;border-bottom:0.5px solid #f0f4ec;">
        <div>
            <div style="font-size:13px;color:#1a202c;"><?php echo e($m->nama); ?></div>
            <div style="font-size:11px;color:#718096;"><?php echo e($m->prm->nama ?? '—'); ?> · <?php echo e($m->prm->pcm->nama ?? '—'); ?></div>
        </div>
        <a href="<?php echo e(route('indikator.input', [$m, 'bulan'=>$bulanIni, 'tahun'=>$tahunIni])); ?>"
            style="font-size:11px;background:#1C4A2A;color:#fff;border-radius:6px;padding:4px 10px;text-decoration:none;">
            📋 Input
        </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<?php endif; ?>


<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;">

    
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const trenData  = <?php echo json_encode($trenData, 15, 512) ?>;
const labels    = <?php echo json_encode($bulanLabel, 15, 512) ?>;

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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/dashboard/index.blade.php ENDPATH**/ ?>