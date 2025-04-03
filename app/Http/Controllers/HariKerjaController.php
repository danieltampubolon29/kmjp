<?php

namespace App\Http\Controllers;

use App\Models\HariKerja;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HariKerjaController extends Controller
{
    public function index()
    {
        $holidays = HariKerja::where('status', true)
            ->get(['tanggal', 'deskripsi'])
            ->toArray();

        return view('admin.hariKerja.index', compact('holidays'));
    }


    public function store(Request $request)
    {
       $validasi =  $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $hariKerja = HariKerja::where('tanggal', $request->tanggal)->first();

        if ($hariKerja) {
            $hariKerja->delete();
            return redirect()->route('hari-kerja.index')->with('success', 'Hari Libur Berhasil Diperbarui!');
        }

        HariKerja::create([
            'tanggal' => $request->tanggal,
            'status' => true,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('hari-kerja.index')->with('success', 'Hari Libur Berhasil Ditambahkan!');
    }
}
