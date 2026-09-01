<?php $__env->startSection('title', 'Rekap Pemenuhan Indikator'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Rekap Pemenuhan Indikator</h1>
        <p style="font-size:13px;color:#718096;">Data indikator masjid unggulan · Tahun <?php echo e($tahun); ?></p>
    </div>
    <a href="<?php echo e(route('indikator.rekap', ['tahun' => $tahun, 'export' => 1])); ?>"
        style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:8px 16px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;gap:6px;">
        ⬇ Export Excel
    </a>
</div>


<form method="GET" action="<?php echo e(route('indikator.rekap')); ?>">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:12px 16px;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:12px;">
    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Tahun</label>
        <select name="tahun" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:100px;">
            <?php $__currentLoopData = range(date('Y'), 2020, -1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($y); ?>" <?php echo e($y == $tahun ? 'selected' : ''); ?>><?php echo e($y); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <a href="<?php echo e(route('indikator.rekap')); ?>" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">
        Reset
    </a>
</div>
</form>


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


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:900px;">
            <thead>
                <tr>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:30px;">#</th>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;min-width:160px;">Nama Masjid</th>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:80px;">PRM</th>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:80px;">Kategori</th>
                    <?php $__currentLoopData = $indikators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 6px;border-bottom:0.5px solid #dde8d5;text-align:center;width:50px;"
                        title="<?php echo e($ind->nama); ?>">
                        <?php echo e($ind->kode); ?>

                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:9px 10px;border-bottom:0.5px solid #dde8d5;text-align:center;width:60px;">Skor</th>
                </tr>
                
                <tr style="background:#F4F7F1;">
                    <td colspan="4" style="font-size:10px;color:#a0aec0;padding:4px 10px;">Satuan →</td>
                    <?php $__currentLoopData = $indikators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td style="font-size:10px;color:#a0aec0;padding:4px 6px;text-align:center;"><?php echo e($ind->satuan); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <td style="font-size:10px;color:#a0aec0;padding:4px 10px;text-align:center;">%</td>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $masjidList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $dataM = $dataRekap[$m->id] ?? collect();
                    $terisi = 0;
                    $total  = $indikators->count();
                ?>
                <tr style="border-bottom:0.5px solid #f0f4ec;">
                    <td style="padding:8px 10px;font-size:12px;color:#718096;"><?php echo e($i + 1); ?></td>
                    <td style="padding:8px 10px;font-size:13px;font-weight:500;">
                        <a href="<?php echo e(route('masjid.show', $m)); ?>" style="color:#1C4A2A;text-decoration:none;"><?php echo e($m->nama); ?></a>
                    </td>
                    <td style="padding:8px 10px;font-size:12px;color:#718096;"><?php echo e($m->prm->nama ?? '-'); ?></td>
                    <td style="padding:8px 10px;">
                        <?php if($m->kategori_unggulan == 'MU_WILAYAH'): ?>
                            <span style="background:#EEEDFE;color:#3C3489;font-size:10px;padding:2px 6px;border-radius:20px;">Wilayah</span>
                        <?php elseif($m->kategori_unggulan == 'MU_DAERAH'): ?>
                            <span style="background:#E1F5EE;color:#085041;font-size:10px;padding:2px 6px;border-radius:20px;">Daerah</span>
                        <?php elseif($m->kategori_unggulan == 'MU_CABANG'): ?>
                            <span style="background:#FAEEDA;color:#633806;font-size:10px;padding:2px 6px;border-radius:20px;">Cabang</span>
                        <?php elseif($m->kategori_unggulan == 'MU_RANTING'): ?>
                            <span style="background:#FAECE7;color:#4A1B0C;font-size:10px;padding:2px 6px;border-radius:20px;">Ranting</span>
                        <?php else: ?>
                            <span style="background:#F1EFE8;color:#5F5E5A;font-size:10px;padding:2px 6px;border-radius:20px;">—</span>
                        <?php endif; ?>
                    </td>
                    <?php $__currentLoopData = $indikators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $d = $dataM->firstWhere('indikator_id', $ind->id);
                        $val = $d ? round($d->rata_tahun ?? $d->total_tahun, 1) : null;
                        if ($val !== null) $terisi++;
                        // Warna pill sederhana berdasarkan ada/tidak data
                        $bg  = $val === null ? '#F1F0EC' : ($val > 0 ? '#EAF3DE' : '#FCEBEB');
                        $clr = $val === null ? '#a0aec0' : ($val > 0 ? '#27500A' : '#A32D2D');
                    ?>
                    <td style="padding:8px 6px;text-align:center;">
                        <span style="background:<?php echo e($bg); ?>;color:<?php echo e($clr); ?>;font-size:11px;font-weight:500;padding:2px 6px;border-radius:4px;display:inline-block;min-width:34px;">
                            <?php echo e($val !== null ? $val : '—'); ?>

                        </span>
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $skor = $total > 0 ? round(($terisi / $total) * 100) : 0;
                        $skorBg  = $skor >= 80 ? '#EAF3DE' : ($skor >= 50 ? '#FAEEDA' : '#FCEBEB');
                        $skorClr = $skor >= 80 ? '#27500A' : ($skor >= 50 ? '#633806' : '#A32D2D');
                    ?>
                    <td style="padding:8px 10px;text-align:center;">
                        <span style="background:<?php echo e($skorBg); ?>;color:<?php echo e($skorClr); ?>;font-size:12px;font-weight:500;padding:3px 8px;border-radius:20px;">
                            <?php echo e($skor); ?>%
                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(5 + $indikators->count()); ?>" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                        Belum ada data masjid untuk ditampilkan.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        Menampilkan <?php echo e($masjidList->count()); ?> masjid · Tahun <?php echo e($tahun); ?>

    </div>
</div>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:16px;margin-top:14px;">
    <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:10px;">Keterangan Kode Indikator</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
        <?php $__currentLoopData = $indikators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span style="background:#EAF3DE;color:#27500A;padding:2px 7px;border-radius:4px;font-size:11px;font-weight:500;flex-shrink:0;"><?php echo e($ind->kode); ?></span>
            <span style="color:#4a5568;"><?php echo e($ind->nama); ?> <span style="color:#a0aec0;">(<?php echo e($ind->satuan); ?>)</span></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/indikator/rekap.blade.php ENDPATH**/ ?>