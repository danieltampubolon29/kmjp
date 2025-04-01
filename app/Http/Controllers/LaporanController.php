<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\KasbonHarianMarketing;
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
        $kasbon = KasbonHarianMarketing::where('marketing_id', Auth::id())
        ->where('status', false)
        ->sum('nominal');
        return view('laporan.harian', compact('kasbon'));
    }

    public function getPencairanByDate(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggal = $request->tanggal_laporan;
        $pencairan = Pencairan::whereDate('tanggal_laporan', $tanggal)
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
            'tanggal_laporan' => 'required|date',
        ]);

        $tanggalLaporan = $request->input('tanggal_laporan');

        $data = Angsuran::with('pencairan')
            ->where('tanggal_laporan', $tanggalLaporan)
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

    public function getFilteredData(Request $request)
    {
        $request->validate([
            'selected_date' => 'required|date',
        ]);

        $selectedDate = $request->input('selected_date');
        $marketingId = Auth::id();

        $totalAngsuran = Angsuran::where('marketing_id', $marketingId)
            ->where('tanggal_laporan', $selectedDate)
            ->sum('nominal');

        $totalPencairan = Pencairan::where('marketing_id', $marketingId)
            ->where('tanggal_laporan', $selectedDate)
            ->sum('nominal');

        $totalSimpananTop = Simpanan::where('marketing_id', $marketingId)
            ->whereIn('pencairan_id', function ($query) use ($marketingId, $selectedDate) {
                $query->select('id')
                    ->from('pencairan')
                    ->where('marketing_id', $marketingId)
                    ->where('tanggal_laporan', $selectedDate);
            })
            ->sum('nominal');

        $totalSimpananBottom = Simpanan::where('marketing_id', $marketingId)
            ->where('tanggal_laporan', $selectedDate)
            ->where('jenis_transaksi', 'tarik')
            ->sum('nominal');

        $administrasi = $totalPencairan * 0.05;

        return response()->json([
            'total_angsuran' => $totalAngsuran,
            'total_pencairan' => $totalPencairan,
            'total_simpanan_top' => $totalSimpananTop,
            'total_simpanan_bottom' => $totalSimpananBottom,
            'administrasi' => $administrasi,
        ]);
    }
}
