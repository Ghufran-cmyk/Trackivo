<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExceptionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicTrackingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Trackivo Global Shipment Visibility Platform
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Pelacakan Resi Publik (Tanpa Autentikasi)
Route::get('/track', [PublicTrackingController::class, 'index'])->name('tracking.public');

// Authentication Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

    // Forgot & Reset Password Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:6,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->middleware('throttle:6,1')
        ->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -------------------------------------------------------------------------
    // Modul Pengaturan Akun & Profil (Semua Peran: Admin, Operator, Customer)
    // -------------------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // -------------------------------------------------------------------------
    // Modul Master Data (Khusus Administrator)
    // -------------------------------------------------------------------------
    Route::middleware('role:admin')->group(function () {
        Route::get('/locations/geocode', [LocationController::class, 'geocode'])->name('locations.geocode');
        Route::resource('carriers', CarrierController::class)->except(['show']);
        Route::resource('locations', LocationController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);

        // Pengaturan Global Delay Threshold
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Audit Logs (Khusus Administrator)
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // -------------------------------------------------------------------------
    // Modul Kendala Operasional (Exceptions) - Khusus Admin dan Operator
    // -------------------------------------------------------------------------
    Route::middleware('role:admin,operator')->group(function () {
        Route::get('/exceptions', [ExceptionController::class, 'index'])->name('exceptions.index');
        Route::patch('/exceptions/{exception}', [ExceptionController::class, 'update'])->name('exceptions.update');
    });

    // -------------------------------------------------------------------------
    // Modul Pengiriman (Shipments)
    // -------------------------------------------------------------------------
    // Read: Semua pengguna terautentikasi (Customer dibatasi di controller)
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');

    // Mutasi Data: Khusus Admin dan Operator
    Route::middleware('role:admin,operator')->group(function () {
        Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
        Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/shipments/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
        Route::put('/shipments/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
        Route::post('/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.status.update');

        // Checkpoint Tracking & Pelaporan Exception
        Route::post('/shipments/{shipment}/tracking', [ShipmentController::class, 'storeTracking'])->name('shipments.tracking.store');
        Route::post('/shipments/{shipment}/exceptions', [ShipmentController::class, 'storeException'])->name('shipments.exceptions.store');
    });

    // Hapus Shipment: Khusus Admin (dengan validasi status CREATED/CANCELLED)
    Route::delete('/shipments/{shipment}', [ShipmentController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('shipments.destroy');

    // Detail Pengiriman: Semua peran terautentikasi (Otorisasi Customer dicek di controller)
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');

    // -------------------------------------------------------------------------
    // Modul In-App Notifications (Semua Pengguna Terautentikasi)
    // -------------------------------------------------------------------------
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
