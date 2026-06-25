<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. TABEL KAMPANYE (Wadah Bulanan Utama)
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Contoh: "Kampanye Bulan Mei 2026"
            $table->string('month', 7); // Format: "2026-05" untuk filter bulanan
            $table->decimal('target_amount', 12, 2); // Kebutuhan dana (e.g., 50000000.00)
            $table->decimal('current_amount', 12, 2)->default(0.00); // Dana terkumpul dari Midtrans
            $table->string('image_url')->nullable(); // Foto/Banner kampanye bulanan
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'upcoming', 'active', 'completed'])->default('draft');
            $table->timestamps();
        });

        // 2. TABEL PROGRAM (Aksi Spesifik di dalam Kampanye)
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan program ke wadah kampanye bulanan
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('program_name'); // Contoh: "Program Makan Bergizi"
            $table->text('description')->nullable();
            $table->string('image_url')->nullable(); // Foto/Banner kegiatan program
            $table->timestamps();
        });

        // 3. TABEL TRANSAKSI (Pencatatan Donasi & Integrasi Midtrans)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // ID unik untuk dikirim ke Midtrans (e.g., TRX-20260625-001)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Donatur yang masuk sistem
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade'); // Donasi dikunci di tingkat Kampanye Bulanan
            $table->decimal('amount', 12, 2); // Nominal donasi dengan presisi desimal keuangan
            $table->string('payment_type')->nullable(); // Jenis pembayaran (gopay, shopeepay, bank_transfer, dll)
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel', 'failed'])->default('pending');
            $table->string('proof_of_payment')->nullable(); // Kolom pelengkap untuk upload bukti bayar jika diperlukan manual
            $table->string('midtrans_transaction_id')->nullable(); // ID Transaksi resmi dari Midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Urutan drop dibalik untuk menghindari foreign key violation error
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('campaigns');
    }
};