<?php

namespace App\Http\Controllers;

use App\Models\HariKerja;
use Illuminate\Http\Request;

class HariKerjaController extends Controller
{
    public function index()
    {
        $holidays = HariKerja::where('status', true)->pluck('tanggal')->toArray();

        return view('admin.hariKerja.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_kerja,tanggal',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        HariKerja::create([
            'tanggal' => $request->tanggal,
            'status' => true,  
            'description' => $request->deskripsi
        ]);

        return redirect()->route('hari-kerja.index')->with('success', 'Hari Iibur berhasil ditambahkan!');
    }
    
}
