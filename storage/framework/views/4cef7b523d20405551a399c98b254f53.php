<?php $__env->startSection('title', 'Data PCM'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Data PCM</h1>
        <p style="font-size:13px;color:#718096;">Pimpinan Cabang Muhammadiyah · <?php echo e($data->total()); ?> cabang</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="<?php echo e(route('wilayah.pcm.create')); ?>"
            style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;">
            + Tambah PCM
        </a>
        <a href="<?php echo e(route('wilayah.import')); ?>"
            style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:8px 16px;font-size:13px;color:#718096;text-decoration:none;">
            ⬆ Import Excel
        </a>
    </div>
</div>


<form method="GET" action="<?php echo e(route('wilayah.pcm')); ?>">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:12px 16px;display:flex;gap:10px;align-items:flex-end;margin-bottom:14px;">
    <div style="display:flex;flex-direction:column;gap:3px;">
        <label style="font-size:11px;color:#718096;">Filter PDM</label>
        <select name="pdm_id" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:200px;">
            <option value="">Semua PDM</option>
            <?php $__currentLoopData = $pdmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($p->id); ?>" <?php echo e(request('pdm_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;">Filter</button>
    <a href="<?php echo e(route('wilayah.pcm')); ?>" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">Reset</a>
</div>
</form>

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:40px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama PCM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Kode</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">PDM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Jml PRM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Status</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr style="border-bottom:0.5px solid #f0f4ec;">
                <td style="padding:9px 12px;font-size:12px;color:#718096;"><?php echo e($data->firstItem() + $i); ?></td>
                <td style="padding:9px 12px;font-size:13px;font-weight:500;"><?php echo e($row->nama); ?></td>
                <td style="padding:9px 12px;font-size:12px;color:#718096;"><?php echo e($row->kode); ?></td>
                <td style="padding:9px 12px;font-size:12px;color:#718096;"><?php echo e($row->pdm->nama ?? '—'); ?></td>
                <td style="padding:9px 12px;font-size:12px;text-align:center;"><?php echo e($row->prm_list_count); ?></td>
                <td style="padding:9px 12px;text-align:center;">
                    <?php if($row->aktif): ?>
                        <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:2px 8px;border-radius:20px;">Aktif</span>
                    <?php else: ?>
                        <span style="background:#F7C1C1;color:#791F1F;font-size:10px;padding:2px 8px;border-radius:20px;">Non-aktif</span>
                    <?php endif; ?>
                </td>
                <td style="padding:9px 12px;text-align:center;">
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <a href="<?php echo e(route('wilayah.pcm.edit', $row)); ?>"
                            style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;">✎</a>
                        <form method="POST" action="<?php echo e(route('wilayah.pcm.destroy', $row)); ?>" onsubmit="return confirm('Hapus PCM <?php echo e(addslashes($row->nama)); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#A32D2D;background:transparent;cursor:pointer;font-size:13px;">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" style="padding:40px;text-align:center;color:#718096;font-size:13px;">Belum ada data PCM.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        <?php echo e($data->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/wilayah/pcm.blade.php ENDPATH**/ ?>