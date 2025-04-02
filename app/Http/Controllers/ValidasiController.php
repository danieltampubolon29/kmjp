<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Validasi;
use App\Models\Pencairan;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function pencairan()
    {
        $datas = Pencairan::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->paginate(10);

        $totalNominal = Pencairan::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->sum('nominal');

        return view('validasi.pencairan', compact('datas', 'totalNominal'));
    }

    public function angsuran()
    {
        $datas = Angsuran::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->paginate(10);

        $totalNominal = Angsuran::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->sum('nominal');

        return view('validasi.angsuran', compact('datas', 'totalNominal'));
    }

    public function simpanan()
    {
        $datas = Simpanan::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->paginate(10);

        $totalNominal = Simpanan::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->sum('nominal');
            
        return view('validasi.simpanan', compact('datas', 'totalNominal'));
    }

    public function validasiPencairan(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');

        Pencairan::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->update(['tanggal_laporan' => $tanggalLaporan]);

        return redirect()->route('validasi.pencairan')->with('success', 'Validasi Pencairan berhasil dilakukan!');
    }
    public function ValidasiAngsuran(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');

        Angsuran::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->update(['tanggal_laporan' => $tanggalLaporan]);
        return redirect()->route('validasi.angsuran')->with('success', 'Validasi Angsuran berhasil dilakukan!');
    }

    public function ValidasiSimpanan(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');

        Simpanan::where('marketing_id', Auth::id())
            ->whereNull('tanggal_laporan')
            ->update(['tanggal_laporan' => $tanggalLaporan]);
        return redirect()->route('validasi.simpanan')->with('success', 'Validasi Simpanan berhasil dilakukan!');
    }


    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Validasi $validasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Validasi $validasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Validasi $validasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Validasi $validasi)
    {
        //
    }
}
