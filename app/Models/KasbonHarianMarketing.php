<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasbonHarianMarketing extends Model
{
    use HasFactory;

    protected $table = 'kasbon_harian_marketing';
    protected $fillable = [
        'marketing_id',
        'nominal',
        'tanggal',
        'sisa_kasbon',
        'is_locked',
        'status'

    ];

    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }
}