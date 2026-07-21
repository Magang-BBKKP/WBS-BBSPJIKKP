<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

// Tracking Routes
Route::get('/track', [TrackingController::class, 'index'])->name('track.index');
Route::post('/track', [TrackingController::class, 'search'])->name('track.search');
Route::get('/track/{token}', [TrackingController::class, 'show'])->name('track.show');
Route::post('/track/{token}/evidence', [TrackingController::class, 'storeEvidence'])->name('track.evidence.store');
Route::get('/track/{token}/messages', [TrackingController::class, 'fetchMessages'])->name('track.messages.fetch');
Route::post('/track/{token}/messages', [TrackingController::class, 'storeMessage'])->name('track.message.store');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pelaporan (wajib login)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/buat',   [LaporanController::class, 'create'])->name('create');
        Route::post('/buat',  [LaporanController::class, 'store'])->name('store');
        Route::get('/sukses', [LaporanController::class, 'success'])->name('sukses');
        Route::get('/saya',   [LaporanController::class, 'myReports'])->name('saya');
    });

    // User Management
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', \App\Http\Controllers\UserController::class);

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
    });

    // Verification Laporan (Tim WBS only)
    Route::middleware([\App\Http\Middleware\EnsureUserIsTimWbs::class])->group(function () {
        Route::get('/verifikasi', [\App\Http\Controllers\VerificationController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{id}', [\App\Http\Controllers\VerificationController::class, 'show'])->name('verifikasi.show');
        Route::post('/verifikasi/{id}/validate', [\App\Http\Controllers\VerificationController::class, 'validateReport'])->name('verifikasi.validate');
        Route::post('/verifikasi/{id}/clarify', [\App\Http\Controllers\VerificationController::class, 'clarifyReport'])->name('verifikasi.clarify');
        Route::post('/verifikasi/{id}/reject', [\App\Http\Controllers\VerificationController::class, 'rejectReport'])->name('verifikasi.reject');
    });

    // Investigasi (Investigators and Super Admins)
    Route::resource('investigations', \App\Http\Controllers\InvestigationController::class)->only(['index', 'show']);
    Route::post('investigations/{id}/timeline', [\App\Http\Controllers\InvestigationController::class, 'storeTimeline'])->name('investigations.store-timeline');
    Route::post('investigations/{id}/document', [\App\Http\Controllers\InvestigationController::class, 'storeDocument'])->name('investigations.store-document');
    Route::get('investigations/{id}/document/{docId}/download', [\App\Http\Controllers\InvestigationController::class, 'downloadDocument'])->name('investigations.download-document');
    Route::post('investigations/{id}/result', [\App\Http\Controllers\InvestigationController::class, 'updateResult'])->name('investigations.update-result');

    // Kepala BBSPJIKKP — Pembentukan Tim Investigasi
    Route::prefix('kepala')->name('kepala.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KepalaController::class, 'index'])->name('index');
        Route::post('/laporan/{laporan_id}/assign', [\App\Http\Controllers\KepalaController::class, 'assign'])->name('assign');
    });

    // Tindak Lanjut (Kepala BBSPJIKKP)
    Route::prefix('tindak-lanjut')->name('tindak-lanjut.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TindakLanjutController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\TindakLanjutController::class, 'show'])->name('show');
        Route::post('/{id}', [\App\Http\Controllers\TindakLanjutController::class, 'store'])->name('store');
    });

    // Monitoring
    Route::get('/monitoring', [\App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring.index');

    // Master Data (Super Admin)
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/kategori', [\App\Http\Controllers\MasterDataController::class, 'index'])->name('index');
        Route::post('/kategori', [\App\Http\Controllers\MasterDataController::class, 'store'])->name('store');
        Route::put('/kategori/{kategori}', [\App\Http\Controllers\MasterDataController::class, 'update'])->name('update');
        Route::delete('/kategori/{kategori}', [\App\Http\Controllers\MasterDataController::class, 'destroy'])->name('destroy');
    });

    // Audit Log (Super Admin)
    Route::get('/audit-log', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-log.index');

    // Pengaturan Sistem (Super Admin)
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

