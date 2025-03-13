<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{

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
            'jenis_simpanan' => 'required|in:Pokok,Simpanan,Sukarela,Deposito',
            'nominal' => 'required|integer|min:1',
        ]);

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
        'jenis_simpanan' => 'required|in:Pokok,Simpanan,Sukarela,Deposito',
        'nominal' => 'required|integer|min:1',
    ]);

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
}
