<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairan extends Model
{
    use HasFactory;

    protected $table = 'pencairan';
    protected $fillable = [
        'anggota_id',
        'pinjaman_ke',
        'produk',
        'nominal',
        'tenor',
        'jatuh_tempo',
        'tanggal_pencairan',
        'foto_pencairan',
        'foto_rumah',
        'marketing',
        'marketing_id',
        'is_locked',
        'latitude',
        'longitude',
    ];

    public function marketingUser()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
    public function simpanan(){
        return $this->hasMany(Simpanan::class);
    }

}