<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KasbonHarianMarketing;

class ProgresController extends Controller
{
    public function rekapData()
    {
        $progres = KasbonHarianMarketing::where('marketing_id', Auth::id())
            ->where('status', false)
            ->sum('nominal');

        $totalNominal = KasbonHarianMarketing::where('marketing_id', Auth::id())
            ->where('status', true)
            ->sum('nominal');
        $totalSisaKasbon = KasbonHarianMarketing::where('marketing_id', Auth::id())
            ->where('status', true)
            ->sum('sisa_kasbon');

        $totalKasbon = $totalNominal - $totalSisaKasbon;
        return view('progres.rekap-data', compact('progres', 'totalKasbon'));
    }

    public function getPencairanData(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $marketingId = Auth::id();

        $pengambilanKasbon = DB::table('kasbon_harian_marketing')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('marketing_id', $marketingId)
            ->where('status', true)
            ->sum('nominal');

        $pengembalianKasbon = DB::table('kasbon_harian_marketing')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('marketing_id', $marketingId)
            ->where('status', true)
            ->sum('sisa_kasbon');

        $kasbonPerbulan = $pengambilanKasbon - $pengembalianKasbon;

        $pencairanData = Pencairan::whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->where('marketing_id', $marketingId)
            ->selectRaw('DATE(tanggal_laporan) as date, SUM(nominal) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $totalPencairan = Pencairan::whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->where('marketing_id', $marketingId)
            ->sum('nominal');

        $angsuranData = Angsuran::whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->where('marketing_id', $marketingId)
            ->selectRaw('DATE(tanggal_laporan) as date, SUM(nominal) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $totalAngsuran = Angsuran::whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->where('marketing_id', $marketingId)
            ->sum('nominal');

        if (Auth::user()->role === 'marketing') {
            $userId = Auth::id();
            $totalAnggota = DB::table('anggota')
                ->where('marketing_id', $userId)
                ->count();
            $totalSisaKredit = DB::table('pencairan')
                ->where('marketing_id', $userId)
                ->sum('sisa_kredit');
            $totalPencairanPending = DB::table('pencairan')
                ->where('marketing_id', $userId)
                ->where('status', 0)
                ->count();
            $totalSetor = DB::table('simpanan')
                ->where('marketing_id', $userId)
                ->where('jenis_transaksi', 'setor')
                ->sum('nominal');
            $totalTarik = DB::table('simpanan')
                ->where('marketing_id', $userId)
                ->where('jenis_transaksi', 'tarik')
                ->sum('nominal');
            $saldoSimpanan = $totalSetor - $totalTarik;
        } else {
            $totalAnggota = 0;
            $totalSisaKredit = 0;
            $totalPencairanPending = 0;
            $saldoSimpanan = 0;
        }

        return response()->json([
            'pencairan_data' => $pencairanData,
            'total_pencairan' => $totalPencairan,
            'angsuran_data' => $angsuranData,
            'total_angsuran' => $totalAngsuran,
            'totalAnggota' => $totalAnggota,
            'totalSisaKredit' => $totalSisaKredit,
            'totalPencairanPending' => $totalPencairanPending,
            'saldoSimpanan' => $saldoSimpanan,
            'pengambilan_kasbon' => $pengambilanKasbon, 
            'pengembalian_kasbon' => $pengembalianKasbon, 
            'kasbon_perbulan' => $kasbonPerbulan, 
        ]);
    }
}
