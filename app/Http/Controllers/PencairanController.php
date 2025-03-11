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
    // Halaman utama pencairan
    public function index(Request $request)
    {
        // Ambil input pencarian dari request
        $search = $request->input('search');

        // Mulai query dari model Pencairan
        $query = Pencairan::query();

        // Filter berdasarkan role marketing
        if (Auth::user()->role === 'marketing') {
            $query->where('marketing_id', Auth::id());
        }

        // Tambahkan kondisi pencarian jika ada input search
        if ($search) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('no_anggota', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        // Paginate hasil query
        $pencairans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim data ke view
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

        $validated['pinjaman_ke'] = $lastPinjaman ? $lastPinjaman->pinjaman_ke + 1 : 1;

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
        ];

        Simpanan::create($simpananData);

        return redirect()->route('pencairan.index')->with('success', 'Data pencairan berhasil ditambahkan.');
    }

    public function update(Request $request, Pencairan $pencairan)
    {
        // Validasi input
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'produk' => 'required|in:Harian,Mingguan',
            'nominal' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
            'jatuh_tempo' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Harian',
            'tanggal_pencairan' => 'required|date',
            'marketing' => 'required|in:Hitler,Jubrito,Hendri',
        ]);

        // Hitung administrasi (5% dari nominal)
        $validated['administrasi'] = $validated['nominal'] * 0.05;

        // Update data pencairan
        $pencairan->update($validated);

        // Hitung simpanan pokok (5% dari nominal)
        $simpananPokok = $validated['nominal'] * 0.05;

        // Persiapkan data simpanan
        $simpananData = [
            'anggota_id' => $validated['anggota_id'],
            'tanggal_transaksi' => $validated['tanggal_pencairan'],
            'jenis_transaksi' => 'SETOR',
            'jenis_simpanan' => 'POKOK',
            'nominal' => $simpananPokok,
            'marketing_id' => Auth::id(),
            'pencairan_id' => $pencairan->id,
        ];

        // Cari data simpanan yang terkait dengan pencairan ini
        $simpanan = Simpanan::where('pencairan_id', $pencairan->id)->first();

        if ($simpanan) {
            $simpanan->update($simpananData);
        } else {
            Simpanan::create($simpananData);
        }

        return redirect()->route('pencairan.index')->with('success', 'Data pencairan berhasil diperbarui.');
    }



    public function destroy($id)
    {
        // Cari data pencairan berdasarkan ID
        $pencairan = Pencairan::findOrFail($id);

        // Hapus foto pencairan jika ada
        $fotoPencairan = $pencairan->foto_pencairan;
        if ($fotoPencairan && Storage::disk('public')->exists($fotoPencairan)) {
            Storage::disk('public')->delete($fotoPencairan);
        }

        // Hapus foto rumah jika ada
        $fotoRumah = $pencairan->foto_rumah;
        if ($fotoRumah && Storage::disk('public')->exists($fotoRumah)) {
            Storage::disk('public')->delete($fotoRumah);
        }

        // Hapus data simpanan yang terkait dengan pencairan ini
        $simpanan = Simpanan::where('pencairan_id', $pencairan->id)->first();
        if ($simpanan) {
            $simpanan->delete();
        }

        // Hapus data pencairan
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
        // Cari data pencairan berdasarkan ID
        $pencairan = Pencairan::findOrFail($id);

        // Validasi input
        $request->validate([
            'foto_pencairan' => 'nullable|image|max:51200', // Max 50MB
            'foto_rumah' => 'nullable|image|max:51200',     // Max 50MB
        ]);

        $uploaded = false;

        // Simpan file foto_pencairan jika ada
        if ($request->hasFile('foto_pencairan')) {
            // Hapus file lama jika ada
            if ($pencairan->foto_pencairan) {
                Storage::disk('public')->delete($pencairan->foto_pencairan);
            }

            // Proses file baru
            $filePencairan = $request->file('foto_pencairan');
            $extensionPencairan = $filePencairan->getClientOriginalExtension();
            $fileNamePencairan = $pencairan->anggota->no_anggota . '-' . $pencairan->pinjaman_ke . '.' . $extensionPencairan;

            // Simpan file ke folder 'pencairan'
            $filePencairan->storeAs('pencairan', $fileNamePencairan, 'public');
            $pencairan->foto_pencairan = 'pencairan/' . $fileNamePencairan; // Simpan path relatif ke database
            $uploaded = true;
        }

        // Simpan file foto_rumah jika ada
        if ($request->hasFile('foto_rumah')) {
            // Hapus file lama jika ada
            if ($pencairan->foto_rumah) {
                Storage::disk('public')->delete($pencairan->foto_rumah);
            }

            // Proses file baru
            $fileRumah = $request->file('foto_rumah');
            $extensionRumah = $fileRumah->getClientOriginalExtension();
            $fileNameRumah = $pencairan->anggota->no_anggota . '-' . $pencairan->pinjaman_ke . '.' . $extensionRumah;

            // Simpan file ke folder 'rumah'
            $fileRumah->storeAs('rumah', $fileNameRumah, 'public');
            $pencairan->foto_rumah = 'rumah/' . $fileNameRumah; // Simpan path relatif ke database
            $uploaded = true;
        }

        // Simpan perubahan ke database jika ada file yang diunggah
        if ($uploaded) {
            $pencairan->save();
            return redirect()->route('pencairan.show', ['pencairan' => $pencairan->id])
                ->with('success', 'Foto berhasil diperbarui.');
        }

        // Jika tidak ada file yang diunggah
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
}
