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
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|array',
            'tanggal.*' => 'required|date',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        foreach ($validated['tanggal'] as $date) {
            // Cek apakah tanggal sudah ada di database
            $existingHoliday = HariKerja::where('tanggal', $date)->first();

            if ($existingHoliday) {
                // Jika sudah ada, hapus data tersebut
                $existingHoliday->delete();
            } else {
                // Jika belum ada, tambahkan sebagai hari libur baru
                HariKerja::create([
                    'tanggal' => $date,
                    'status' => true,
                    'deskripsi' => $request->deskripsi,
                ]);
            }
        }

        // Redirect dengan pesan sukses
        return redirect()->route('hari-kerja.index')->with('success', 'Hari Libur Berhasil Diupdate!');
    }
}
