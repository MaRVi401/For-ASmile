<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

// ==================== ROUTE AUTH ADMIN ====================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// ==================== ROUTE PROTECTED ADMIN (Wajib Login & Harus Admin) ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Halaman Dashboard Utama Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Tempat menaruh Route CRUD Custom kamu nantinya:
    // Route::resource('campaigns', CampaignController::class);
    // Route::resource('programs', ProgramController::class);
    // Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
});
