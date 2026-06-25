<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\TransactionController;

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

    // ==================== ROUTE KAMPANYE BULANAN ====================
    Route::resource('campaigns', CampaignController::class)->names([
        'index' => 'admin.campaigns.index',
        'create' => 'admin.campaigns.create',
        'store' => 'admin.campaigns.store',
        'edit' => 'admin.campaigns.edit',
        'update' => 'admin.campaigns.update',
        'destroy' => 'admin.campaigns.destroy',
    ]);

    // ==================== ROUTE PROGRAM KERJA ====================
    Route::resource('programs', ProgramController::class)->names([
        'index' => 'admin.programs.index',
        'create' => 'admin.programs.create',
        'store' => 'admin.programs.store',
        'edit' => 'admin.programs.edit',
        'update' => 'admin.programs.update',
        'destroy' => 'admin.programs.destroy',
    ]);

    // ==================== ROUTE LAPORAN TRANSAKSI ====================
    Route::resource('transactions', TransactionController::class)->only(['index', 'show'])->names([
        'index' => 'admin.transactions.index',
        'show' => 'admin.transactions.show',
    ]);
});
