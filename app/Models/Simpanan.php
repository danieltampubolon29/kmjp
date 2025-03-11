<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'simpanan';
    protected $fillable = [
        'anggota_id',
        'pencairan_id',
        'marketing_id',
        'tanggal_transaksi',
        'jenis_transaksi',
        'jenis_simpanan',
        'nominal',
    ];
    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
    public function pencairan()
    {
        return $this->belongsTo(Pencairan::class, 'pencairan_id');
    }
}
