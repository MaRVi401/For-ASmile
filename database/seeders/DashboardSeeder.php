<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua Campaign dan User yang sudah ada di database
        $campaigns = Campaign::all();
        $users = User::all();

        // Cek pengaman jika data belum ada
        if ($campaigns->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Harap jalankan seeder User dan Campaign terlebih dahulu sebelum seeder ini!');
            return;
        }

        $this->command->info('Memulai seeding data transaksi untuk grafik dashboard...');

        // 2. Tentukan rentang waktu 6 bulan terakhir (agar grafik penuh dan cantik)
        // Kita simulasikan total donasi meningkat tiap bulannya secara realistis
        $monthlyTargetDonations = [
            5 => ['count' => 8,  'min' => 20000,  'max' => 100000],   // 5 bulan lalu (Donasi kecil-kecil)
            4 => ['count' => 12, 'min' => 25000,  'max' => 150000],   // 4 bulan lalu
            3 => ['count' => 15, 'min' => 50000,  'max' => 250000],   // 3 bulan lalu (Mulai naik)
            2 => ['count' => 20, 'min' => 50000,  'max' => 500000],   // 2 bulan lalu
            1 => ['count' => 25, 'min' => 100000, 'max' => 750000],   // 1 bulan lalu
            0 => ['count' => 30, 'min' => 100000, 'max' => 1000000],  // Bulan sekarang (Puncak donasi)
        ];

        foreach ($monthlyTargetDonations as $monthsAgo => $config) {
            // Tentukan basis waktu bulan tersebut
            $targetMonth = Carbon::now()->subMonths($monthsAgo);

            for ($i = 0; $i < $config['count']; $i++) {
                // Ambil user dan campaign acak
                $user = $users->random();
                $campaign = $campaigns->random();

                // Generate nominal donasi acak sesuai rentang konfigurasi bulannya (kelipatan 5.000)
                $amount = rand($config['min'] / 5000, $config['max'] / 5000) * 5000;

                // Tentukan tanggal acak di dalam bulan tersebut
                $randomDay = rand(1, $targetMonth->daysInMonth);
                $createdAt = Carbon::create($targetMonth->year, $targetMonth->month, $randomDay, rand(0, 23), rand(0, 59), rand(0, 59));

                // Buat data transaksi sukses (settlement)
                Transaction::create([
                    'order_id' => 'FAS-' . $createdAt->timestamp . '-' . rand(100, 999),
                    'user_id' => $user->id,
                    'campaign_id' => $campaign->id,
                    'amount' => $amount,
                    'payment_type' => collect(['gopay', 'shopeepay', 'bank_transfer', 'qris'])->random(),
                    'status' => 'settlement', // WAJIB settlement agar masuk hitungan grafik
                    'proof_of_payment' => null,
                    'midtrans_transaction_id' => 'midtrans-id-' . uuid_create(),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $this->command->info('Seeding transaksi sukses untuk dashboard berhasil diselesaikan!');
    }
}