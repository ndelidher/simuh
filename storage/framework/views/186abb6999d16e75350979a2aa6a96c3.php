<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'SIMUH'); ?> — Sistem Informasi Masjid Unggulan Muhammadiyah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --hijau-tua:   #1C4A2A;
            --hijau-mid:   #3B6D11;
            --hijau-aksen: #5DA632;
            --hijau-muda:  #EAF3DE;
            --sidebar-bg:  #F4F7F1;
        }
        body { font-size: 14px; background: #F5F5F2; }

        /* Topbar */
        .topbar { background: var(--hijau-tua); height: 52px; }
        .topbar-brand { font-size: 16px; font-weight: 600; color: #fff; }
        .topbar-sub   { font-size: 11px; color: rgba(255,255,255,.6); }
        .topbar-logo  { width:34px;height:34px;background:var(--hijau-aksen);border-radius:50%;
                        display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff; }
        .badge-role   { background: var(--hijau-aksen); color:#fff; font-size:11px;
                        padding:3px 10px; border-radius:20px; }

        /* Sidebar */
        #sidebar { width:220px;min-height:calc(100vh - 52px);background:var(--sidebar-bg);
                   border-right:1px solid #dde8d5;flex-shrink:0; }
        .sidebar-section { font-size:10px;color:#888;text-transform:uppercase;
                           letter-spacing:.08em;padding:10px 16px 4px;margin-top:4px; }
        .sidebar-link { display:flex;align-items:center;gap:8px;padding:8px 16px;
                        color:#4a5568;font-size:13px;text-decoration:none;border-left:3px solid transparent; }
        .sidebar-link:hover { background:rgba(28,74,42,.06);color:var(--hijau-tua); }
        .sidebar-link.active { background:rgba(28,74,42,.1);color:var(--hijau-tua);
                               border-left-color:var(--hijau-tua);font-weight:500; }
        .sidebar-link i { font-size:16px; }

        /* Content */
        #content { flex:1;padding:24px;overflow-y:auto; }
        .page-header { margin-bottom:20px; }
        .page-header h1 { font-size:20px;font-weight:600;color:#1a202c;margin:0; }
        .page-header p  { color:#718096;margin:2px 0 0; }

        /* Cards */
        .stat-card { background:#fff;border:1px solid #dde8d5;border-radius:10px;padding:16px;
                     transition:box-shadow .15s; }
        .stat-card:hover { box-shadow:0 2px 8px rgba(28,74,42,.1); }
        .stat-card .label { font-size:12px;color:#718096;margin-bottom:6px; }
        .stat-card .value { font-size:26px;font-weight:600;color:var(--hijau-tua); }
        .stat-card .sub   { font-size:11px;color:#a0aec0;margin-top:2px; }

        /* Alerts */
        .alert-success-simuh { background:var(--hijau-muda);border:1px solid #b7dca3;color:var(--hijau-tua); }

        /* Buttons */
        .btn-simuh         { background:var(--hijau-tua);color:#fff;border:none; }
        .btn-simuh:hover   { background:var(--hijau-mid);color:#fff; }
        .btn-simuh-outline { border:1px solid var(--hijau-tua);color:var(--hijau-tua);background:transparent; }
        .btn-simuh-outline:hover { background:var(--hijau-muda); }

        /* Table */
        .table-simuh thead th { background:var(--hijau-muda);color:var(--hijau-tua);
                                 font-weight:500;font-size:13px;border-color:#c3dbb5; }

        /* Badge kategori */
        .badge-wilayah { background:#EEEDFE;color:#3C3489; }
        .badge-daerah  { background:#E1F5EE;color:#085041; }
        .badge-cabang  { background:#FAEEDA;color:#633806; }
        .badge-ranting { background:#FAECE7;color:#4A1B0C; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<div class="topbar d-flex align-items-center px-3 gap-3">
    <div class="topbar-logo">M</div>
    <div>
        <div class="topbar-brand">SIMUH</div>
        <div class="topbar-sub">LPCRPM PP Muhammadiyah</div>
    </div>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="badge-role"><?php echo e(auth()->user()->role_label); ?></span>
        <span class="text-white" style="font-size:13px;"><?php echo e(auth()->user()->name); ?></span>
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm text-white border-0 bg-transparent p-0" title="Keluar">
                <i class="bi bi-box-arrow-right fs-5"></i>
            </button>
        </form>
    </div>
</div>

<div class="d-flex">
    
    <nav id="sidebar">
        <a href="<?php echo e(route('dashboard')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        
        <div class="sidebar-section">Data Wilayah</div>
        <a href="<?php echo e(route('wilayah.pwm')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('wilayah.pwm') ? 'active' : ''); ?>">
            <i class="bi bi-map"></i> Data PWM
        </a>
        <a href="<?php echo e(route('wilayah.pdm')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('wilayah.pdm') ? 'active' : ''); ?>">
            <i class="bi bi-map"></i> Data PDM
        </a>
        <a href="<?php echo e(route('wilayah.pcm')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('wilayah.pcm') ? 'active' : ''); ?>">
            <i class="bi bi-building"></i> Data PCM
        </a>
        <a href="<?php echo e(route('wilayah.prm')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('wilayah.prm') ? 'active' : ''); ?>">
            <i class="bi bi-house"></i> Data PRM
        </a>

        
        <div class="sidebar-section">Data Masjid</div>
        <a href="<?php echo e(route('masjid.index')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('masjid.*') ? 'active' : ''); ?>">
            <i class="bi bi-building"></i> Daftar Masjid
        </a>
        <a href="<?php echo e(route('indikator.rekap')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('indikator.rekap') && !request()->routeIs('indikator.rekap.laporan') ? 'active' : ''); ?>">
            <i class="bi bi-clipboard-data"></i> Pemenuhan Indikator
        </a>
        <a href="<?php echo e(route('indikator.rekap.laporan')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('indikator.rekap.laporan') ? 'active' : ''); ?>">
            <i class="bi bi-clipboard-check"></i> Rekap Laporan
        </a>

        
        <?php if(auth()->user()->isAdminMasjid()): ?>
        <div class="sidebar-section">Masjid Saya</div>
        <?php $masjidSaya = auth()->user()->masjid; ?>
        <?php if($masjidSaya): ?>
        <a href="<?php echo e(route('indikator.input', $masjidSaya)); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('indikator.input') ? 'active' : ''); ?>">
            <i class="bi bi-pencil-square"></i> Input Indikator
        </a>
        <?php endif; ?>
        <?php endif; ?>

        
        <?php if(!auth()->user()->isAdminPP() && !auth()->user()->isAdminMasjid()): ?>
        <div class="sidebar-section">Kelola</div>
        <a href="<?php echo e(route('user.index')); ?>"
            class="sidebar-link <?php echo e(request()->routeIs('user.*') ? 'active' : ''); ?>">
            <i class="bi bi-people"></i> Manajemen User
        </a>
        <?php endif; ?>
    </nav>

    
    <main id="content">
        <?php if(session('success')): ?>
        <div class="alert alert-success-simuh alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($e); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\simuh\resources\views/layouts/app.blade.php ENDPATH**/ ?>