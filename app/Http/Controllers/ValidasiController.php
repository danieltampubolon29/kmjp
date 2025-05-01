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

        $query = Pencairan::whereNull('tanggal_laporan');

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        $datas = $query->paginate(10);

        $totalNominal = $query->sum('nominal');

        return view('validasi.pencairan', compact('datas', 'totalNominal'));
    }

    public function angsuran()
    {
        $query = Angsuran::whereNull('tanggal_laporan');

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        $datas = $query->paginate(10);

        $totalNominal = $query->sum('nominal');

        return view('validasi.angsuran', compact('datas', 'totalNominal'));
    }

    public function simpanan()
    {
        $query = Simpanan::whereNull('tanggal_laporan');

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        $datas = $query->paginate(10);

        $totalNominal = $query->sum('nominal');

        return view('validasi.simpanan', compact('datas', 'totalNominal'));
    }

    public function validasiPencairan(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');


        $query = Pencairan::whereNull('tanggal_laporan');

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        $query->update(['tanggal_laporan' => $tanggalLaporan]);

        return redirect()->route('validasi.pencairan')->with('success', 'Validasi Pencairan berhasil dilakukan!');
    }
    public function ValidasiAngsuran(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');


        $query = Angsuran::whereNull('tanggal_laporan');

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        $query->update(['tanggal_laporan' => $tanggalLaporan]);
        return redirect()->route('validasi.angsuran')->with('success', 'Validasi Angsuran berhasil dilakukan!');
    }

    public function ValidasiSimpanan(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');


        $query = Simpanan::whereNull('tanggal_laporan');

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        $query->update(['tanggal_laporan' => $tanggalLaporan]);
        return redirect()->route('validasi.simpanan')->with('success', 'Validasi Simpanan berhasil dilakukan!');
    }
}
