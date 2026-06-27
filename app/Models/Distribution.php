<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    protected $fillable = [
        'campaign_id',
        'beneficiary_id',
        'amount_distributed',
        'notes',
        'distributed_at',
    ];

    // Cast ke tipe data Carbon instansiasi waktu agar mudah diformat di Blade view
    protected $casts = [
        'distributed_at' => 'datetime',
    ];

    /**
     * Relasi balik ke Kampanye Bulanan
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Relasi balik ke Penerima Manfaat
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }
}