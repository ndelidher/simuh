<?php $__env->startSection('title', 'Daftar Masjid'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Daftar Masjid</h1>
        <p style="font-size:13px;color:#718096;">Kelola data masjid sesuai cakupan wilayah Anda · Total: <?php echo e($masjid->total()); ?> masjid</p>
    </div>
    <?php if(!auth()->user()->isAdminPP()): ?>
    <a href="<?php echo e(route('masjid.create')); ?>"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;">
        + Tambah Masjid
    </a>
    <?php endif; ?>
</div>


<form method="GET" action="<?php echo e(route('masjid.index')); ?>">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:12px 16px;margin-bottom:14px;">

    
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:10px;">
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">Cari nama masjid</label>
            <input type="text" name="nama" value="<?php echo e(request('nama')); ?>" placeholder="Ketik nama masjid..."
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;width:180px;">
        </div>
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">Kategori Unggulan</label>
            <select name="kategori" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:150px;">
                <option value="">Semua Kategori</option>
                <option value="MU_WILAYAH" <?php echo e(request('kategori')=='MU_WILAYAH'?'selected':''); ?>>MU Wilayah</option>
                <option value="MU_DAERAH"  <?php echo e(request('kategori')=='MU_DAERAH'?'selected':''); ?>>MU Daerah</option>
                <option value="MU_CABANG"  <?php echo e(request('kategori')=='MU_CABANG'?'selected':''); ?>>MU Cabang</option>
                <option value="MU_RANTING" <?php echo e(request('kategori')=='MU_RANTING'?'selected':''); ?>>MU Ranting</option>
            </select>
        </div>
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">Status</label>
            <select name="aktif" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:110px;">
                <option value="">Semua</option>
                <option value="1" <?php echo e(request('aktif')==='1'?'selected':''); ?>>Aktif</option>
                <option value="0" <?php echo e(request('aktif')==='0'?'selected':''); ?>>Non-aktif</option>
            </select>
        </div>
    </div>

    
    <?php if(auth()->user()->isSuperAdmin() || auth()->user()->isAdminPP()): ?>
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding-top:10px;border-top:0.5px solid #f0f4ec;">
        <span style="font-size:11px;color:#718096;align-self:center;">Filter wilayah:</span>

        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PWM</label>
            <select name="pwm_id" id="f_pwm" onchange="filterLoadPDM(this.value)"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:160px;">
                <option value="">Semua PWM</option>
                <?php $__currentLoopData = $pwmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(request('pwm_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PDM</label>
            <select name="pdm_id" id="f_pdm" onchange="filterLoadPCM(this.value)"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:160px;">
                <option value="">Semua PDM</option>
                <?php $__currentLoopData = $pdmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(request('pdm_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PCM</label>
            <select name="pcm_id" id="f_pcm" onchange="filterLoadPRM(this.value)"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:160px;">
                <option value="">Semua PCM</option>
                <?php $__currentLoopData = $pcmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(request('pcm_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PRM</label>
            <select name="prm_id" id="f_prm"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:160px;">
                <option value="">Semua PRM</option>
                <?php $__currentLoopData = $prmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(request('prm_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;align-self:flex-end;">Cari</button>
        <a href="<?php echo e(route('masjid.index')); ?>" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;align-self:flex-end;">Reset</a>
    </div>
    <?php else: ?>
    <div style="display:flex;gap:10px;padding-top:10px;border-top:0.5px solid #f0f4ec;">
        <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;">Cari</button>
        <a href="<?php echo e(route('masjid.index')); ?>" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">Reset</a>
    </div>
    <?php endif; ?>

</div>
</form>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;min-width:900px;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;width:32px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama Masjid</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;">PRM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;">PCM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;">PDM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;">PWM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:left;">Kategori</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:center;width:70px;">Status</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:10px 10px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $masjid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $katBg = ['MU_WILAYAH'=>'#EEEDFE','MU_DAERAH'=>'#E1F5EE','MU_CABANG'=>'#FAEEDA','MU_RANTING'=>'#FAECE7'][$m->kategori_unggulan] ?? '#f0f4ec';
                $katCl = ['MU_WILAYAH'=>'#3C3489','MU_DAERAH'=>'#085041','MU_CABANG'=>'#633806','MU_RANTING'=>'#4A1B0C'][$m->kategori_unggulan] ?? '#718096';
                $katLbl = ['MU_WILAYAH'=>'MU Wilayah','MU_DAERAH'=>'MU Daerah','MU_CABANG'=>'MU Cabang','MU_RANTING'=>'MU Ranting'][$m->kategori_unggulan] ?? '—';
            ?>
            <tr style="border-bottom:0.5px solid #f0f4ec;">
                <td style="padding:8px 10px;font-size:11px;color:#718096;"><?php echo e($masjid->firstItem() + $i); ?></td>
                <td style="padding:8px 10px;">
                    <div style="font-size:13px;font-weight:500;color:#1a202c;"><?php echo e($m->nama); ?></div>
                    <?php if($m->kota_kabupaten): ?>
                    <div style="font-size:11px;color:#718096;"><?php echo e($m->kota_kabupaten); ?></div>
                    <?php endif; ?>
                </td>
                <td style="padding:8px 10px;font-size:11px;color:#4a5568;"><?php echo e($m->prm->nama ?? '—'); ?></td>
                <td style="padding:8px 10px;font-size:11px;color:#4a5568;"><?php echo e($m->prm->pcm->nama ?? '—'); ?></td>
                <td style="padding:8px 10px;font-size:11px;color:#4a5568;"><?php echo e($m->prm->pcm->pdm->nama ?? '—'); ?></td>
                <td style="padding:8px 10px;font-size:11px;color:#4a5568;"><?php echo e($m->prm->pcm->pdm->pwm->nama ?? '—'); ?></td>
                <td style="padding:8px 10px;">
                    <?php if($m->kategori_unggulan): ?>
                    <span style="background:<?php echo e($katBg); ?>;color:<?php echo e($katCl); ?>;font-size:10px;padding:2px 8px;border-radius:20px;white-space:nowrap;">
                        <?php echo e($katLbl); ?>

                    </span>
                    <?php else: ?>
                    <span style="color:#718096;font-size:11px;">—</span>
                    <?php endif; ?>
                </td>
                <td style="padding:8px 10px;text-align:center;">
                    <?php if($m->aktif): ?>
                        <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:2px 8px;border-radius:20px;">Aktif</span>
                    <?php else: ?>
                        <span style="background:#F7C1C1;color:#791F1F;font-size:10px;padding:2px 8px;border-radius:20px;">Non-aktif</span>
                    <?php endif; ?>
                </td>
                <td style="padding:8px 10px;text-align:center;">
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <a href="<?php echo e(route('masjid.show', $m)); ?>"
                            style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;" title="Detail">👁</a>
                        <?php if(!auth()->user()->isAdminPP()): ?>
                        <a href="<?php echo e(route('masjid.edit', $m)); ?>"
                            style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;" title="Edit">✎</a>
                        <form method="POST" action="<?php echo e(route('masjid.destroy', $m)); ?>" onsubmit="return confirm('Hapus masjid <?php echo e(addslashes($m->nama)); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#A32D2D;background:transparent;cursor:pointer;font-size:13px;" title="Hapus">🗑</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="9" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                    Tidak ada data masjid ditemukan.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        <span>Menampilkan <?php echo e($masjid->firstItem() ?? 0); ?>–<?php echo e($masjid->lastItem() ?? 0); ?> dari <?php echo e($masjid->total()); ?> masjid</span>
        <?php echo e($masjid->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filterLoadPDM(pwmId) {
    const pdm = document.getElementById('f_pdm');
    const pcm = document.getElementById('f_pcm');
    const prm = document.getElementById('f_prm');
    if (pcm) pcm.innerHTML = '<option value="">Semua PCM</option>';
    if (prm) prm.innerHTML = '<option value="">Semua PRM</option>';
    if (!pwmId || !pdm) return;
    pdm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pdm-by-pwm/${pwmId}`)
        .then(r => r.json())
        .then(data => {
            pdm.innerHTML = '<option value="">Semua PDM</option>';
            data.forEach(d => pdm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
        });
}

function filterLoadPCM(pdmId) {
    const pcm = document.getElementById('f_pcm');
    const prm = document.getElementById('f_prm');
    if (prm) prm.innerHTML = '<option value="">Semua PRM</option>';
    if (!pdmId || !pcm) return;
    pcm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pcm-by-pdm/${pdmId}`)
        .then(r => r.json())
        .then(data => {
            pcm.innerHTML = '<option value="">Semua PCM</option>';
            data.forEach(d => pcm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
        });
}

function filterLoadPRM(pcmId) {
    const prm = document.getElementById('f_prm');
    if (!pcmId || !prm) return;
    prm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/prm-by-pcm/${pcmId}`)
        .then(r => r.json())
        .then(data => {
            prm.innerHTML = '<option value="">Semua PRM</option>';
            data.forEach(d => prm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
        });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/masjid/index.blade.php ENDPATH**/ ?>