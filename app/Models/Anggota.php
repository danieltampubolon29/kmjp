<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggota';
    protected $fillable = [
        'no_anggota', 'nama', 'tanggal_lahir', 'alamat_ktp', 'alamat_domisili', 
        'no_hp', 'pekerjaan', 'marketing', 'lokasi', 'foto_ktp', 'foto_kk'
    ];
}
