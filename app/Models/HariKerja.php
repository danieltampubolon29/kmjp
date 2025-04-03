<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariKerja extends Model
{
    use HasFactory;

    protected $table = 'hari_kerja';
    protected $fillable = [
        'tanggal',      
        'status',     
        'deskripsi' 
    ];

}