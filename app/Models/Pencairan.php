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
        'no_anggota',
        'nama',
        'pinjaman_ke',
        'produk',
        'nominal',
        'tenor',
        'jatuh_tempo',
        'sisa_kredit',
        'tanggal_pencairan',
        'foto_pencairan',
        'foto_rumah',
        'marketing',
        'marketing_id',
        'status',
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
    public function angsuran(){
        return $this->hasMany(Angsuran::class);
    }

}