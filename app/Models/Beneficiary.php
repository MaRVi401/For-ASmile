<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Beneficiary extends Model
{
    // Kolom yang diizinkan untuk mass-assignment
    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    /**
     * Relasi ke tabel Distributions.
     * Satu penerima manfaat bisa menerima santunan berkali-kali.
     */
    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class);
    }
}