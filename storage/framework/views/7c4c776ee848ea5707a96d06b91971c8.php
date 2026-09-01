<?php $__env->startSection('title', 'Data PWM'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Data PWM</h1>
        <p style="font-size:13px;color:#718096;">Pimpinan Wilayah Muhammadiyah · <?php echo e($data->total()); ?> wilayah</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="<?php echo e(route('wilayah.pwm.create')); ?>"
            style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;">
            + Tambah PWM
        </a>
        <a href="<?php echo e(route('wilayah.import')); ?>"
            style="border:0.5px solid #ccc;background:#fff;border-radius:8px;padding:8px 16px;font-size:13px;color:#718096;text-decoration:none;">
            ⬆ Import Excel
        </a>
    </div>
</div>

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:40px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama PWM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:120px;">Kode</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Jml PDM</th>
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
                <td style="padding:9px 12px;font-size:12px;text-align:center;"><?php echo e($row->pdm_list_count); ?></td>
                <td style="padding:9px 12px;text-align:center;">
                    <?php if($row->aktif): ?>
                        <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:2px 8px;border-radius:20px;">Aktif</span>
                    <?php else: ?>
                        <span style="background:#F7C1C1;color:#791F1F;font-size:10px;padding:2px 8px;border-radius:20px;">Non-aktif</span>
                    <?php endif; ?>
                </td>
                <td style="padding:9px 12px;text-align:center;">
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <a href="<?php echo e(route('wilayah.pwm.edit', $row)); ?>"
                            style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;" title="Edit">✎</a>
                        <form method="POST" action="<?php echo e(route('wilayah.pwm.destroy', $row)); ?>" onsubmit="return confirm('Hapus PWM <?php echo e(addslashes($row->nama)); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#A32D2D;background:transparent;cursor:pointer;font-size:13px;">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                    Belum ada data PWM. <a href="<?php echo e(route('wilayah.pwm.create')); ?>" style="color:#3B6D11;">+ Tambah PWM</a>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        <?php echo e($data->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/wilayah/pwm.blade.php ENDPATH**/ ?>