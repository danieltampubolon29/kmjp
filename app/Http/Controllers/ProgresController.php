<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KasbonHarianMarketing;
use App\Models\User;
use Carbon\Carbon;


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

    public function rekapMarketing()
    {
        return view('progres.rekap-marketing');
    }



    public function targetHarian(Request $request)
    {
        Carbon::setLocale('id');
        $currentDay = Carbon::now()->isoFormat('dddd'); // Hari sekarang dalam bahasa Indonesia

        $query = Pencairan::query();

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }
        $query->where('jatuh_tempo', 'like', '%' . $currentDay . '%')
            ->orWhere('jatuh_tempo', 'harian')
            ->where('status', 0);
        $pencairans = $query->get();
        $filteredPencairans = $pencairans->filter(function ($pencairan) {
            return !$pencairan->angsuran()
                ->whereDate('tanggal_angsuran', Carbon::today())
                ->exists();
        });

        $paginatedPencairans = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredPencairans,
            $filteredPencairans->count(),
            10,
            $request->input('page', 1),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('progres.target-harian', compact('paginatedPencairans', 'currentDay'));
    }
    // rekap data role marketing
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

    // rekap data role admin
    public function getRekapMarketing(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        // Ambil data semua marketing
        $marketings = User::where('role', 'marketing')->get(['id', 'name']);

        $pencairanData = DB::table('pencairan')
            ->selectRaw('DATE(tanggal_laporan) as date, marketing_id, SUM(nominal) as total_nominal')
            ->whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->groupBy('date', 'marketing_id')
            ->get();

        $angsuranData = DB::table('angsuran')
            ->selectRaw('DATE(tanggal_laporan) as date, marketing_id, SUM(nominal) as total_nominal')
            ->whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->groupBy('date', 'marketing_id')
            ->get();

        // === Rekap Per Marketing ===
        $rekapPerMarketing = $marketings->map(function ($marketing) use ($month, $year) {
            $nasabahBaru = DB::table('pencairan')
                ->where('marketing_id', $marketing->id)
                ->whereMonth('tanggal_laporan', $month)
                ->whereYear('tanggal_laporan', $year)
                ->where('pinjaman_ke', 1)
                ->count();

            $jumlahPencairan = DB::table('pencairan')
                ->where('marketing_id', $marketing->id)
                ->where('status', 0)
                ->whereMonth('tanggal_laporan', $month)
                ->whereYear('tanggal_laporan', $year)
                ->count();


            $beforeStartOfMonth = Carbon::create($year, $month, 1)->subDay()->toDateString();

            $pencairanMarketing = DB::table('pencairan')
                ->where('marketing_id', $marketing->id)
                ->get(['id', 'sisa_kredit']);

            $saldoAwal = 0;

            foreach ($pencairanMarketing as $p) {
                $angsuranSetelah = DB::table('angsuran')
                    ->where('pencairan_id', $p->id)
                    ->whereDate('tanggal_laporan', '>', $beforeStartOfMonth)
                    ->sum('nominal');

                $saldoAwal += ($p->sisa_kredit + $angsuranSetelah);
            }

            $nominalPencairan = DB::table('pencairan')
                ->where('marketing_id', $marketing->id)
                ->whereMonth('tanggal_laporan', $month)
                ->whereYear('tanggal_laporan', $year)
                ->sum('nominal');

            $nasabahBayar = DB::table('angsuran')
                ->join('pencairan', 'angsuran.pencairan_id', '=', 'pencairan.id')
                ->where('pencairan.marketing_id', $marketing->id)
                ->whereMonth('angsuran.tanggal_laporan', $month)
                ->whereYear('angsuran.tanggal_laporan', $year)
                ->distinct('pencairan.id') 
                ->count('pencairan.id');

            $nominalAngsuran = DB::table('angsuran')
                ->where('marketing_id', $marketing->id)
                ->whereMonth('tanggal_laporan', $month)
                ->whereYear('tanggal_laporan', $year)
                ->sum('nominal');

            $saldoBerjalan = $saldoAwal - $nominalAngsuran ;
            return [
                'id' => $marketing->id,
                'name' => $marketing->name,
                'nasabah_baru' => $nasabahBaru,
                'saldo_awal' => $saldoAwal,
                'jumlah_pencairan' => $jumlahPencairan,
                'nominal_pencairan' => $nominalPencairan,
                'saldo_berjalan' => $saldoBerjalan,
                'nasabah_bayar' => $nasabahBayar,
                'nominal_angsuran' => $nominalAngsuran,
            ];
        });

        // === Rekap Total Keseluruhan ===
        $totalNasabahBaru = DB::table('pencairan')
            ->whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->where('pinjaman_ke', 1)
            ->count();

        $totalPencairan = DB::table('pencairan')
            ->whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->sum('nominal');

        $beforeStartOfMonth = Carbon::create($year, $month, 1)->subDay()->toDateString();

        $pencairanAll = DB::table('pencairan')
            ->select('id', 'sisa_kredit')
            ->get();

        $totalSaldoAwal = 0;

        foreach ($pencairanAll as $p) {
            $angsuranSetelah = DB::table('angsuran')
                ->where('pencairan_id', $p->id)
                ->whereDate('tanggal_laporan', '>', $beforeStartOfMonth)
                ->sum('nominal');

            $totalSaldoAwal += ($p->sisa_kredit + $angsuranSetelah);
        }

        $totalJumlahPencairan = DB::table('pencairan')
            ->where('status', 0)
            ->whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->count();

        $totalNasabahBayar = DB::table('angsuran')
            ->join('pencairan', 'angsuran.pencairan_id', '=', 'pencairan.id')
            ->whereMonth('angsuran.tanggal_laporan', $month)
            ->whereYear('angsuran.tanggal_laporan', $year)
            ->distinct('pencairan.id')
            ->count('pencairan.id');

        $totalNominalAngsuran = DB::table('angsuran')
            ->whereMonth('tanggal_laporan', $month)
            ->whereYear('tanggal_laporan', $year)
            ->sum('nominal');

        $totalSaldoBerjalan = $totalSaldoAwal - $totalNominalAngsuran;

        return response()->json([
            'marketings' => $marketings,
            'pencairanData' => $pencairanData,
            'angsuranData' => $angsuranData,
            'rekapUtama' => [
                'rekap_per_marketing' => $rekapPerMarketing,
                'total_nasabah_baru' => $totalNasabahBaru,
                'total_nominal_pencairan' => $totalPencairan,
                'total_jumlah_pencairan' => $totalJumlahPencairan,
                'total_saldo_berjalan' => $totalSaldoBerjalan,
                'total_nasabah_bayar' => $totalNasabahBayar,
                'total_nominal_angsuran' => $totalNominalAngsuran,
                'total_saldo_awal' => $totalSaldoAwal
            ]
        ]);
    }
}
