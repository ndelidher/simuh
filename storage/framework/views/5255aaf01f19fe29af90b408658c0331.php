<?php $__env->startSection('title', 'Tambah Masjid'); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:10px;">
        <a href="<?php echo e(route('masjid.index')); ?>" style="color:#3B6D11;text-decoration:none;">Daftar Masjid</a>
        <span>›</span>
        <span>Tambah Masjid</span>
    </div>
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Tambah Masjid Baru</h1>
    <p style="font-size:13px;color:#718096;">Isi data masjid dengan lengkap dan benar</p>
</div>

<?php
    // Tentukan apakah field terkunci sesuai role
    $lockPwm = $scopePwm !== null;
    $lockPdm = $scopePdm !== null;
    $lockPcm = $scopePcm !== null;
    $lockPrm = $scopePrm !== null;

    $styleLocked = "width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#EAF3DE;color:#27500A;font-weight:500;";
    $styleFree   = "width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#fff;";
    $styleDisabled = "width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#f9f9f9;";
?>

<form method="POST" action="<?php echo e(route('masjid.store')); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
        <div style="font-size:14px;font-weight:500;color:#1C4A2A;">📍 Lokasi Wilayah</div>
        <?php if($lockPwm || $lockPdm || $lockPcm || $lockPrm): ?>
        <span style="background:#EAF3DE;color:#27500A;font-size:11px;padding:2px 8px;border-radius:20px;">
            🔒 Otomatis sesuai akun Anda
        </span>
        <?php endif; ?>
    </div>
    <div style="font-size:12px;color:#718096;margin-bottom:16px;">
        <?php if($lockPrm): ?>
            Semua wilayah sudah terisi otomatis sesuai akun Anda.
        <?php else: ?>
            Pilih bertahap: PWM → PDM → PCM → PRM
        <?php endif; ?>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">
                PWM (Wilayah) <span style="color:red;">*</span>
                <?php if($lockPwm): ?><span style="color:#27500A;">🔒</span><?php endif; ?>
            </label>
            <?php if($lockPwm): ?>
                
                <div style="<?php echo e($styleLocked); ?>display:flex;align-items:center;"><?php echo e($scopePwm->nama); ?></div>
                <input type="hidden" name="_pwm_id" value="<?php echo e($scopePwm->id); ?>">
            <?php else: ?>
                <select id="sel_pwm" onchange="loadPDM(this.value)" style="<?php echo e($styleFree); ?>">
                    <option value="">-- Pilih PWM --</option>
                    <?php $__currentLoopData = $pwmList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e(old('_pwm_id') == $p->id ? 'selected' : ''); ?>><?php echo e($p->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>
        </div>

        
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">
                PDM (Daerah) <span style="color:red;">*</span>
                <?php if($lockPdm): ?><span style="color:#27500A;">🔒</span><?php endif; ?>
            </label>
            <?php if($lockPdm): ?>
                <div style="<?php echo e($styleLocked); ?>display:flex;align-items:center;"><?php echo e($scopePdm->nama); ?></div>
                <input type="hidden" name="_pdm_id" value="<?php echo e($scopePdm->id); ?>">
            <?php else: ?>
                <select id="sel_pdm" onchange="loadPCM(this.value)" disabled style="<?php echo e($styleDisabled); ?>">
                    <option value="">-- Pilih PDM --</option>
                </select>
            <?php endif; ?>
        </div>

        
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">
                PCM (Cabang) <span style="color:red;">*</span>
                <?php if($lockPcm): ?><span style="color:#27500A;">🔒</span><?php endif; ?>
            </label>
            <?php if($lockPcm): ?>
                <div style="<?php echo e($styleLocked); ?>display:flex;align-items:center;"><?php echo e($scopePcm->nama); ?></div>
                <input type="hidden" name="_pcm_id" value="<?php echo e($scopePcm->id); ?>">
            <?php else: ?>
                <select id="sel_pcm" onchange="loadPRM(this.value)" disabled style="<?php echo e($styleDisabled); ?>">
                    <option value="">-- Pilih PCM --</option>
                </select>
            <?php endif; ?>
        </div>

        
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">
                PRM (Ranting) <span style="color:red;">*</span>
                <?php if($lockPrm): ?><span style="color:#27500A;">🔒</span><?php endif; ?>
            </label>
            <?php if($lockPrm): ?>
                <div style="<?php echo e($styleLocked); ?>display:flex;align-items:center;"><?php echo e($scopePrm->nama); ?></div>
                <input type="hidden" name="prm_id" value="<?php echo e($scopePrm->id); ?>">
            <?php else: ?>
                <select id="sel_prm" name="prm_id" required disabled style="<?php echo e($styleDisabled); ?>">
                    <option value="">-- Pilih PRM --</option>
                </select>
                <?php $__errorArgs = ['prm_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:11px;color:red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php endif; ?>
        </div>

    </div>
</div>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        🕌 Identitas Masjid
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kode Masjid <span style="color:red;">*</span></label>
            <input type="text" name="kode" value="<?php echo e(old('kode')); ?>" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Contoh: MSJ-SKA-001">
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
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama Masjid <span style="color:red;">*</span></label>
            <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Nama lengkap masjid">
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
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Tahun Berdiri</label>
            <input type="number" name="tahun_berdiri" value="<?php echo e(old('tahun_berdiri')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Contoh: 1985" min="1800" max="<?php echo e(date('Y')); ?>">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Status Tanah</label>
            <select name="status_tanah" style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih --</option>
                <?php $__currentLoopData = ['Wakaf','Milik','Sewa','Pinjam']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($st); ?>" <?php echo e(old('status_tanah') == $st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div style="grid-column:span 2;">
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Alamat</label>
            <input type="text" name="alamat" value="<?php echo e(old('alamat')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Jalan, nomor, kelurahan">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kota / Kabupaten</label>
            <input type="text" name="kota_kabupaten" value="<?php echo e(old('kota_kabupaten')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Provinsi</label>
            <input type="text" name="provinsi" value="<?php echo e(old('provinsi')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
    </div>
</div>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        📐 Data Fisik
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Luas Tanah (m²)</label>
            <input type="number" name="luas_tanah" value="<?php echo e(old('luas_tanah')); ?>" min="0"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;" placeholder="0">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Luas Bangunan (m²)</label>
            <input type="number" name="luas_bangunan" value="<?php echo e(old('luas_bangunan')); ?>" min="0"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;" placeholder="0">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kapasitas Jamaah</label>
            <input type="number" name="kapasitas_jamaah" value="<?php echo e(old('kapasitas_jamaah')); ?>" min="0"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;" placeholder="0">
        </div>
    </div>
</div>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        📞 Kontak Pengelola / Takmir
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama Pengelola / Takmir</label>
            <input type="text" name="nama_pengelola" value="<?php echo e(old('nama_pengelola')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Nama ketua takmir / pengelola">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">No HP / WhatsApp</label>
            <input type="text" name="hp_pengelola" value="<?php echo e(old('hp_pengelola')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="08xxxxxxxxxx">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Email Pengelola</label>
            <input type="email" name="email_pengelola" value="<?php echo e(old('email_pengelola')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="email@contoh.com">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Website Masjid</label>
            <input type="url" name="website" value="<?php echo e(old('website')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="https://masjid.contoh.com">
        </div>
    </div>
</div>


<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:20px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        ⭐ Status Masjid Unggulan
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kategori Unggulan</label>
            <select name="kategori_unggulan" style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Belum ditetapkan --</option>
                <option value="MU_WILAYAH" <?php echo e(old('kategori_unggulan')=='MU_WILAYAH'?'selected':''); ?>>MU Wilayah (PWM)</option>
                <option value="MU_DAERAH"  <?php echo e(old('kategori_unggulan')=='MU_DAERAH'?'selected':''); ?>>MU Daerah (PDM)</option>
                <option value="MU_CABANG"  <?php echo e(old('kategori_unggulan')=='MU_CABANG'?'selected':''); ?>>MU Cabang (PCM)</option>
                <option value="MU_RANTING" <?php echo e(old('kategori_unggulan')=='MU_RANTING'?'selected':''); ?>>MU Ranting (PRM)</option>
            </select>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Tanggal Penetapan</label>
            <input type="date" name="tanggal_penetapan" value="<?php echo e(old('tanggal_penetapan')); ?>"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
    </div>
</div>


<div style="display:flex;gap:10px;">
    <button type="submit"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
        💾 Simpan Masjid
    </button>
    <a href="<?php echo e(route('masjid.index')); ?>"
        style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">
        Batal
    </a>
</div>

</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Cascading dropdown (hanya aktif untuk field yang tidak terkunci)
const lockPwm = <?php echo e($lockPwm ? 'true' : 'false'); ?>;
const lockPdm = <?php echo e($lockPdm ? 'true' : 'false'); ?>;
const lockPcm = <?php echo e($lockPcm ? 'true' : 'false'); ?>;
const lockPrm = <?php echo e($lockPrm ? 'true' : 'false'); ?>;

// Jika PDM terkunci tapi PCM bebas, load PCM otomatis saat halaman dibuka
const scopePdmId = <?php echo e($scopePdm?->id ?? 'null'); ?>;
const scopePcmId = <?php echo e($scopePcm?->id ?? 'null'); ?>;
const scopePwmId = <?php echo e($scopePwm?->id ?? 'null'); ?>;

function setLoading(el, text) { el.innerHTML = `<option value="">${text}</option>`; el.disabled = true; el.style.background = '#f9f9f9'; }
function setReady(el)         { el.disabled = false; el.style.background = '#fff'; }
function resetSelect(el, ph)  { el.innerHTML = `<option value="">${ph}</option>`; el.disabled = true; el.style.background = '#f9f9f9'; }

function loadPDM(pwmId) {
    if (lockPdm) return;
    const pdm = document.getElementById('sel_pdm');
    const pcm = document.getElementById('sel_pcm');
    const prm = document.getElementById('sel_prm');
    if (pcm) resetSelect(pcm, '-- Pilih PCM --');
    if (prm) resetSelect(prm, '-- Pilih PRM --');
    if (!pwmId) { resetSelect(pdm, '-- Pilih PDM --'); return; }
    setLoading(pdm, 'Memuat PDM...');
    fetch(`/api/pdm-by-pwm/${pwmId}`)
        .then(r => r.json())
        .then(data => {
            pdm.innerHTML = '<option value="">-- Pilih PDM --</option>';
            data.forEach(d => pdm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
            setReady(pdm);
        });
}

function loadPCM(pdmId) {
    if (lockPcm) return;
    const pcm = document.getElementById('sel_pcm');
    const prm = document.getElementById('sel_prm');
    if (prm) resetSelect(prm, '-- Pilih PRM --');
    if (!pdmId) { resetSelect(pcm, '-- Pilih PCM --'); return; }
    setLoading(pcm, 'Memuat PCM...');
    fetch(`/api/pcm-by-pdm/${pdmId}`)
        .then(r => r.json())
        .then(data => {
            pcm.innerHTML = '<option value="">-- Pilih PCM --</option>';
            data.forEach(d => pcm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
            setReady(pcm);
        });
}

function loadPRM(pcmId) {
    if (lockPrm) return;
    const prm = document.getElementById('sel_prm');
    if (!pcmId) { resetSelect(prm, '-- Pilih PRM --'); return; }
    setLoading(prm, 'Memuat PRM...');
    fetch(`/api/prm-by-pcm/${pcmId}`)
        .then(r => r.json())
        .then(data => {
            prm.innerHTML = '<option value="">-- Pilih PRM --</option>';
            data.forEach(d => prm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
            setReady(prm);
        });
}

// Auto load saat halaman dibuka jika ada scope terkunci
window.addEventListener('DOMContentLoaded', () => {
    if (lockPwm && !lockPdm && scopePwmId) loadPDM(scopePwmId);
    if (lockPdm && !lockPcm && scopePdmId) loadPCM(scopePdmId);
    if (lockPcm && !lockPrm && scopePcmId) loadPRM(scopePcmId);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simuh\resources\views/masjid/create.blade.php ENDPATH**/ ?>