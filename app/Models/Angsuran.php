<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsuran';
    protected $fillable = [
        'pencairan_id',
        'angsuran_ke',
        'jenis_transaksi',
        'nominal',
        'tanggal_angsuran',
        'marketing_id',
        'tanggal_laporan',
        'is_locked',
        'latitude',
        'longitude',

    ];

    public function pencairan()
    {
        return $this->belongsTo(Pencairan::class, 'pencairan_id');
    }
}
