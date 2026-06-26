<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    // Pastikan fillable atau guarded kamu sudah ada di sini
    protected $guarded = ['id'];

    /**
     * Relasi balik ke model Program
     * Setiap kampanye berada di bawah sebuah program tertentu
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}