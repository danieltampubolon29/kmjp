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
        $validated = $request->validate([
            'tanggal' => 'required|array',
            'tanggal.*' => 'required|date',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        foreach ($validated['tanggal'] as $date) {
            HariKerja::updateOrCreate(
                ['tanggal' => $date],
                [
                    'status' => true,
                    'deskripsi' => $request->deskripsi,
                ]
            );
        }

        return redirect()->route('hari-kerja.index')->with('success', 'Hari Libur Berhasil Ditambahkan!');
    }
}
