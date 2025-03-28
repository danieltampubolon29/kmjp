<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Simpanan;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function angsuran()
    {
        return view('laporan.angsuran');
    }

    public function pencairan()
    {
        return view('laporan.pencairan');
    }

    public function harian()
    {
        return view('laporan.harian');
    }

    public function getPencairanByDate(Request $request)
    {
        $request->validate([
            'tanggal_pencairan' => 'required|date',
        ]);

        $tanggal = $request->tanggal_pencairan;
        $pencairan = Pencairan::whereDate('tanggal_pencairan', $tanggal)
            ->where('marketing_id', Auth::id())
            ->select('id', 'nama', 'nominal', 'tenor', 'no_anggota')
            ->get();

        $data = $pencairan->map(function ($item) {
            $admin = $item->nominal * 0.05;
            $simpanan = Simpanan::where('pencairan_id', $item->id)->sum('nominal');
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'no_anggota' => $item->no_anggota,
                'nominal' => $item->nominal,
                'tenor' => $item->tenor,
                'admin' => $admin,
                'simpanan' => $simpanan > 0 ? $simpanan : "-",
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getAngsuranByDate(Request $request)
    {
        $request->validate([
            'tanggal_angsuran' => 'required|date',
        ]);

        $tanggalAngsuran = $request->input('tanggal_angsuran');

        $data = Angsuran::with('pencairan')
            ->where('tanggal_angsuran', $tanggalAngsuran)
            ->where('marketing_id', Auth::id())
            ->get()
            ->map(function ($angsuran) {
                return [
                    'id' => $angsuran->id,
                    'no_anggota' => $angsuran->pencairan->no_anggota,  
                    'pinjaman_ke' => $angsuran->pencairan->pinjaman_ke,  
                    'nama' => $angsuran->pencairan->nama, 
                    'nominal' => $angsuran->nominal, 
                    'angsuran_ke' => $angsuran->angsuran_ke,  
                    'tenor' => $angsuran->pencairan->tenor, 
                ];
            });

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data angsuran untuk tanggal tersebut.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
