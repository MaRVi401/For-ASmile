<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Program;
use Illuminate\Database\Seeder;

class DonationSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DATA KAMPANYE PERTAMA (Bulan Ini)
        $campaign1 = Campaign::create([
            'title' => 'Kampanye Kebaikan Juni 2026',
            'month' => '2026-06',
            'target_amount' => 50000000,
            'current_amount' => 15000000, // Angka contoh donasi masuk
            'image_url' => null, // Sementara null, bisa kamu ganti via admin nanti
            'description' => 'Wadah utama pengumpulan donasi untuk program operasional dan aksi sosial kemanusiaan sepanjang bulan Juni 2026.',
            'status' => 'active',
        ]);

        // Sub-Program untuk Kampanye Pertama
        Program::create([
            'campaign_id' => $campaign1->id,
            'program_name' => 'Program Berbagi Makan Siang Bergizi',
            'description' => 'Penyaluran paket makanan sehat dan bergizi gratis setiap hari Jumat untuk anak-anak yatim piatu dan pejuang jalanan.',
            'image_url' => null,
        ]);

        Program::create([
            'campaign_id' => $campaign1->id,
            'program_name' => 'Santunan Pendidikan Anak Pelosok',
            'description' => 'Bantuan alat tulis, seragam, dan beasiswa tunai untuk membantu anak-anak kurang mampu di daerah pinggiran Subang tetap bersekolah.',
            'image_url' => null,
        ]);


        // 2. DATA KAMPANYE KEDUA (Bulan Depan / Upcoming)
        $campaign2 = Campaign::create([
            'title' => 'Kampanye Peduli Sesama Juli 2026',
            'month' => '2026-07',
            'target_amount' => 45000000,
            'current_amount' => 0,
            'image_url' => null,
            'description' => 'Persiapan wadah penggalangan dana kemanusiaan untuk menyambut program kerja sosial di bulan Juli 2026.',
            'status' => 'upcoming',
        ]);

        // Sub-Program untuk Kampanye Kedua
        Program::create([
            'campaign_id' => $campaign2->id,
            'program_name' => 'Pengadaan Fasilitas Air Bersih Pedesaan',
            'description' => 'Pembangunan sumur bor dan pipanisasi air bersih untuk warga dusun yang sering mengalami krisis kekeringan.',
            'image_url' => null,
        ]);
    }
}