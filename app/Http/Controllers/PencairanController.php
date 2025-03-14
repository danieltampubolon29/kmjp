<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PencairanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Pencairan::query();

        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        if ($search) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('no_anggota', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $pencairans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pencairan.index', compact('pencairans', 'search'));
    }

    public function create()
    {
        return view('pencairan.create');
    }

    public function show($id)
    {
        $pencairan = Pencairan::findOrFail($id);

        if (Auth::user()->role === 'marketing') {
            if ($pencairan->marketing_id !== Auth::id()) {
                return redirect()->route('pencairan.index')->with('error', 'Anda tidak memiliki akses ke data ini.');
            }
        }
        return view('pencairan.show', compact('pencairan'));
    }

    public function edit($id)
    {
        $pencairan = Pencairan::findOrFail($id);
        return view('pencairan.edit', compact('pencairan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'no_anggota' => 'required',
            'nama' => 'required',
            'produk' => 'required|in:Harian,Mingguan',
            'nominal' => 'required|integer|min:0|max:4294967295',
            'tenor' => 'required|integer|min:1',
            'jatuh_tempo' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Harian',
            'tanggal_pencairan' => 'required|date',
            'marketing' => 'required|in:Hitler,Jubrito,Hendri',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        // $validated['administrasi'] = $validated['nominal'] * 0.05;

        $validated['marketing_id'] = Auth::id();
        
        $lastPinjaman = Pencairan::where('anggota_id', $request->anggota_id)
        ->orderBy('pinjaman_ke', 'desc')
        ->first();
        
        $sisa_kredit = $validated['nominal'] * 0.2 + $validated['nominal'] ;
        $validated['pinjaman_ke'] = $lastPinjaman ? $lastPinjaman->pinjaman_ke + 1 : 1;
        $validated['sisa_kredit'] = $sisa_kredit;

        $pencairan = Pencairan::create($validated);
        $simpananPokok = $validated['nominal'] * 0.05;

        $simpananData = [
            'anggota_id' => $validated['anggota_id'],
            'tanggal_transaksi' => $validated['tanggal_pencairan'],
            'jenis_transaksi' => 'SETOR',
            'jenis_simpanan' => 'POKOK',
            'nominal' => $simpananPokok,
            'marketing_id' => $validated['marketing_id'],
            'pencairan_id' => $pencairan->id,
            'is_locked' => true,
        ];

        Simpanan::create($simpananData);

        return redirect()->route('pencairan.index')->with('success', 'Data pencairan berhasil ditambahkan.');
    }

    public function update(Request $request, Pencairan $pencairan)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'no_anggota' => 'required',
            'nama' => 'required',
            'produk' => 'required|in:Harian,Mingguan',
            'nominal' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
            'jatuh_tempo' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Harian',
            'tanggal_pencairan' => 'required|date',
            'marketing' => 'required|in:Hitler,Jubrito,Hendri',
        ]);
        $sisa_kredit = $validated['nominal'] * 0.2 + $validated['nominal'] ;
        $validated['sisa_kredit'] = $sisa_kredit;

        // $validated['administrasi'] = $validated['nominal'] * 0.05;

        $pencairan->update($validated);

        $simpananPokok = $validated['nominal'] * 0.05;

        $simpananData = [
            'anggota_id' => $validated['anggota_id'],
            'tanggal_transaksi' => $validated['tanggal_pencairan'],
            'jenis_transaksi' => 'SETOR',
            'jenis_simpanan' => 'POKOK',
            'nominal' => $simpananPokok,
            'marketing_id' => Auth::id(),
            'pencairan_id' => $pencairan->id,
            'is_locked' => true,
        ];

        $simpanan = Simpanan::where('pencairan_id', $pencairan->id)->first();

        if ($simpanan) {
            $simpanan->update($simpananData);
        } else {
            Simpanan::create($simpananData);
        }
        return redirect()->route('pencairan.show', ['pencairan' => $pencairan->id])->with('success', 'Data pencairan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pencairan = Pencairan::findOrFail($id);

        $fotoPencairan = $pencairan->foto_pencairan;
        if ($fotoPencairan && Storage::disk('public')->exists($fotoPencairan)) {
            Storage::disk('public')->delete($fotoPencairan);
        }

        $fotoRumah = $pencairan->foto_rumah;
        if ($fotoRumah && Storage::disk('public')->exists($fotoRumah)) {
            Storage::disk('public')->delete($fotoRumah);
        }

        $simpanan = Simpanan::where('pencairan_id', $pencairan->id)->first();
        if ($simpanan) {
            $simpanan->delete();
        }

        $pencairan->delete();

        return redirect()->route('pencairan.index')->with('success', 'Data pencairan, simpanan, dan foto berhasil dihapus.');
    }

    public function lock($id)
    {
        $pencairan = Pencairan::findOrFail($id);

        if ($pencairan->is_locked) {
            $pencairan->update(['is_locked' => false]);
            $message = 'Data pencairan berhasil dibuka.';
        } else {
            $pencairan->update(['is_locked' => true]);
            $message = 'Data pencairan berhasil dikunci.';
        }
        return redirect()->route('pencairan.show', ['pencairan' => $pencairan->id])->with('success', $message);
    }

    public function upload(Request $request, $id)
    {
        $pencairan = Pencairan::findOrFail($id);

        $request->validate([
            'foto_pencairan' => 'nullable|image|max:51200', 
            'foto_rumah' => 'nullable|image|max:51200',     
        ]);

        $uploaded = false;

        if ($request->hasFile('foto_pencairan')) {
            if ($pencairan->foto_pencairan) {
                Storage::disk('public')->delete($pencairan->foto_pencairan);
            }

            $filePencairan = $request->file('foto_pencairan');
            $extensionPencairan = $filePencairan->getClientOriginalExtension();
            $fileNamePencairan = $pencairan->anggota->no_anggota . '-' . $pencairan->pinjaman_ke . '.' . $extensionPencairan;

            $filePencairan->storeAs('pencairan', $fileNamePencairan, 'public');
            $pencairan->foto_pencairan = 'pencairan/' . $fileNamePencairan; // Simpan path relatif ke database
            $uploaded = true;
        }

        if ($request->hasFile('foto_rumah')) {
            if ($pencairan->foto_rumah) {
                Storage::disk('public')->delete($pencairan->foto_rumah);
            }

            $fileRumah = $request->file('foto_rumah');
            $extensionRumah = $fileRumah->getClientOriginalExtension();
            $fileNameRumah = $pencairan->anggota->no_anggota . '-' . $pencairan->pinjaman_ke . '.' . $extensionRumah;

            $fileRumah->storeAs('rumah', $fileNameRumah, 'public');
            $pencairan->foto_rumah = 'rumah/' . $fileNameRumah; // Simpan path relatif ke database
            $uploaded = true;
        }

        if ($uploaded) {
            $pencairan->save();
            return redirect()->route('pencairan.show', ['pencairan' => $pencairan->id])
                ->with('success', 'Foto berhasil diperbarui.');
        }

        return redirect()->route('pencairan.show', ['pencairan' => $pencairan->id])
            ->with('error', 'Tidak ada foto baru yang diupload.');
    }

    public function getPinjamanKe($anggotaId)
    {
        $lastPinjaman = Pencairan::where('anggota_id', $anggotaId)
            ->orderBy('pinjaman_ke', 'desc')
            ->first();

        $pinjamanKe = $lastPinjaman ? $lastPinjaman->pinjaman_ke + 1 : 1;

        return response()->json($pinjamanKe);
    }

    // untuk pencairan create edit
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
    

    
}
