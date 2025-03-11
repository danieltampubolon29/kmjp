<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $fillable = [
        'no_anggota',
        'nama',
        'tanggal_lahir',
        'alamat_ktp',
        'alamat_domisili',
        'no_hp',
        'tanggal_daftar',
        'marketing',
        'marketing_id',
        'is_locked',
        'foto_ktp',
        'foto_kk',
        'latitude',
        'longitude',
    ];

    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }
    public function pencairan()
    {
        return $this->hasMany(Pencairan::class);
    }
    public function simpanan()
    {
        return $this->hasMany(Simpanan::class);
    }
}
