<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handleNotification(Request $request)
    {
        // Menggunakan SHA512 signature key demi keamanan validasi data dari Midtrans
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $serverKey = config('midtrans.server_key');
        $signatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . $serverKey);

        if ($signatureKey !== $notification->signature_key) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transactionStatus = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        // Cari transaksi berdasarkan order_id asli proyek Anda
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Jalankan logika pembaruan status berdasarkan status transaksi dari Midtrans
        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $transaction->status = 'challenge';
                } else {
                    $transaction->status = 'success';
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->status = 'success';
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        } elseif ($transactionStatus == 'deny') {
            $transaction->status = 'failed';
        } elseif ($transactionStatus == 'expire') {
            $transaction->status = 'expired';
        } elseif ($transactionStatus == 'cancel') {
            $transaction->status = 'cancelled';
        }

        // Simpan tipe pembayaran spesifik dan ID Transaksi Midtrans
        $transaction->payment_type = $type;
        $transaction->midtrans_transaction_id = $notification->transaction_id;
        $transaction->save();

        return response()->json(['message' => 'Webhook handled successfully']);
    }
}