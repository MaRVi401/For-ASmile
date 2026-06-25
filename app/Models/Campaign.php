<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;


class Campaign extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional, karena Laravel otomatis menjadikannya jamak)
    protected $table = 'campaigns';

    // Kolom yang diizinkan untuk diisi massal (Mass Assignment)
    protected $fillable = [
        'title',
        'month',
        'target_amount',
        'current_amount',
        'image_url',
        'description',
        'status',
    ];

    /**
     * Relasi ke model Program
     * Satu Kampanye Bulanan memuat banyak Program Kerja
     */
    public function programs()
    {
        return $this->hasMany(Program::class, 'campaign_id', 'id');
    }

    /**
     * Relasi ke model Transaction
     * Satu Kampanye Bulanan menerima banyak transaksi donasi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'campaign_id', 'id');
    }
}