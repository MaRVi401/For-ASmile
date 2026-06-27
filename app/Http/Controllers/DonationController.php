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
            'amount' => 'required|numeric|min:10000', // Minimal donasi 10.000
        ]);

        $campaign = Campaign::findOrFail($request->campaign_id);
        $orderId = 'FAS-' . time() . '-' . rand(100, 999);

        // 1. Siapkan parameter Midtrans (Tanpa simpan ke DB dulu)
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 1, // Pastikan durasi sesuai kebutuhan satuannya menit atau jam
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
            // 2. Dapatkan Snap Token DULU
            $snapToken = Snap::getSnapToken($params);

            // 3. BARU simpan data transaksi ke database setelah sukses mendapatkan token
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'campaign_id' => $campaign->id,
                'amount' => $request->amount,
                'payment_type' => 'midtrans',
                'status' => 'pending',
            ]);

            return view('donations.checkout', compact('transaction', 'snapToken', 'campaign'));
        } catch (Exception $e) {
            // Jika koneksi ke Midtrans gagal, maka Transaction::create tidak akan pernah tereksekusi
            return back()->with('error', 'Gagal menghubungkan ke payment gateway: ' . $e->getMessage());
        }
    }
}
