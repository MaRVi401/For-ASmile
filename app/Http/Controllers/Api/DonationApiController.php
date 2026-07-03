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
     * API DAFTAR KAMPANYE (Untuk Beranda Mobile)
     * Menampilkan semua kampanye beserta progress bar data nominal terkumpul
     */
    public function index()
    {
        try {
            $campaigns = Campaign::with('programs')
                ->withSum(['transactions' => function ($query) {
                    $query->where('status', 'settlement');
                }], 'amount')
                ->latest()
                ->get();

            // Transformasi data untuk mempermudah pemrosesan di mobile app (Flutter/RN)
            $formattedData = $campaigns->map(function ($campaign) {
                $collected = $campaign->transactions_sum_amount ?? 0;
                $target = $campaign->target_amount ?? 0;
                $percentage = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;

                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'image_url' => $campaign->image_url ? asset('storage/' . $campaign->image_url) : null,
                    'target_amount' => $target,
                    'total_collected' => $collected,
                    'progress_percentage' => $percentage,
                    'total_programs' => $campaign->programs->count(),
                    'created_at' => $campaign->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Daftar kampanye berhasil diambil',
                'data' => $formattedData
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar kampanye: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API DETAIL KAMPANYE & LAPORAN DANA (Untuk Halaman Detail Mobile)
     * Menampilkan detail program kerja, total transparansi, dan riwayat penyaluran donasi
     */
    public function show($id)
    {
        try {
            // Eager load seluruh relasi program, distribusi, dan penerima santunan
            $campaign = Campaign::with(['programs', 'distributions.beneficiary'])
                ->withSum(['transactions' => function ($query) {
                    $query->where('status', 'settlement');
                }], 'amount')
                ->withSum('distributions', 'amount_distributed')
                ->findOrFail($id);

            $totalCollected = $campaign->transactions_sum_amount ?? 0;
            $totalDistributed = $campaign->distributions_sum_amount_distributed ?? 0;
            $balance = $totalCollected - $totalDistributed;

            // Memformat data keluaran JSON yang bersih untuk dibaca Mobile Developer
            $data = [
                'campaign_details' => [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'image_url' => $campaign->image_url ? asset('storage/' . $campaign->image_url) : null,
                    'target_amount' => $campaign->target_amount ?? 0,
                ],
                'transparency_report' => [
                    'total_collected' => $totalCollected,
                    'total_distributed' => $totalDistributed,
                    'remaining_balance' => $balance,
                ],
                'programs' => $campaign->programs->map(function ($program) {
                    return [
                        'id' => $program->id,
                        'program_name' => $program->program_name,
                        'description' => $program->description,
                        'image_url' => $program->image_url ? asset('storage/' . $program->image_url) : null,
                    ];
                }),
                'distribution_history' => $campaign->distributions->map(function ($dist) {
                    return [
                        'id' => $dist->id,
                        'amount_distributed' => $dist->amount_distributed,
                        'beneficiary_name' => $dist->beneficiary->name ?? 'Penerima Umum',
                        'notes' => $dist->notes ?? '-',
                        'date' => $dist->created_at->format('d M Y'),
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'message' => 'Detail kampanye dan laporan keuangan berhasil dimuat',
                'data' => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail kampanye: ' . $e->getMessage()
            ], 500);
        }
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
