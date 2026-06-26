<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class MidtransWebhookController extends Controller
{
    public function handleNotification(Request $request)
    {
        $notification = json_decode($request->getContent());

        if (!$notification) {
            return response()->json(['message' => 'Empty payload'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $type = $notification->payment_type ?? 'transfer';

        // Cari transaksi berdasarkan order_id
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Logika penentuan status
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $transaction->status = 'success';
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->status = 'failed';
        }

        $transaction->payment_type = $type;
        if (isset($notification->transaction_id)) {
            $transaction->midtrans_transaction_id = $notification->transaction_id;
        }
        
        $transaction->save();

        return response()->json(['message' => 'Webhook updated successfully']);
    }
}