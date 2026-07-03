<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use App\Models\Beneficiary;
use App\Models\Distribution;
use Carbon\Carbon;

class BeneficiaryAndDistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === 0. BERSIHKAN DATA SEBELUMNYA (TRUNCATE) ===
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('distributions')->truncate();
        DB::table('beneficiaries')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // === 1. BUAT DATA DUMMY BENEFICIARIES (20 Penerima Manfaat) ===
        $beneficiariesData = [
            ['name' => 'Ahmad Fauzi', 'address' => 'Subang Kota RT 01/RW 03', 'phone' => '081234567801'],
            ['name' => 'Budi Santoso', 'address' => 'Kec. Cijambe, Subang', 'phone' => '081234567802'],
            ['name' => 'Siti Aminah', 'address' => 'Kampung Cigadung, Subang', 'phone' => '081234567803'],
            ['name' => 'Dewi Lestari', 'address' => 'Desa Cisalak, Subang', 'phone' => null],
            ['name' => 'Eko Prasetyo', 'address' => 'Kec. Pagaden, Subang', 'phone' => '081234567805'],
            ['name' => 'Farhan Hidayat', 'address' => 'Subang Kota RT 04/RW 01', 'phone' => '081234567806'],
            ['name' => 'Gita Permata', 'address' => 'Kec. Jalancagak, Subang', 'phone' => null],
            ['name' => 'Hendra Wijaya', 'address' => 'Kec. Kalijati, Subang', 'phone' => '081234567808'],
            ['name' => 'Indah Cahyani', 'address' => 'Dusun Sukamaju, Subang', 'phone' => '081234567809'],
            ['name' => 'Joko Susilo', 'address' => 'Kec. Purwadadi, Subang', 'phone' => null],
            ['name' => 'Kartika Sari', 'address' => 'Kampung Melayu, Subang', 'phone' => '081234567811'],
            ['name' => 'Lukman Hakim', 'address' => 'Kec. Pamanukan, Subang', 'phone' => '081234567812'],
            ['name' => 'Mega Utami', 'address' => 'Kec. Binong, Subang', 'phone' => '081234567813'],
            ['name' => 'Nugroho Adi', 'address' => 'Desa Sukamelang, Subang', 'phone' => null],
            ['name' => 'Oki Setiawan', 'address' => 'Kec. Ciasem, Subang', 'phone' => '081234567815'],
            ['name' => 'Putri Rahayu', 'address' => 'Subang Kota RT 02/RW 05', 'phone' => '081234567816'],
            ['name' => 'Rian Hidayat', 'address' => 'Kec. Tanjungsiang, Subang', 'phone' => '081234567817'],
            ['name' => 'Siska Amelia', 'address' => 'Dusun Karanganyar, Subang', 'phone' => null],
            ['name' => 'Taufik Hidayat', 'address' => 'Kec. Cibogo, Subang', 'phone' => '081234567819'],
            ['name' => 'Wulan Dari', 'address' => 'Kec. Sagalaherang, Subang', 'phone' => '081234567820'],
        ];

        // Tambahkan timestamps otomatis untuk bulk insert beneficiaries
        foreach ($beneficiariesData as &$beneficiary) {
            $beneficiary['created_at'] = Carbon::now();
            $beneficiary['updated_at'] = Carbon::now();
        }
        unset($beneficiary); // Unset reference

        DB::table('beneficiaries')->insert($beneficiariesData);

        // === 2. BUAT DATA DUMMY DISTRIBUTIONS (30 Pendistribusian) ===
        // Ambil ID dari database pasca-insert
        $campaignIds = DB::table('campaigns')->pluck('id')->toArray();
        $beneficiaryIds = DB::table('beneficiaries')->pluck('id')->toArray();

        // Fallback jika seeder utama belum dijalankan
        if (empty($campaignIds)) {
            $campaignId = DB::table('campaigns')->insertGetId([
                'title' => 'Kampanye Kebaikan Juni 2026',
                'month' => '2026-06',
                'target_amount' => 50000000.00,
                'current_amount' => 15000000.00,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $campaignIds = [$campaignId];
        }

        // Kumpulan catatan opsional untuk variasi data penyaluran paket kecil
        $noteTemplates = [
            'Penyaluran bantuan konsumsi harian.',
            'Subsidi pembelian paket sembako murah ringan.',
            'Bantuan dana transportasi berobat jalan.',
            'Pemberian santunan logistik darurat.',
            'Bantuan paket makan siang bergizi.',
            'Bantuan tunai ringan modal harian.',
            'Penyaluran kompensasi bantuan sosial bulanan.',
        ];

        $distributionsData = [];

        // Lakukan perulangan hingga menghasilkan 30 rekam pendistribusian dana
        for ($i = 0; $i < 30; $i++) {
            // Memilih Campaign ID secara acak dari yang tersedia (Juni atau Juli)
            $selectedCampaignId = $campaignIds[array_rand($campaignIds)];
            
            // Memilih Penerima secara acak dari 20 data beneficiaries di atas
            $selectedBeneficiaryId = $beneficiaryIds[array_rand($beneficiaryIds)];

            $distributionsData[] = [
                'campaign_id' => $selectedCampaignId,
                'beneficiary_id' => $selectedBeneficiaryId,
                'amount_distributed' => rand(20, 100) * 1000, // Menghasilkan kelipatan nominal antara 20,000 - 100,000
                'distributed_at' => Carbon::now()->subDays(rand(1, 15))->subHours(rand(1, 12)),
                'notes' => $noteTemplates[array_rand($noteTemplates)],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert semua data rekam distribusi secara kolektif
        DB::table('distributions')->insert($distributionsData);
    }
}