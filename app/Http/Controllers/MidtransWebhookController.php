<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handleNotification(Request $request)
    {
        // Ambil data langsung menggunakan wrapper bawaan Laravel (Anti-Null)
        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $type = $request->input('payment_type', 'transfer');

        // Validasi awal memastikan data tidak kosong
        if (!$orderId) {
            Log::warning('Webhook Midtrans masuk tanpa membawa Order ID.');
            return response()->json(['message' => 'Order ID tidak ditemukan dalam request'], 400);
        }

        // Cari data transaksi di database berdasarkan order_id asli
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::error('Transaksi dengan Order ID: ' . $orderId . ' tidak ditemukan di database.');
            return response()->json(['message' => 'Transaksi tidak terdaftar'], 404);
        }

        // Logika konversi status Midtrans ke kolom status database aplikasi
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $transaction->status = 'success'; // Ubah status menjadi success agar dibaca oleh Blade
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->status = 'failed';
        }

        // Simpan data pelengkap pembayaran
        $transaction->payment_type = $type;
        $transaction->midtrans_transaction_id = $request->input('transaction_id');
        
        // Eksekusi simpan ke database
        $transaction->save();

        Log::info('Webhook Berhasil! Order ID: ' . $orderId . ' kini berstatus: ' . $transaction->status);

        return response()->json(['message' => 'Status database berhasil disinkronkan']);
    }
}