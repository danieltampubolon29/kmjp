<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Angsuran;
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
            $query->where(function ($q) use ($search) {
                $q->whereHas('anggota', function ($qAnggota) use ($search) {
                    $qAnggota->where('no_anggota', 'like', '%' . $search . '%')
                        ->orWhere('nama', 'like', '%' . $search . '%');
                });
                $q->orWhere('jatuh_tempo', 'like', '%' . $search . '%');
                $q->orWhere('tanggal_pencairan', 'like', '%' . $search . '%');
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


        $validated['marketing_id'] = Auth::id();

        $lastPinjaman = Pencairan::where('anggota_id', $request->anggota_id)
            ->orderBy('pinjaman_ke', 'desc')
            ->first();

        $sisa_kredit = $validated['nominal'] * 0.2 + $validated['nominal'];
        $validated['pinjaman_ke'] = $lastPinjaman ? $lastPinjaman->pinjaman_ke + 1 : 1;
        $validated['sisa_kredit'] = $sisa_kredit;

        $pencairan = Pencairan::create($validated);
        $simpananPokok = $validated['nominal'] * 0.05;

        $anggota = Anggota::findOrFail($validated['anggota_id']);
        $anggota->simpanan = ($anggota->simpanan ?? 0) + $simpananPokok;
        $anggota->save();

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

        $sisa_kredit = $validated['nominal'] * 0.2 + $validated['nominal'];
        $validated['sisa_kredit'] = $sisa_kredit;

        $anggotaLama = Anggota::findOrFail($pencairan->anggota_id);
        $nominalLama = $pencairan->nominal;

        $simpananPokokLama = $nominalLama * 0.05;

        $anggotaLama->simpanan = ($anggotaLama->simpanan ?? 0) - $simpananPokokLama;
        $anggotaLama->save();

        $pencairan->update($validated);

        $simpananPokokBaru = $validated['nominal'] * 0.05;

        $anggotaBaru = Anggota::findOrFail($validated['anggota_id']);
        $anggotaBaru->simpanan = ($anggotaBaru->simpanan ?? 0) + $simpananPokokBaru;
        $anggotaBaru->save();

        $simpananData = [
            'anggota_id' => $validated['anggota_id'],
            'tanggal_transaksi' => $validated['tanggal_pencairan'],
            'jenis_transaksi' => 'SETOR',
            'jenis_simpanan' => 'POKOK',
            'nominal' => $simpananPokokBaru,
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

        $hasAngsuran = Angsuran::where('pencairan_id', $pencairan->id)->exists();

        if ($hasAngsuran) {
            return redirect()->route('pencairan.show', ['pencairan' => $pencairan->id])
                ->withErrors(['error' => 'Tidak dapat menghapus data karena masih memiliki angsuran terkait.']);
        }

        $anggota = Anggota::findOrFail($pencairan->anggota_id);
        $simpananPokok = $pencairan->nominal * 0.05;
        $anggota->simpanan = ($anggota->simpanan ?? 0) - $simpananPokok;
        $anggota->save();

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
            $pencairan->foto_pencairan = 'pencairan/' . $fileNamePencairan;
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
            $pencairan->foto_rumah = 'rumah/' . $fileNameRumah;
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

    public function getPencairanData(Request $request)
    {
        $anggotaId = $request->input('anggota_id');

        if (!$anggotaId) {
            return response()->json(['error' => 'ID Anggota tidak ditemukan'], 400);
        }

        $pencairan = Pencairan::where('anggota_id', $anggotaId)
            ->where('marketing_id', Auth::id())
            ->select('id', 'pinjaman_ke', 'tanggal_pencairan', 'nominal', 'sisa_kredit', 'status')
            ->get();

        return response()->json($pencairan);
    }
}
