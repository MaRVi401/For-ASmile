<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'campaign_id',
        'program_name',
        'description',
        'image_url',
    ];

    /**
     * Kebalikan Relasi: Tiap Program Kerja berinduk pada satu Kampanye Bulanan
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
}