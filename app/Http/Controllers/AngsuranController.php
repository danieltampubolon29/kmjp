<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AngsuranController extends Controller
{

    public function getAngsuranData($pencairanId)
    {
        $angsuran = Angsuran::where('pencairan_id', $pencairanId)->get();
        return response()->json($angsuran);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Angsuran::query();

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        if ($search) {
            $query->whereHas('pencairan.anggota', function ($q) use ($search) {
                $q->where('no_anggota', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $angsurans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('angsuran.index', compact('angsurans', 'search'));
    }


    public function create(Request $request)
    {
        $pencairanId = $request->query('pencairan_id');
        return view('angsuran.create', compact('pencairanId'));
    }

    public function store(Request $request)
    {
        $pencairan = Pencairan::findOrFail($request->pencairan_id);
        $request->validate([
            'pencairan_id' => 'required|exists:pencairan,id',
            'angsuran_ke' => 'required|integer|min:1',
            'jenis_transaksi' => 'required|string',
            'nominal' => 'required|integer|min:1',
            'tanggal_angsuran' => 'required|date',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        if ($pencairan->sisa_kredit < $request->nominal) {
            return redirect()->back()->with('error', 'Nominal angsuran melebihi sisa kredit.');
        }

        $angsuran = Angsuran::create([
            'pencairan_id' => $request->pencairan_id,
            'angsuran_ke' => $request->angsuran_ke,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'tanggal_angsuran' => $request->tanggal_angsuran,
            'marketing_id' => Auth::id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $newSisaKredit = $pencairan->sisa_kredit - $request->nominal;
        $pencairan->update([
            'sisa_kredit' => $newSisaKredit,
            'status' => $newSisaKredit === 0 ? true : $pencairan->status,
        ]);

        return redirect()->route('angsuran.index')->with('success', 'Angsuran berhasil disimpan.');
    }

    public function show($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        if (Auth::user()->role === 'marketing') {
            if ($angsuran->marketing_id !== Auth::id()) {
                return redirect()->route('angsuran.index')->with('error', 'Anda tidak memiliki akses ke data ini.');
            }
        }
        return view('angsuran.show', compact('angsuran'));
    }


    public function edit($id)
    {
        $angsuran = Angsuran::findOrFail($id);
        return view('angsuran.edit', compact('angsuran'));
    }


    public function update(Request $request, Angsuran $angsuran)
    {
        $request->validate([
            'pencairan_id' => 'required|exists:pencairan,id',
            'angsuran_ke' => 'required|integer|min:1',
            'jenis_transaksi' => 'required|string',
            'nominal' => 'required|integer|min:1',
            'tanggal_angsuran' => 'required|date',
        ]);

        $pencairanLama = Pencairan::findOrFail($angsuran->pencairan_id);
        $pencairanLama->update([
            'sisa_kredit' => $pencairanLama->sisa_kredit + $angsuran->nominal,
        ]);
        $pencairanBaru = Pencairan::findOrFail($request->pencairan_id);

        if ($pencairanBaru->sisa_kredit < $request->nominal) {
            return redirect()->back()->with('error', 'Sisa kredit tidak mencukupi pada pencairan yang baru.');
        }
        $newSisaKredit = $pencairanBaru->sisa_kredit - $request->nominal;
        $pencairanBaru->update([
            'sisa_kredit' => $newSisaKredit,
            'status' => $newSisaKredit === 0 ? true : false,
        ]);

        $angsuran->update([
            'pencairan_id' => $request->pencairan_id,
            'angsuran_ke' => $request->angsuran_ke,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'tanggal_angsuran' => $request->tanggal_angsuran,
        ]);

        return redirect()->route('angsuran.index')->with('success', 'Angsuran berhasil diperbarui.');
    }




    public function destroy(Angsuran $angsuran)
    {
        if (Auth::user()->role === 'marketing') {
            if ($angsuran->marketing_id !== Auth::id()) {
                return redirect()->route('angsuran.index')->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
            }
        }

        $pencairan = $angsuran->pencairan;
        $newSisaKredit = $pencairan->sisa_kredit + $angsuran->nominal;
        $pencairan->update([
            'sisa_kredit' => $newSisaKredit,
            'status' => $newSisaKredit > 0 ? false : true,
        ]);

        $angsuran->delete();
        return redirect()->route('angsuran.index')->with('success', 'Angsuran berhasil dihapus dan sisa kredit telah diperbarui.');
    }

    public function lock($id)
    {
        $angsuran = Angsuran::findOrFail($id);

        if ($angsuran->is_locked) {
            $angsuran->update(['is_locked' => false]);
            $message = 'Data angsuran berhasil dibuka.';
        } else {
            $angsuran->update(['is_locked' => true]);
            $message = 'Data angsuran berhasil dikunci.';
        }
        return redirect()->route('angsuran.show', ['angsuran' => $angsuran->id])->with('success', $message);
    }


    public function searchPencairan(Request $request)
    {
        $query = $request->input('q');
        $angsuranId = $request->input('angsuran_id');

        $pencairans = Pencairan::where('marketing_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%$query%")
                    ->orWhere('no_anggota', 'LIKE', "%$query%");
            })
            ->get([
                'id',
                'no_anggota',
                'nama',
                'pinjaman_ke',
                'produk',
                'tenor',
                'sisa_kredit',
                'nominal'
            ]);

        $pencairans->transform(function ($pencairan) use ($angsuranId) {
            $angsuranList = Angsuran::where('pencairan_id', $pencairan->id)
                ->orderBy('angsuran_ke', 'asc')
                ->pluck('angsuran_ke')
                ->toArray();

            $angsuranKe = 1;
            for ($i = 1; $i <= count($angsuranList) + 1; $i++) {
                if (!in_array($i, $angsuranList)) {
                    $angsuranKe = $i;
                    break;
                }
            }

            if ($angsuranId) {
                $currentAngsuran = Angsuran::find($angsuranId);
                if ($currentAngsuran && $currentAngsuran->pencairan_id == $pencairan->id) {
                    $angsuranKe = $currentAngsuran->angsuran_ke;
                }
            }

            $pencairan->angsuran_ke = $angsuranKe;
            return $pencairan;
        });

        return response()->json($pencairans);
    }
}
