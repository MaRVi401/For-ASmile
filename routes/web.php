<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistributionController;
use App\Http\Controllers\Admin\BeneficiaryController;

// ==================== ROUTE HALAMAN UTAMA UNTUK TESTING ====================
use App\Models\Campaign;

Route::get('/', function () {
    // Ambil campaign aktif dan hitung total transaksi yang sukses ('settlement')
    $campaigns = Campaign::with('programs')
        ->withSum(['transactions' => function ($query) {
            $query->where('status', 'settlement'); // Menghitung hanya transaksi yang sudah bayar
        }], 'amount')
        ->latest()
        ->get();

    // Map hasil perhitungan transactions_sum_amount ke properti total_collected
    foreach ($campaigns as $campaign) {
        $campaign->total_collected = $campaign->transactions_sum_amount ?? 0;
    }

    return view('welcome', compact('campaigns'));
});

Route::get('/campaigns/{id}/distribution-modal', [DonationController::class, 'getDistributionModal'])
    ->name('campaigns.distribution-modal');

// ==================== ROUTE DONASI ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/campaign/{campaign}/donate', [DonationController::class, 'create'])->name('donation.create');
    Route::post('/donate', [DonationController::class, 'store'])->name('donation.store');
});

// ==================== ROUTE MIDTRANS WEBHOOK ====================
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handleNotification']);

// ==================== ROUTE AUTH ADMIN ====================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// ==================== ROUTE PROTECTED ADMIN (Wajib Login & Harus Admin) ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Halaman Dashboard Utama Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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

    // ==================== ROUTE EKSPOR LAPORAN TRANSAKSI ====================
    Route::get('transactions/export-excel', [TransactionController::class, 'exportExcel'])->name('admin.transactions.exportExcel');
    Route::get('transactions/export-pdf', [TransactionController::class, 'exportPdf'])->name('admin.transactions.exportPdf');

    // ==================== ROUTE LAPORAN TRANSAKSI ====================
    Route::resource('transactions', TransactionController::class)->only(['index', 'show'])->names([
        'index' => 'admin.transactions.index',
        'show' => 'admin.transactions.show',
    ]);

    // ==================== ROUTE PENYALURAN DONASI ====================
    Route::resource('distributions', DistributionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index'   => 'admin.distributions.index',
        'create'  => 'admin.distributions.create',
        'store'   => 'admin.distributions.store',
        'edit'    => 'admin.distributions.edit',
        'update'  => 'admin.distributions.update',
        'destroy' => 'admin.distributions.destroy',
    ]);

    // ==================== ROUTE PENERIMA SANTUNAN ====================
    Route::resource('beneficiaries', BeneficiaryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index'   => 'admin.beneficiaries.index',
        'create'  => 'admin.beneficiaries.create',
        'store'   => 'admin.beneficiaries.store',
        'edit'    => 'admin.beneficiaries.edit',
        'update'  => 'admin.beneficiaries.update',
        'destroy' => 'admin.beneficiaries.destroy',
    ]);
});
