<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KasbonHarianMarketing;

class KasbonHarianMarketingController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = KasbonHarianMarketing::with('marketing')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tanggal', 'like', '%' . $search . '%')
                    ->orWhereHas('marketing', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $kasbons = $query->paginate(10);
        return view('kasbon.index', compact('kasbons', 'search'));
    }

    public function create()
    {
        $users = User::where('role', 'marketing')->get();

        return view('kasbon.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'tanggal' => 'required|date',
            'marketing_id' => 'required|exists:users,id',
            'nominal' => 'required|integer|min:1',
        ]);

        KasbonHarianMarketing::create($validate);

        return redirect()->route('kasbon.index')->with('success', 'Data kasbon berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kasbon = KasbonHarianMarketing::findOrFail($id);
        return view('kasbon.show', compact('kasbon'));
    }

    public function edit($id)
    {
        $kasbon = KasbonHarianMarketing::findOrFail($id);
        $users = User::where('role', 'marketing')->get();
        return view('kasbon.edit', compact('kasbon', 'users'));
    }

    public function update(Request $request, $id)
    {
        $kasbon = KasbonHarianMarketing::findOrFail($id);

        $validate = $request->validate([
            'marketing_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
        ]);

        $kasbon->update($validate);

        return redirect()->route('kasbon.show', ['kasbon' => $kasbon->id])->with('success', 'Kasbon berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kasbon = KasbonHarianMarketing::findOrFail($id);
        $kasbon->delete();
        return redirect()->route('kasbon.index')->with('success', 'Data kasbon berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $kasbon = KasbonHarianMarketing::findOrFail($id);
        $request->validate([
            'sisa_kasbon' => 'required|numeric',
        ]);
        $kasbon->update([
            'sisa_kasbon' => $request->sisa_kasbon,
            'status' => true,
        ]);

        return redirect()->route('kasbon.show', ['kasbon' => $kasbon->id])->with('success', 'Status kasbon berhasil diperbarui!');
    }

    public function lock($id)
    {
        $kasbon = KasbonHarianMarketing::findOrFail($id);

        if ($kasbon->status == 0) {
            return redirect()->route('kasbon.show', ['kasbon' => $kasbon->id])->with('error', 'Kasbon tidak dapat dikunci karena masih dalam proses.');
        }
        if ($kasbon->is_locked) {
            $kasbon->update(['is_locked' => false]);
            $message = 'Data kasbon berhasil dibuka.';
        } else {
            $kasbon->update(['is_locked' => true]);
            $message = 'Data kasbon berhasil dikunci.';
        }
        return redirect()->route('kasbon.show', ['kasbon' => $kasbon->id])->with('success', $message);
    }
}
