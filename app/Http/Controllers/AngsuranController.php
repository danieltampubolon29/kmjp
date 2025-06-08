<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Angsuran;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // scan angsuran
    // Tambahkan method ini ke AngsuranController yang sudah ada

    /**
     * Get next angsuran number for a pencairan
     */
    public function getNextAngsuran($pencairanId)
    {
        $angsuranList = Angsuran::where('pencairan_id', $pencairanId)
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

        return response()->json([
            'angsuran_ke' => $angsuranKe,
            'total_angsuran' => count($angsuranList)
        ]);
    }

    /**
     * Store multiple angsuran at once
     */
    public function storeMultiple(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Store Multiple Request Data:', $request->all());

        try {
            $request->validate([
                'angsuran_data' => 'required|array|min:1',
                'angsuran_data.*.pencairan_id' => 'required|integer|exists:pencairan,id',
                'angsuran_data.*.angsuran_ke' => 'required|integer|min:1',
                'angsuran_data.*.jenis_transaksi' => 'required|string',
                'angsuran_data.*.nominal' => 'required|integer|min:1',
                'angsuran_data.*.tanggal_angsuran' => 'required|date',
                'angsuran_data.*.latitude' => 'nullable|string',
                'angsuran_data.*.longitude' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        }

        $errors = [];
        $successCount = 0;
        $createdAngsuran = [];

        try {
            DB::beginTransaction();

            foreach ($request->angsuran_data as $index => $angsuranData) {
                try {
                    $pencairan = Pencairan::findOrFail($angsuranData['pencairan_id']);

                    // Validasi sisa kredit
                    if ($pencairan->sisa_kredit < $angsuranData['nominal']) {
                        $errors[] = "Data ke-" . ($index + 1) . ": Nominal angsuran melebihi sisa kredit untuk anggota {$pencairan->nama}";
                        continue;
                    }

                    // Validasi marketing (jika user adalah marketing, hanya bisa input data miliknya)
                    if (Auth::user()->role === 'marketing' && $pencairan->marketing_id !== Auth::id()) {
                        $errors[] = "Data ke-" . ($index + 1) . ": Anda tidak memiliki akses untuk menginput angsuran anggota {$pencairan->nama}";
                        continue;
                    }

                    // Cek apakah angsuran dengan nomor tersebut sudah ada
                    $existingAngsuran = Angsuran::where('pencairan_id', $angsuranData['pencairan_id'])
                        ->where('angsuran_ke', $angsuranData['angsuran_ke'])
                        ->first();

                    if ($existingAngsuran) {
                        $errors[] = "Data ke-" . ($index + 1) . ": Angsuran ke-{$angsuranData['angsuran_ke']} untuk anggota {$pencairan->nama} sudah ada";
                        continue;
                    }

                    // Buat angsuran baru
                    $angsuran = Angsuran::create([
                        'pencairan_id' => $angsuranData['pencairan_id'],
                        'angsuran_ke' => $angsuranData['angsuran_ke'],
                        'jenis_transaksi' => $angsuranData['jenis_transaksi'],
                        'nominal' => $angsuranData['nominal'],
                        'tanggal_angsuran' => $angsuranData['tanggal_angsuran'],
                        'marketing_id' => Auth::id(),
                        'latitude' => $angsuranData['latitude'] ?? null,
                        'longitude' => $angsuranData['longitude'] ?? null,
                    ]);

                    // Update sisa kredit pencairan
                    $newSisaKredit = $pencairan->sisa_kredit - $angsuranData['nominal'];
                    $pencairan->update([
                        'sisa_kredit' => $newSisaKredit,
                        'status' => $newSisaKredit === 0 ? true : $pencairan->status,
                    ]);

                    $createdAngsuran[] = $angsuran;
                    $successCount++;
                } catch (\Exception $e) {
                    Log::error("Error processing angsuran data index {$index}:", [
                        'error' => $e->getMessage(),
                        'data' => $angsuranData
                    ]);
                    $errors[] = "Data ke-" . ($index + 1) . ": " . $e->getMessage();
                }
            }

            // Jika ada data yang berhasil disimpan, commit transaction
            if ($successCount > 0) {
                DB::commit();

                $message = "Berhasil menyimpan {$successCount} data angsuran.";
                if (!empty($errors)) {
                    $message .= " Namun ada " . count($errors) . " data yang gagal disimpan.";
                }

                Log::info("Successfully stored {$successCount} angsuran records");

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'success_count' => $successCount,
                    'errors' => $errors,
                    'created_data' => $createdAngsuran
                ]);
            } else {
                DB::rollback();
                Log::warning('No angsuran data was saved', ['errors' => $errors]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil disimpan.',
                    'errors' => $errors
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Store Multiple Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pencairan data with calculated angsuran info (for API)
     */
    public function getPencairanWithAngsuran($pencairanId)
    {
        try {
            $pencairan = Pencairan::with(['anggota'])->findOrFail($pencairanId);

            // Hitung angsuran ke berdasarkan angsuran yang sudah ada
            $angsuranList = Angsuran::where('pencairan_id', $pencairanId)
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

            // Hitung nominal default
            $nominalPencairan = floatval($pencairan->nominal);
            $tenor = intval($pencairan->tenor);
            $calculatedNominal = $tenor > 0 ?
                floor(($nominalPencairan + ($nominalPencairan * 0.2)) / $tenor) : 0;

            return response()->json([
                'success' => true,
                'pencairan' => $pencairan,
                'angsuran_ke' => $angsuranKe,
                'calculated_nominal' => $calculatedNominal,
                'total_angsuran_exist' => count($angsuranList),
                'is_sequential' => $this->checkSequentialAngsuran($angsuranList)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pencairan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Check if angsuran sequence is valid
     */
    private function checkSequentialAngsuran($angsuranList)
    {
        if (empty($angsuranList)) {
            return true;
        }

        sort($angsuranList);

        for ($i = 0; $i < count($angsuranList); $i++) {
            if ($angsuranList[$i] !== ($i + 1)) {
                return false;
            }
        }

        return true;
    }
}
