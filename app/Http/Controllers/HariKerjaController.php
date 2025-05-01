<?php

namespace App\Http\Controllers;

use App\Models\HariKerja;
use Illuminate\Http\Request;

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
            $existingHoliday = HariKerja::where('tanggal', $date)->first();

            if ($existingHoliday) {
                $existingHoliday->delete();
            } else {
                HariKerja::create([
                    'tanggal' => $date,
                    'status' => true,
                    'deskripsi' => $request->deskripsi,
                ]);
            }
        }
        return redirect()->route('hari-kerja.index')->with('success', 'Hari Libur Berhasil Diupdate!');
    }
}
