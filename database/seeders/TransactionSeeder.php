<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data user biasa (bukan admin) untuk dijadikan donatur contoh
        // Jika tidak ada user biasa, ambil user pertama yang tersedia
        $donatur = User::where('is_admin', false)->first() ?? User::first();
        
        // Ambil kampanye yang aktif
        $campaign = Campaign::where('status', 'active')->first() ?? Campaign::first();

        if (!$donatur || !$campaign) {
            $this->command->warn('Gagal membuat seeder transaksi: Pastikan seeder User dan Campaign sudah dijalankan terlebih dahulu!');
            return;
        }

        // 1. Transaksi Contoh 1: Sukses via GoPay
        Transaction::create([
            'order_id' => 'FAS-' . time() . '-001',
            'user_id' => $donatur->id,
            'campaign_id' => $campaign->id,
            'amount' => 150000,
            'payment_type' => 'gopay',
            'status' => 'settlement', // 'settlement' atau 'success' berarti berhasil di Midtrans
            'proof_of_payment' => null,
            'midtrans_transaction_id' => (string) Str::uuid(),
        ]);

        // 2. Transaksi Contoh 2: Pending via Bank Transfer (BCA)
        Transaction::create([
            'order_id' => 'FAS-' . (time() + 1) . '-002',
            'user_id' => $donatur->id,
            'campaign_id' => $campaign->id,
            'amount' => 500000,
            'payment_type' => 'bank_transfer_bca',
            'status' => 'pending', // Masih menunggu pembayaran dari donatur
            'proof_of_payment' => null,
            'midtrans_transaction_id' => (string) Str::uuid(),
        ]);

        // 3. Transaksi Contoh 3: Gagal / Expired via ShopeePay
        Transaction::create([
            'order_id' => 'FAS-' . (time() + 2) . '-003',
            'user_id' => $donatur->id,
            'campaign_id' => $campaign->id,
            'amount' => 50000,
            'payment_type' => 'shopeepay',
            'status' => 'expire', // Waktu pembayaran habis / dibatalkan
            'proof_of_payment' => null,
            'midtrans_transaction_id' => (string) Str::uuid(),
        ]);

        // 4. Transaksi Contoh 4: Sukses via Mandiri Bill
        Transaction::create([
            'order_id' => 'FAS-' . (time() + 3) . '-004',
            'user_id' => $donatur->id,
            'campaign_id' => $campaign->id,
            'amount' => 1000000,
            'payment_type' => 'echannel_mandiri',
            'status' => 'settlement',
            'proof_of_payment' => null,
            'midtrans_transaction_id' => (string) Str::uuid(),
        ]);
    }
}