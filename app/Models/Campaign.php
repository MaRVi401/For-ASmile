<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Memastikan import HasMany sudah ada

class Campaign extends Model
{
    // Memastikan id dijaga dari mass assignment
    protected $guarded = ['id'];

    /**
     * Relasi ke model Program (PERBAIKAN)
     * Satu wadah kampanye bulanan menampung banyak sub-program kegiatan kerja
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * Relasi ke model Transaction
     * Satu kampanye bulanan memiliki banyak transaksi donasi masuk
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}