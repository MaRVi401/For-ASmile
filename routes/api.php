<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Api\UserProfileController;

// Route Publik (Tanpa Login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route Publik Lupa Password
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Route Publik Midtrans Webhook
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handleNotification']);

// Route Publik untuk Kampanye dan Donasi
Route::get('/campaigns', [DonationApiController::class, 'index']);
Route::get('/campaigns/{id}', [DonationApiController::class, 'show']);

// Route Terproteksi (Wajib membawa Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Endpoint testing untuk mengambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Endpoint untuk update profil user yang sedang login
    Route::post('/user/update', [UserProfileController::class, 'update']);


    // Endpoint untuk membuat donasi baru dan melihat riwayat donasi
    Route::post('/donations', [DonationApiController::class, 'store']);
    Route::get('/donations/history', [DonationApiController::class, 'history']);
});
