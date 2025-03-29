<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Anggota::query();
        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_anggota', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }
        $anggotas = $query->paginate(10);
        return view('anggota.index', compact('anggotas', 'search'));
    }

    public function create()
    {
        $lastNoAnggota = Anggota::max('no_anggota') ?? 0;
        $nextNoAnggota = $lastNoAnggota + 1;
        return view('anggota.create', compact('nextNoAnggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',
            'no_hp' => 'nullable|string|max:15',
            'tanggal_daftar' => 'required|date',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ], [
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'nama.required' => 'Nama wajib diisi.',
            'alamat_ktp.required' => 'Alamat KTP wajib diisi.',
            'alamat_domisili.required' => 'Alamat domisili wajib diisi.',
            'tanggal_daftar.required' => 'Tanggal daftar wajib diisi.',
        ]);

        try {
            $marketingId = Auth::id();

            Anggota::create([
                'no_anggota' => $request->input('no_anggota'),
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat_ktp' => $validated['alamat_ktp'],
                'alamat_domisili' => $validated['alamat_domisili'],
                'no_hp' => $validated['no_hp'],
                'tanggal_daftar' => $validated['tanggal_daftar'],
                'marketing_id' => $marketingId,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);

            return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('anggota.create')
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'])
                ->withInput();
        }
    }

    public function show($id)
    {
        $anggota = Anggota::findOrFail($id);

        if (Auth::user()->role === 'marketing') {
            if ($anggota->marketing_id !== Auth::id()) {
                return redirect()->route('anggota.index')->with('error', 'Anda tidak memiliki akses ke data ini.');
            }
        }

        return view('anggota.show', compact('anggota'));
    }


    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.edit', compact('anggota'));
    }


    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',
            'no_hp' => 'nullable|string',
            'tanggal_daftar' => 'required|date',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'alamat_ktp.required' => 'Alamat KTP wajib diisi.',
            'alamat_domisili.required' => 'Alamat domisili wajib diisi.',
            'no_hp.string' => 'Nomor HP wajib diisi.',
            'tanggal_daftar.required' => 'Tanggal daftar wajib diisi.',
        ]);

        try {
            $anggota->update($validated);

            return redirect()->route('anggota.show', ['anggotum' => $anggota->id])
                ->with('success', 'Data anggota berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('anggota.show', ['anggotum' => $anggota->id])
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.'])
                ->withInput();
        }
    }


    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        $hasPencairan = Pencairan::where('anggota_id', $anggota->id)->exists();
        $hasSimpanan = Simpanan::where('anggota_id', $anggota->id)->exists();

        if ($hasPencairan || $hasSimpanan) {
            return redirect()->route('anggota.show', ['anggotum' => $anggota->id])
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Karena data ini memiliki hubungan dengan data lain']);
        }
        $fotoKtp = $anggota->foto_ktp;
        $fotoKk = $anggota->foto_kk;

        if ($fotoKtp && Storage::disk('public')->exists($fotoKtp)) {
            Storage::disk('public')->delete($fotoKtp);
        }
        if ($fotoKk && Storage::disk('public')->exists($fotoKk)) {
            Storage::disk('public')->delete($fotoKk);
        }
        $anggota->delete();
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }


    public function upload(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'foto_ktp' => 'nullable|image|max:51200',
            'foto_kk' => 'nullable|image|max:51200',
        ]);

        $uploaded = false;

        if ($request->hasFile('foto_ktp')) {
            if ($anggota->foto_ktp) {
                Storage::disk('public')->delete($anggota->foto_ktp);
            }

            $fileKtp = $request->file('foto_ktp');
            $extensionKtp = $fileKtp->getClientOriginalExtension();
            $fileNameKtp = $anggota->no_anggota . '.' . $extensionKtp;

            $fileKtp->storeAs('ktp', $fileNameKtp, 'public');
            $anggota->foto_ktp = 'ktp/' . $fileNameKtp;
            $uploaded = true;
        }

        if ($request->hasFile('foto_kk')) {
            if ($anggota->foto_kk) {
                Storage::disk('public')->delete($anggota->foto_kk);
            }

            $fileKk = $request->file('foto_kk');
            $extensionKk = $fileKk->getClientOriginalExtension();
            $fileNameKk = $anggota->no_anggota . '.' . $extensionKk;

            $fileKk->storeAs('kk', $fileNameKk, 'public');
            $anggota->foto_kk = 'kk/' . $fileNameKk;
            $uploaded = true;
        }

        if ($uploaded) {
            $anggota->save();
            return redirect()->route('anggota.show', ['anggotum' => $anggota->id])
                ->with('success', 'Foto berhasil diperbarui.');
        }

        return redirect()->route('anggota.show', ['anggotum' => $anggota->id])
            ->with('error', 'Tidak ada foto baru yang diupload.');
    }


    public function lock($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->is_locked) {
            $anggota->update(['is_locked' => false]);
            $message = 'Data anggota berhasil dibuka.';
        } else {
            $anggota->update(['is_locked' => true]);
            $message = 'Data anggota berhasil dikunci.';
        }
        return redirect()->route('anggota.show', ['anggotum' => $anggota->id])->with('success', $message);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $anggotas = Anggota::where('nama', 'like', "%$query%")
            ->orWhere('no_anggota', 'like', "%$query%")
            ->get();

        return response()->json($anggotas);
    }
}
