<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class DonationApiController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat transaksi donasi baru via API (Checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:10000',
        ]);

        try {
            $campaign = Campaign::findOrFail($request->campaign_id);
            $orderId = 'FAS-' . time() . '-' . rand(100, 999);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $request->amount,
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s O'),
                    'unit' => 'minute',
                    'duration' => 15, // Waktu kedaluwarsa pembayaran (15 menit)
                ],
                'item_details' => [
                    [
                        'id' => $campaign->id,
                        'price' => (int) $request->amount,
                        'quantity' => 1,
                        'name' => substr('Donasi: ' . $campaign->title, 0, 50),
                    ]
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name ?? 'Donatur Anonim',
                    'email' => Auth::user()->email ?? 'anonim@forasmile.org',
                ],
            ];

            // Request token ke Midtrans
            $snapResponse = Snap::createTransaction($params);
            $snapToken = $snapResponse->token;
            $redirectUrl = $snapResponse->redirect_url;

            // Simpan data ke tabel transactions dengan status awal pending
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'campaign_id' => $campaign->id,
                'amount' => $request->amount,
                'payment_type' => 'midtrans',
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi donasi berhasil dibuat',
                'snap_token' => $snapToken,
                'redirect_url' => $redirectUrl, // URL ini yang akan dibuka di WebView Flutter / Postman
                'data' => $transaction
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi ke Midtrans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil riwayat transaksi milik user yang sedang terautentikasi
     */
    public function history()
    {
        try {
            $transactions = Transaction::with('campaign')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat transaksi berhasil diambil',
                'data' => $transactions
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}