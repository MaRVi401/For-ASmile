<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // Menampilkan seluruh riwayat donasi masuk
    public function index()
    {
        // Mengambil data transaksi beserta relasi user dan kampanye induknya
        $transactions = Transaction::with(['user', 'campaign'])->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    // Menampilkan detail spesifik dari satu transaksi (Opsional/Bagus untuk verifikasi manual jika dibutuhkan)
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'campaign']);
        return view('admin.transactions.show', compact('transaction'));
    }
}