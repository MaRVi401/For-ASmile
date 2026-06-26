<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class DonationController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    // Menampilkan halaman form donasi (asumsi melewatkan ID kampanye)
    public function create(Campaign $campaign)
    {
        return view('donations.create', compact('campaign'));
    }

    // Memproses pembuatan transaksi dan mengambil Snap Token
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:10000', // Minimal donasi Rp 10.000
        ]);

        $campaign = Campaign::findOrFail($request->campaign_id);
        $orderId = 'FAS-' . time() . '-' . rand(100, 999);

        // 1. Simpan data awal transaksi ke database dengan status 'pending'
        $transaction = Transaction::create([
            'order_id' => $orderId,
            'user_id' => Auth::id(), // null jika mengizinkan anonim
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'payment_type' => 'midtrans',
            'status' => 'pending',
        ]);

        // 2. Siapkan parameter untuk dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'), // Waktu mulai (saat ini)
                'unit' => 'minute', // Satuan waktu: 'minute', 'hour', atau 'day'
                'duration' => 1,   // Contoh: 60 menit (1 jam)
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

        try {
            // 3. Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Anda bisa mereturn token ini ke view atau mengirimkannya sebagai respon JSON jika menggunakan AJAX
            return view('donations.checkout', compact('transaction', 'snapToken', 'campaign'));
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghubungkan ke payment gateway: ' . $e->getMessage());
        }
    }
}
