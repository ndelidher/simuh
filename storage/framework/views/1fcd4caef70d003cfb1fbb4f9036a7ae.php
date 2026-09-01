<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIMUH</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background:#EBF2E8; min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .login-card { background:#fff; border-radius:14px; border:1px solid #dde8d5; padding:36px 32px; width:360px; }
        .logo-wrap { width:56px;height:56px;background:#1C4A2A;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px; }
        .logo-inner { width:30px;height:30px;background:#5DA632;border-radius:50%; }
        .btn-login { background:#1C4A2A;color:#fff;border:none;width:100%;padding:10px;border-radius:8px;font-size:14px;font-weight:500; }
        .btn-login:hover { background:#3B6D11;color:#fff; }
        .form-control:focus { border-color:#5DA632;box-shadow:0 0 0 3px rgba(93,166,50,.15); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-wrap"><div class="logo-inner"></div></div>
        <h1 class="text-center fw-bold mb-1" style="font-size:20px;color:#1C4A2A;">SIMUH</h1>
        <p class="text-center text-muted mb-4" style="font-size:12px;">
            Sistem Informasi Masjid Unggulan Muhammadiyah<br>
            LPCRPM Pimpinan Pusat Muhammadiyah
        </p>

        <?php if($errors->has('login')): ?>
        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:13px;">
            <?php echo e($errors->first('login')); ?>

        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label text-muted" style="font-size:12px;">Username</label>
                <input type="text" name="username" class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('username')); ?>" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted" style="font-size:12px;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="mb-3 d-flex align-items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="text-muted" style="font-size:12px;">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <p class="text-center text-muted mt-3 mb-0" style="font-size:11px;">
            Lupa password? Hubungi administrator wilayah Anda.
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\simuh\resources\views/auth/login.blade.php ENDPATH**/ ?>