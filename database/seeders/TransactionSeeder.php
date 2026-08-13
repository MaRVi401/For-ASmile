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
        // 1. Reset tabel transactions secara aman
        Schema::disableForeignKeyConstraints();
        Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Ambil data User (Donatur) dan Campaign
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
        $notesSample = [
            'Semoga berkah dan bermanfaat untuk sesama.',
            'Bismillah, semoga dilapangkan rezekinya.',
            'Doa terbaik untuk kelancaran program ini.',
            'Semoga menjadi amal jariyah.',
            null
        ];

        // Konfigurasi target donasi per bulan (6 Bulan Terakhir hingga Hari Ini)
        $monthlyTargetDonations = [
            5 => ['count' => 10, 'min' => 20000,  'max' => 100000],   // 5 bulan lalu
            4 => ['count' => 15, 'min' => 25000,  'max' => 150000],   // 4 bulan lalu
            3 => ['count' => 18, 'min' => 50000,  'max' => 250000],   // 3 bulan lalu
            2 => ['count' => 22, 'min' => 50000,  'max' => 500000],   // 2 bulan lalu
            1 => ['count' => 28, 'min' => 100000, 'max' => 750000],   // 1 bulan lalu
            0 => ['count' => 35, 'min' => 100000, 'max' => 1000000],  // Bulan berjalan (sampai hari ini)
        ];

        $now = Carbon::now();
        $counter = 100;

        foreach ($monthlyTargetDonations as $monthsAgo => $config) {
            $targetMonth = Carbon::now()->subMonths($monthsAgo);
            
            // Pengaman agar tanggal tidak melebihi hari ini untuk bulan berjalan
            $maxDay = ($monthsAgo === 0) ? $now->day : $targetMonth->daysInMonth;

            for ($i = 0; $i < $config['count']; $i++) {
                $counter++;
                $selectedDonatur = $donaturs->random();
                $selectedCampaign = $campaigns->random();
                $selectedPayment = $paymentTypes[array_rand($paymentTypes)];
                
                // Variasi status: Mayoritas settlement agar grafik & dana terkumpul terisi
                $statuses = ['settlement', 'settlement', 'settlement', 'pending', 'expire'];
                $selectedStatus = $statuses[array_rand($statuses)];

                // Generate nominal acak (kelipatan 5.000)
                $amount = rand($config['min'] / 5000, $config['max'] / 5000) * 5000;

                // Tentukan tanggal & jam acak
                $randomDay = rand(1, max(1, $maxDay));
                $maxHour = ($monthsAgo === 0 && $randomDay === $now->day) ? $now->hour : 23;
                $randomHour = rand(0, max(0, $maxHour));

                $createdAt = Carbon::create(
                    $targetMonth->year,
                    $targetMonth->month,
                    $randomDay,
                    $randomHour,
                    rand(0, 59),
                    rand(0, 59)
                );

                Transaction::create([
                    'order_id'                => 'FAS-' . $createdAt->timestamp . '-' . $counter,
                    'user_id'                 => $selectedDonatur->id,
                    'campaign_id'             => $selectedCampaign->id,
                    'amount'                  => $amount,
                    'is_anonymous'            => (bool) rand(0, 1),
                    'notes'                   => $notesSample[array_rand($notesSample)],
                    'payment_type'            => $selectedPayment,
                    'status'                  => $selectedStatus,
                    'proof_of_payment'        => null,
                    'midtrans_transaction_id' => 'midtrans-id-' . Str::uuid(),
                    'created_at'              => $createdAt,
                    'updated_at'              => $createdAt,
                ]);
            }
        }
    }
}