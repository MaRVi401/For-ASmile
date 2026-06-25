<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'user_id',
        'campaign_id',
        'amount',
        'payment_type',
        'status',
        'proof_of_payment',
        'midtrans_transaction_id',
    ];

    /**
     * Relasi ke Donatur / User yang melakukan transaksi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke Kampanye Bulanan tempat donasi dialokasikan
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
}