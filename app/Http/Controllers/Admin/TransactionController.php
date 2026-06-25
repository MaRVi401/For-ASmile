<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Menampilkan seluruh riwayat donasi masuk
    public function index()
    {
        // Mengambil data transaksi beserta relasi user dan kampanye induknya
        $transactions = Transaction::with(['user', 'campaign'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    // Menampilkan detail spesifik dari satu transaksi (Opsional/Bagus untuk verifikasi manual jika dibutuhkan)
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'campaign']);
        return view('admin.transactions.show', compact('transaction'));
    }
}