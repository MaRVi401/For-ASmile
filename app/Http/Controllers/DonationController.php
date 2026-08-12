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
            'campaign_id'  => 'required|exists:campaigns,id',
            'amount'       => 'required|numeric|min:10000',
            'is_anonymous' => 'nullable|boolean',
            'notes'        => 'nullable|string|max:500',
        ]);

        $campaign = Campaign::findOrFail($request->campaign_id);
        $orderId = 'FAS-' . time() . '-' . rand(100, 999);
        $isAnonymous = $request->boolean('is_anonymous');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 15,
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
                // Jika dikirim anonim, kirim 'Hamba Allah' ke Midtrans
                'first_name' => $isAnonymous ? 'Hamba Allah' : (Auth::user()->name ?? 'Donatur Anonim'),
                'email' => Auth::user()->email ?? 'anonim@forasmile.org',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $transaction = Transaction::create([
                'order_id'     => $orderId,
                'user_id'      => Auth::id(),
                'campaign_id'  => $campaign->id,
                'amount'       => $request->amount,
                'is_anonymous' => $isAnonymous,
                'notes'        => $request->notes,
                'payment_type' => 'midtrans',
                'status'       => 'pending',
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'snapToken' => $snapToken,
                    'transaction' => $transaction
                ]);
            }

            return view('donations.checkout', compact('transaction', 'snapToken', 'campaign'));
        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungkan ke payment gateway: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal menghubungkan ke payment gateway: ' . $e->getMessage());
        }
    }

    public function getDistributionModal($id)
    {
        // Eager loading distributions dan beneficiary, diurutkan dari yang terbaru
        $campaign = Campaign::with(['distributions' => function ($query) {
            $query->orderBy('distributed_at', 'desc')->with('beneficiary');
        }])
            ->withSum(['transactions' => function ($query) {
                $query->where('status', 'settlement');
            }], 'amount')
            ->withSum('distributions', 'amount_distributed')
            ->findOrFail($id);

        $campaign->total_collected = $campaign->transactions_sum_amount ?? 0;
        $campaign->total_distributed = $campaign->distributions_sum_amount_distributed ?? 0;
        $campaign->balance = $campaign->total_collected - $campaign->total_distributed;

        // Pastikan mengembalikan partial view yang dirender menjadi string HTML
        return view('donations.partials.distribution_content', compact('campaign'))->render();
    }
}
