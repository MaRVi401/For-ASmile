<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset tabel transactions
        Schema::disableForeignKeyConstraints();
        Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        $donaturs = User::where('is_admin', false)->get();
        if ($donaturs->isEmpty()) {
            $donaturs = User::all();
        }

        $campaigns = Campaign::all();

        if ($donaturs->isEmpty() || $campaigns->isEmpty()) {
            $this->command->warn('Gagal membuat seeder transaksi: Pastikan seeder User dan Campaign sudah dijalankan terlebih dahulu!');
            return;
        }

        $paymentTypes = ['gopay', 'shopeepay', 'qris', 'bank_transfer_bca', 'bank_transfer_bni', 'echannel_mandiri'];
        $statuses = ['settlement', 'settlement', 'settlement', 'pending', 'expire'];
        $amounts = [25000, 50000, 100000, 150000, 250000, 500000, 1000000];
        $notesSample = [
            'Semoga berkah dan bermanfaat untuk sesama.',
            'Bismillah, semoga dilapangkan rezekinya.',
            'Doa terbaik untuk kelancaran program ini.',
            'Semoga menjadi amal jariyah.',
            null
        ];

        $counter = 100;
        
        // Kunci waktu saat ini (Hari Ini)
        $today = Carbon::now();

        foreach ($campaigns as $campaign) {
            $transactionCount = rand(5, 8);

            for ($i = 0; $i < $transactionCount; $i++) {
                $counter++;
                $selectedDonatur = $donaturs->random();
                $selectedStatus = $statuses[array_rand($statuses)];
                $selectedAmount = $amounts[array_rand($amounts)];
                $selectedPayment = $paymentTypes[array_rand($paymentTypes)];

                // Mengurangi waktu dari HARI INI secara bertahap ke belakang (0 s/d 7 hari lalu)
                $daysAgo = rand(0, 7);
                $hoursAgo = rand(0, 23);
                $minutesAgo = rand(1, 59);

                // Clone objek $today agar tidak mengubah variabel utama
                $transactionDate = $today->copy()
                    ->subDays($daysAgo)
                    ->subHours($hoursAgo)
                    ->subMinutes($minutesAgo);

                Transaction::create([
                    'order_id'                => 'FAS-' . $transactionDate->timestamp . '-' . $counter,
                    'user_id'                 => $selectedDonatur->id,
                    'campaign_id'             => $campaign->id,
                    'amount'                  => $selectedAmount,
                    'is_anonymous'            => (bool) rand(0, 1),
                    'notes'                   => $notesSample[array_rand($notesSample)],
                    'payment_type'            => $selectedPayment,
                    'status'                  => $selectedStatus,
                    'proof_of_payment'        => null,
                    'midtrans_transaction_id' => (string) Str::uuid(),
                    'created_at'              => $transactionDate,
                    'updated_at'              => $transactionDate,
                ]);
            }
        }
    }
}