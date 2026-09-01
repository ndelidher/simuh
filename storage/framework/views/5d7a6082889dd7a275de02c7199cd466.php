<?php $__env->startSection('title', 'Detail Masjid — ' . $masjid->nama); ?>

<?php $__env->startSection('content'); ?>


<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:14px;">
    <a href="<?php echo e(route('masjid.index')); ?>" style="color:#3B6D11;text-decoration:none;">Daftar Masjid</a>
    <span>›</span>
    <span><?php echo e($masjid->nama); ?></span>
</div>


<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:52px;height:52px;background:#EAF3DE;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-building" style="font-size:24px;color:#3B6D11;"></i>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                <h1 style="font-size:18px;font-weight:500;margin:0;"><?php echo e($masjid->nama); ?></h1>
                <?php if($masjid->kategori_unggulan): ?>
                    <?php $kat = [
                        'MU_WILAYAH' => ['bg'=>'#EEEDFE','c'=>'#3C3489','l'=>'MU Wilayah'],
                        'MU_DAERAH'  => ['bg'=>'#E1F5EE','c'=>'#085041','l'=>'MU Daerah'],
                        'MU_CABANG'  => ['bg'=>'#FAEEDA','c'=>'#633806','l'=>'MU Cabang'],
                        'MU_RANTING' => ['bg'=>'#FAECE7','c'=>'#4A1B0C','l'=>'MU Ranting'],
                    ][$masjid->kategori_unggulan] ?? null; ?>
                    <?php if($kat): ?>
                    <span style="background:<?php echo e($kat['bg']); ?>;color:<?php echo e($kat['c']); ?>;font-size:11px;padding:2px 8px;border-radius:20px;"><?php echo e($kat['l']); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
                <span style="background:<?php echo e($masjid->aktif ? '#EAF3DE' : '#F7C1C1'); ?>;color:<?php echo e($masjid->aktif ? '#27500A' : '#791F1F'); ?>;font-size:11px;padding:2px 8px;border-radius:20px;">
                    <?php echo e($masjid->aktif ? 'Aktif' : 'Non-aktif'); ?>

                </span>
            </div>
            <div style="font-size:12px;color:#718096;">
                <?php echo e($masjid->prm->nama ?? '-'); ?> ·
                <?php echo e($masjid->prm->pcm->nama ?? '-'); ?> ·
                <?php echo e($masjid->prm->pcm->pdm->nama ?? '-'); ?> ·
                <?php echo e($masjid->prm->pcm->pdm->pwm->nama ?? '-'); ?>

            </div>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <?php if(!auth()->user()->isAdminPP()): ?>
        <a href="<?php echo e(route('masjid.edit', $masjid)); ?>"
            style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:7px 14px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;gap:5px;">
            ✎ Edit
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('indikator.input', $masjid)); ?>"
            style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:7px 14px;font-size:13px;font-weight:500;text-decoration:none;display:flex;align-items:center;gap:5px;">
            📋 Input Indikator
        </a>
    </div>
</div>


<?php
    $tahun = date('Y');
    $bulan = date('n');
    $dataIndikator = $masjid->dataIndikator()
        ->with('indikator')
        ->where('tahun', $tahun)
        ->where('bulan', $bulan)
        ->where('status', 'terkirim')
        ->get()
        ->keyBy('indikator.kode');
