<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handleNotification(Request $request)
    {
        // 1. Validasi Signature Key (Keamanan utama)
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::warning('Midtrans Webhook: Invalid signature untuk Order ID: ' . $request->order_id);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Ambil data dari request
        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $transactionId = $request->input('transaction_id');
        $paymentType = $request->input('payment_type');

        // 3. Cari transaksi
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::error('Midtrans Webhook: Transaksi tidak ditemukan di DB. Order ID: ' . $orderId);
            return response()->json(['message' => 'Transaksi tidak terdaftar'], 404);
        }

        // 4. Proteksi Status Final
        // Jika sudah 'settlement' atau 'failed', jangan ubah status lagi
        if (in_array($transaction->status, ['settlement', 'failed'])) {
            return response()->json(['message' => 'Status sudah final, tidak perlu update'], 200);
        }

        // 5. Update status berdasarkan mapping Midtrans
        switch ($transactionStatus) {
            case 'settlement':
            case 'capture':
                $transaction->status = 'settlement';
                break;
            case 'pending':
                $transaction->status = 'pending';
                break;
            case 'expire':
            case 'expired':
                $transaction->status = 'expire';
                break;
            case 'cancel':
                $transaction->status = 'cancel';
                break;
            case 'deny':
                $transaction->status = 'failed';
                break;
            default:
                Log::info('Midtrans Webhook: Status tidak dipetakan: ' . $transactionStatus);
                return response()->json(['message' => 'Status tidak dipetakan'], 200);
        }

        // 6. Simpan detail tambahan dan update database
        $transaction->payment_type = $paymentType ?? $transaction->payment_type;
        $transaction->midtrans_transaction_id = $transactionId;
        $transaction->save();

        Log::info('Midtrans Webhook: Berhasil update Order ID ' . $orderId . ' ke status ' . $transaction->status);

        return response()->json(['message' => 'OK']);
    }
}