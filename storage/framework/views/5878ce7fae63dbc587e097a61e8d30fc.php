<?php $__env->startSection('title', ($mode=='create'?'Tambah':'Edit') . ' PCM'); ?>
<?php $__env->startSection('content'); ?>

<div style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:10px;">
        <a href="<?php echo e(route('wilayah.pcm')); ?>" style="color:#3B6D11;text-decoration:none;">Data PCM</a>
        <span>›</span>
        <span><?php echo e($mode=='create' ? 'Tambah' : 'Edit'); ?> PCM</span>
    </div>
    <h1 style="font-size:20px;font-weight:500;"><?php echo e($mode=='create' ? 'Tambah' : 'Edit'); ?> PCM</h1>
</div>

<form method="POST" action="<?php echo e($mode=='create' ? route('wilayah.pcm.store') : route('wilayah.pcm.update', $data)); ?>">
<?php echo csrf_field(); ?>
<?php if($mode=='edit'): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PWM <span style="color:red;">*</span></label>
            <select id="sel_pwm" onchange="loadPDM(this.value)"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih PWM --</option>
                <?php $__currentLoopData = $pwmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(old('pwm_id', $data?->pdm?->pwm_id ?? '') == $p->id ? 'selected' : ''); ?>><?php echo e($p->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PDM <span style="color:red;">*</span></label>
            <select name="pdm_id" id="sel_pdm"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih PDM --</option>
                <?php $__currentLoopData = $pdmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(old('pdm_id', $data->pdm_id ?? '') == $p->id ? 'selected' : ''); ?>><?php echo e($p->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['pdm_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:11px;color:red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kode PCM <span style="color:red;">*</span></label>
            <input type="text" name="kode" value="<?php echo e(old('kode', $data->kode ?? '')); ?>" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            <?php $__errorArgs = ['kode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:11px;color:red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama PCM <span style="color:red;">*</span></label>
            <input type="text" name="nama" value="<?php echo e(old('nama', $data->nama ?? '')); ?>" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:11px;color:red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:8px;">Status</label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="aktif" id="aktif" value="1" <?php echo e(old('aktif', $data->aktif ?? true) ? 'checked' : ''); ?> style="width:16px;height:16px;">
                <label for="aktif" style="font-size:13px;">Aktif</label>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;">
    <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
        💾 <?php echo e($mode=='create' ? 'Simpan' : 'Perbarui'); ?>

    </button>
    <a href="<?php echo e(route('wilayah.pcm')); ?>" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">Batal</a>
</div>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
const existingPdmId = <?php echo e($data?->pdm_id ?? 'null'); ?>;
function loadPDM(pwmId, selectedId = null) {
    const pdm = document.getElementById('sel_pdm');
    if (!pwmId) { pdm.innerHTML = '<option value="">-- Pilih PDM --</option>'; return; }
    pdm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pdm-by-pwm/${pwmId}`)
        .then(r => r.json())
        .then(data => {
            pdm.innerHTML = '<option value="">-- Pilih PDM --</option>';
            data.forEach(d => {
                const sel = (selectedId && d.id == selectedId) || d.id == existingPdmId ? 'selected' : '';
                pdm.innerHTML += `<option value="${d.id}" ${sel}>${d.nama}</option>`;
            });
        });
}
window.addEventListener('DOMContentLoaded', () => {
    const pwmSel = document.getElementById('sel_pwm');
    if (pwmSel.value) loadPDM(pwmSel.value, existingPdmId);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/wilayah/pcm_form.blade.php ENDPATH**/ ?>