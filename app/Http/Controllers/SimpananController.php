<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{

    public function getTransactions(Request $request)
    {
        $type = $request->query('type');
        $anggotaId = $request->query('anggota_id');

        if (!in_array($type, ['POKOK', 'WAJIB', 'SUKARELA', 'DEPOSITO'])) {
            return response()->json(['error' => 'Jenis simpanan tidak valid'], 400);
        }

        if (!$anggotaId) {
            return response()->json(['error' => 'ID Anggota diperlukan'], 400);
        }

        $transactions = Simpanan::where('jenis_simpanan', $type)
            ->where('anggota_id', $anggotaId)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return response()->json($transactions);
    }


    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Simpanan::query();

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        if ($search) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('no_anggota', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $simpanans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('simpanan.index', compact('simpanans', 'search'));
    }


    public function create()
    {
        return view('simpanan.create');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:Setor,Tarik',
            'jenis_simpanan' => 'required|in:Pokok,Wajib,Sukarela,Deposito',
            'nominal' => 'required|integer|min:1',
        ]);

        $anggota = Anggota::findOrFail($validatedData['anggota_id']);
        if ($validatedData['jenis_transaksi'] === 'Tarik') {
            if (($anggota->simpanan ?? 0) < $validatedData['nominal']) {
                return redirect()->back()->with('error', 'Penarikan gagal! Jumlah penarikan melebihi saldo simpanan.');
            }
        }

        if ($validatedData['jenis_transaksi'] === 'Setor') {
            $anggota->simpanan = ($anggota->simpanan ?? 0) + $validatedData['nominal'];
        } elseif ($validatedData['jenis_transaksi'] === 'Tarik') {
            $anggota->simpanan = ($anggota->simpanan ?? 0) - $validatedData['nominal'];
        }

        $anggota->save();
        $validatedData['marketing_id'] = Auth::id();
        $validatedData['pencairan_id'] = null;
        Simpanan::create($validatedData);
        return redirect()->route('simpanan.index')->with('success', 'Data simpanan berhasil ditambahkan.');
    }


    public function show($id)
    {
        $simpanan = Simpanan::findOrFail($id);
        if (Auth::user()->role === 'marketing') {
            if ($simpanan->marketing_id !== Auth::id()) {
                return redirect()->route('simpanan.index')->with('error', 'Anda tidak memiliki akses ke data ini.');
            }
        }
        return view('simpanan.show', compact('simpanan'));
    }


    public function edit($id)
    {
        $simpanan = Simpanan::findOrFail($id);
        return view('simpanan.edit', compact('simpanan'));
    }




    public function update(Request $request, $id)
    {
        $simpanan = Simpanan::findOrFail($id);
        $validatedData = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:Setor,Tarik',
            'jenis_simpanan' => 'required|in:Pokok,Wajib,Sukarela,Deposito',
            'nominal' => 'required|integer|min:1',
        ]);
        $anggotaLama = Anggota::findOrFail($simpanan->anggota_id);
        $nominalLama = $simpanan->nominal;
        if ($simpanan->jenis_transaksi === 'Setor') {
            $anggotaLama->simpanan = ($anggotaLama->simpanan ?? 0) - $nominalLama;
        } elseif ($simpanan->jenis_transaksi === 'Tarik') {
            $anggotaLama->simpanan = ($anggotaLama->simpanan ?? 0) + $nominalLama;
        }
        $anggotaLama->save();
        $anggotaBaru = Anggota::findOrFail($validatedData['anggota_id']);
        if ($validatedData['jenis_transaksi'] === 'Tarik') {
            if (($anggotaBaru->simpanan ?? 0) < $validatedData['nominal']) {
                $anggotaLama->simpanan = ($anggotaLama->simpanan ?? 0) + $nominalLama;
                $anggotaLama->save();
                return redirect()->back()->with('error', 'Penarikan gagal! Jumlah penarikan melebihi saldo simpanan.');
            }
        }
        if ($validatedData['jenis_transaksi'] === 'Setor') {
            $anggotaBaru->simpanan = ($anggotaBaru->simpanan ?? 0) + $validatedData['nominal'];
        } elseif ($validatedData['jenis_transaksi'] === 'Tarik') {
            $anggotaBaru->simpanan = ($anggotaBaru->simpanan ?? 0) - $validatedData['nominal'];
        }
        $anggotaBaru->save();
        $simpanan->update($validatedData);
        return redirect()->route('simpanan.show', ['simpanan' => $simpanan->id])->with('success', 'Data simpanan berhasil diperbarui.');
    }




    public function destroy($id)
    {
        $simpanan = Simpanan::findOrFail($id);
        if ($simpanan->pencairan_id !== null) {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('simpanan.show', ['simpanan' => $simpanan->id])
                    ->with('error', 'Hanya admin yang dapat menghapus data simpanan dari pencairan ini');
            }
        }
        $anggota = Anggota::findOrFail($simpanan->anggota_id);
        if ($simpanan->jenis_transaksi === 'Setor') {
            $anggota->simpanan = ($anggota->simpanan ?? 0) - $simpanan->nominal;
        } elseif ($simpanan->jenis_transaksi === 'Tarik') {
            $anggota->simpanan = ($anggota->simpanan ?? 0) + $simpanan->nominal;
        }
        $anggota->save();
        $simpanan->delete();
        return redirect()->route('simpanan.index')->with('success', 'Data simpanan berhasil dihapus.');
    }

    public function searchAnggota(Request $request)
    {
        $query = $request->input('q');
        $anggotas = Anggota::where('marketing_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%$query%")
                    ->orWhere('no_anggota', 'LIKE', "%$query%");
            })
            ->get(['id', 'no_anggota', 'nama']);
        return response()->json($anggotas);
    }

    public function lock($id)
    {
        $simpanan = Simpanan::findOrFail($id);
        if ($simpanan->is_locked) {
            $simpanan->update(['is_locked' => false]);
            $message = 'Data simpanan berhasil dibuka.';
        } else {
            $simpanan->update(['is_locked' => true]);
            $message = 'Data simpanan berhasil dikunci.';
        }
        return redirect()->route('simpanan.show', ['simpanan' => $simpanan->id])->with('success', $message);
    }

    public function getSimpananData(Request $request)
    {
        $anggotaId = $request->input('anggota_id');
        if (!$anggotaId) {
            return response()->json(['error' => 'ID Anggota tidak ditemukan'], 400);
        }
        $simpanan = [
            'pokok' => $this->calculateSimpanan($anggotaId, 'pokok'),
            'wajib' => $this->calculateSimpanan($anggotaId, 'wajib'),
            'sukarela' => $this->calculateSimpanan($anggotaId, 'sukarela'),
            'deposito' => $this->calculateSimpanan($anggotaId, 'deposito'),
        ];
        return response()->json($simpanan);
    }

    private function calculateSimpanan($anggotaId, $jenisSimpanan)
    {
        $totalSetor = Simpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan', $jenisSimpanan)
            ->where('jenis_transaksi', 'setor')
            ->sum('nominal');

        $totalTarik = Simpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan', $jenisSimpanan)
            ->where('jenis_transaksi', 'tarik')
            ->sum('nominal');

        return $totalSetor - $totalTarik;
    }

    public function getAllSimpanan($anggotaId)
    {
        $simpanan = Simpanan::where('anggota_id', $anggotaId)
            ->orderBy('tanggal_simpanan', 'asc')
            ->get();

        return response()->json($simpanan);
    }
}
