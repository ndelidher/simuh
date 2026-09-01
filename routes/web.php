<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasjidController;
use App\Http\Controllers\DataIndikatorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WilayahController;

// ─── Auth ─────────────────────────────────────────────────────────
Route::get('/',       [AuthController::class, 'showLogin'])->name('home');
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Semua route di bawah memerlukan auth ─────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── API Cascading Dropdown Wilayah ────────────────────────────
    Route::get('/api/pdm-by-pwm/{pwm_id}', function($pwm_id) {
        return \App\Models\Pdm::where('pwm_id', $pwm_id)
            ->where('aktif', true)->orderBy('nama')->get(['id','nama']);
    })->name('api.pdm');

    Route::get('/api/pcm-by-pdm/{pdm_id}', function($pdm_id) {
        return \App\Models\Pcm::where('pdm_id', $pdm_id)
            ->where('aktif', true)->orderBy('nama')->get(['id','nama']);
    })->name('api.pcm');

    Route::get('/api/prm-by-pcm/{pcm_id}', function($pcm_id) {
        return \App\Models\Prm::where('pcm_id', $pcm_id)
            ->where('aktif', true)->orderBy('nama')->get(['id','nama']);
    })->name('api.prm');

    // ── Wilayah ───────────────────────────────────────────────────
    Route::prefix('wilayah')->name('wilayah.')->group(function () {
        Route::get('/pwm',              [WilayahController::class, 'pwm'])->name('pwm');
        Route::get('/pdm',              [WilayahController::class, 'pdm'])->name('pdm');
        Route::get('/pcm',              [WilayahController::class, 'pcm'])->name('pcm');
        Route::get('/prm',              [WilayahController::class, 'prm'])->name('prm');
        Route::get('/import',           [WilayahController::class, 'importForm'])->name('import');
        Route::post('/import',          [WilayahController::class, 'importProses'])->name('import.proses');

        // PWM CRUD
        Route::get('/pwm/tambah',       [WilayahController::class, 'pwmCreate'])->name('pwm.create');
        Route::post('/pwm',             [WilayahController::class, 'pwmStore'])->name('pwm.store');
        Route::get('/pwm/{pwm}/edit',   [WilayahController::class, 'pwmEdit'])->name('pwm.edit');
        Route::put('/pwm/{pwm}',        [WilayahController::class, 'pwmUpdate'])->name('pwm.update');
        Route::delete('/pwm/{pwm}',     [WilayahController::class, 'pwmDestroy'])->name('pwm.destroy');

        // PDM CRUD
        Route::get('/pdm/tambah',       [WilayahController::class, 'pdmCreate'])->name('pdm.create');
        Route::post('/pdm',             [WilayahController::class, 'pdmStore'])->name('pdm.store');
        Route::get('/pdm/{pdm}/edit',   [WilayahController::class, 'pdmEdit'])->name('pdm.edit');
        Route::put('/pdm/{pdm}',        [WilayahController::class, 'pdmUpdate'])->name('pdm.update');
        Route::delete('/pdm/{pdm}',     [WilayahController::class, 'pdmDestroy'])->name('pdm.destroy');

        // PCM CRUD
        Route::get('/pcm/tambah',       [WilayahController::class, 'pcmCreate'])->name('pcm.create');
        Route::post('/pcm',             [WilayahController::class, 'pcmStore'])->name('pcm.store');
        Route::get('/pcm/{pcm}/edit',   [WilayahController::class, 'pcmEdit'])->name('pcm.edit');
        Route::put('/pcm/{pcm}',        [WilayahController::class, 'pcmUpdate'])->name('pcm.update');
        Route::delete('/pcm/{pcm}',     [WilayahController::class, 'pcmDestroy'])->name('pcm.destroy');

        // PRM CRUD
        Route::get('/prm/tambah',       [WilayahController::class, 'prmCreate'])->name('prm.create');
        Route::post('/prm',             [WilayahController::class, 'prmStore'])->name('prm.store');
        Route::get('/prm/{prm}/edit',   [WilayahController::class, 'prmEdit'])->name('prm.edit');
        Route::put('/prm/{prm}',        [WilayahController::class, 'prmUpdate'])->name('prm.update');
        Route::delete('/prm/{prm}',     [WilayahController::class, 'prmDestroy'])->name('prm.destroy');
    });

    // ── Masjid ────────────────────────────────────────────────────
    Route::prefix('masjid')->name('masjid.')->group(function () {
        Route::get('/',             [MasjidController::class, 'index'])->name('index');
        Route::get('/tambah',       [MasjidController::class, 'create'])->name('create')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
        Route::post('/',            [MasjidController::class, 'store'])->name('store')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
        Route::get('/{masjid}',     [MasjidController::class, 'show'])->name('show');
        Route::get('/{masjid}/edit',[MasjidController::class, 'edit'])->name('edit')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
        Route::put('/{masjid}',     [MasjidController::class, 'update'])->name('update')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
        Route::delete('/{masjid}',  [MasjidController::class, 'destroy'])->name('destroy')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
    });

    // ── Indikator ─────────────────────────────────────────────────
    Route::prefix('indikator')->name('indikator.')->group(function () {
        Route::get('/input/{masjid}', [DataIndikatorController::class, 'input'])->name('input')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
        Route::post('/input/{masjid}',[DataIndikatorController::class, 'simpan'])->name('simpan')
             ->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm,admin_masjid');
        Route::get('/rekap',          [DataIndikatorController::class, 'rekap'])->name('rekap');
        Route::get('/rekap-laporan',  [DataIndikatorController::class, 'rekapLaporan'])->name('rekap.laporan');
    });

    // ── User Management ───────────────────────────────────────────
    Route::prefix('user')->name('user.')->middleware('role:super_admin,admin_pwm,admin_pdm,admin_pcm,admin_prm')->group(function () {
        Route::get('/',            [UserController::class, 'index'])->name('index');
        Route::get('/tambah',      [UserController::class, 'create'])->name('create');
        Route::post('/',           [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',      [UserController::class, 'update'])->name('update');
        Route::delete('/{user}',   [UserController::class, 'destroy'])->name('destroy');
    });

});