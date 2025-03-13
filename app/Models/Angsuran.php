<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsuran';
    protected $fillable = [
        'pencairan_id',
        'nominal',
        'tanggal_angsuran',
        'marketing_id',
        'is_locked',
        'latitude',
        'longitude',

    ];

    public function pencairan()
    {
        return $this->belongsTo(Pencairan::class, 'pencairan_id');
    }
}