?>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;">
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:22px;font-weight:500;color:#1C4A2A;"><?php echo e($dataIndikator['JAMAAH_1']->nilai ?? '—'); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Rata-rata Jamaah Subuh</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:22px;font-weight:500;color:#1C4A2A;"><?php echo e($dataIndikator['JAMAAH_2']->nilai ?? '—'); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Rata-rata Jamaah Pengajian</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:22px;font-weight:500;color:#1C4A2A;">
            <?php if(isset($dataIndikator['JARIYAH_1'])): ?>
                Rp <?php echo e(number_format($dataIndikator['JARIYAH_1']->nilai, 2, ',', '.')); ?> jt
            <?php else: ?>—<?php endif; ?>
        </div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Infaq Bulan Ini (jt)</div>
    </div>
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:10px;padding:14px;text-align:center;">
        <div style="font-size:22px;font-weight:500;color:#1C4A2A;"><?php echo e($dataIndikator['JAMIYAH_3']->nilai ?? '—'); ?></div>
        <div style="font-size:11px;color:#718096;margin-top:4px;">Kegiatan Unggulan</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

    
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:16px;">
        <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:12px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
            ℹ Profil Masjid
        </div>
        <?php $rows = [
            ['Kode Masjid',    $masjid->kode],
            ['Tahun Berdiri',  $masjid->tahun_berdiri ?? '—'],
            ['Alamat',         $masjid->alamat ?? '—'],
            ['Kota/Kabupaten', $masjid->kota_kabupaten ?? '—'],
            ['Provinsi',       $masjid->provinsi ?? '—'],
            ['Kapasitas',      $masjid->kapasitas_jamaah ? number_format($masjid->kapasitas_jamaah) . ' jamaah' : '—'],
            ['Luas Bangunan',  $masjid->luas_bangunan ? $masjid->luas_bangunan . ' m²' : '—'],
            ['Status Tanah',   $masjid->status_tanah ?? '—'],
            ['Penetapan MU',   $masjid->tanggal_penetapan ? $masjid->tanggal_penetapan->format('d M Y') : '—'],
            ['─── Pengelola ───', null],
            ['Nama Pengelola', $masjid->nama_pengelola ?? '—'],
            ['No HP / WA',     $masjid->hp_pengelola ?? '—'],
            ['Email',          $masjid->email_pengelola ?? '—'],
            ['Website',        $masjid->website ?? '—'],
        ]; ?>
        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($r[1] === null): ?>
        <div style="padding:8px 0 4px;font-size:11px;color:#718096;font-weight:500;border-bottom:0.5px solid #f0f4ec;"><?php echo e($r[0]); ?></div>
        <?php else: ?>
        <div style="display:flex;padding:6px 0;border-bottom:0.5px solid #f0f4ec;font-size:12px;">
            <span style="color:#718096;width:120px;flex-shrink:0;"><?php echo e($r[0]); ?></span>
            <span style="color:#1a202c;"><?php echo e($r[1]); ?></span>
        </div>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:16px;">
        <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:12px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
            📊 Pemenuhan Indikator — <?php echo e(["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"][$bulan]); ?> <?php echo e($tahun); ?>

        </div>
        <?php
            $indikators = \App\Models\Indikator::where('aktif', true)->orderBy('urutan')->get();
            $warna = ['jamaah' => '#3B6D11', 'jariyah' => '#0F6E56', 'jamiyah' => '#534AB7'];
            $badge = ['jamaah' => ['#EAF3DE','#27500A'], 'jariyah' => ['#E1F5EE','#085041'], 'jamiyah' => ['#EEEDFE','#3C3489']];
        ?>
        <?php $__currentLoopData = $indikators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $d = $dataIndikator[$ind->kode] ?? null;
            $val = $d ? $d->nilai : null;
            $pct = $val ? min(100, ($val / max(1, $val)) * 100) : 0;
        ?>
        <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:0.5px solid #f0f4ec;">
            <span style="background:<?php echo e($badge[$ind->kelompok][0]); ?>;color:<?php echo e($badge[$ind->kelompok][1]); ?>;font-size:10px;padding:1px 6px;border-radius:10px;flex-shrink:0;width:60px;text-align:center;"><?php echo e($ind->kode); ?></span>
            <div style="flex:1;min-width:0;">
                <div style="font-size:11px;color:#4a5568;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($ind->nama); ?></div>
                <div style="height:6px;background:#f0f4ec;border-radius:3px;margin-top:3px;">
                    <div style="height:100%;width:<?php echo e($val ? '80%' : '0%'); ?>;background:<?php echo e($warna[$ind->kelompok]); ?>;border-radius:3px;"></div>
                </div>
            </div>
            <span style="font-size:12px;font-weight:500;color:#1a202c;min-width:50px;text-align:right;">
                <?php echo e($val !== null ? number_format($val, 1) . ' ' . $ind->satuan : '—'); ?>

            </span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:16px;margin-bottom:14px;">
    <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:4px;">📈 Tren Indikator 6 Bulan Terakhir</div>
    <div style="display:flex;gap:16px;margin-bottom:10px;font-size:11px;color:#718096;flex-wrap:wrap;">
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:3px;background:#1C4A2A;display:inline-block;border-radius:2px;"></span>Jamaah Subuh</span>
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:3px;background:#5DA632;border:1.5px dashed #5DA632;display:inline-block;border-radius:2px;"></span>Jamaah Pengajian</span>
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:10px;background:#0F6E56;display:inline-block;border-radius:2px;"></span>Infaq (jt Rp)</span>
        <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:10px;background:#97C459;display:inline-block;border-radius:2px;"></span>Alokasi (jt Rp)</span>
    </div>
    <div style="position:relative;height:220px;">
        <canvas id="chartTren"></canvas>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Ambil data 6 bulan terakhir
const trenData = <?php echo json_encode($trenData, 15, 512) ?>;
const bulanLabel = <?php echo json_encode($bulanLabel, 15, 512) ?>;

new Chart(document.getElementById('chartTren'), {
    type: 'line',
    data: {
        labels: bulanLabel,
        datasets: [
            {
                label: 'Jamaah Subuh',
                data: trenData['JAMAAH_1'] ?? [],
                borderColor: '#1C4A2A', backgroundColor: 'rgba(28,74,42,0.07)',
                borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true
            },
            {
                label: 'Jamaah Pengajian',
                data: trenData['JAMAAH_2'] ?? [],
                borderColor: '#5DA632', backgroundColor: 'rgba(93,166,50,0.05)',
                borderWidth: 2, borderDash: [4,3], pointRadius: 3, tension: 0.4
            },
            {
                label: 'Infaq (jt Rp)',
                data: trenData['JARIYAH_1'] ?? [],
                borderColor: '#0F6E56', backgroundColor: 'transparent',
                borderWidth: 2, pointRadius: 3, tension: 0.4, yAxisID: 'y2'
            },
            {
                label: 'Alokasi (jt Rp)',
                data: trenData['JARIYAH_2'] ?? [],
                borderColor: '#97C459', backgroundColor: 'transparent',
                borderWidth: 2, borderDash: [4,3], pointRadius: 3, tension: 0.4, yAxisID: 'y2'
            },
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { font: { size: 11 } } },
            y: { ticks: { font: { size: 11 } }, title: { display: true, text: 'Orang', font: { size: 10 }, color: '#888' } },
            y2: { position: 'right', ticks: { font: { size: 11 } }, grid: { display: false },
                  title: { display: true, text: 'Juta Rp', font: { size: 10 }, color: '#888' } }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/masjid/show.blade.php ENDPATH**/ ?>