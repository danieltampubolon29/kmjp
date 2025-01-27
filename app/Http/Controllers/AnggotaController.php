<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::all();
        return view('anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'no_anggota' => 'required|string|max:255|unique:anggota',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required',
            'alamat_domisili' => 'required',
            'no_hp' => 'required|string|max:15',
            'pekerjaan' => 'required|string|max:255',
            'marketing' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:204800',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:204800',
        ]);

        // Create new Anggota instance with validated data
        $anggota = new Anggota($validatedData);

        // Handle the file uploads for foto_ktp and foto_kk
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = $request->no_anggota . '_ktp.' . $file->getClientOriginalExtension();
            $anggota->foto_ktp = $file->storeAs('images/ktp', $filename, 'public');
        }

        if ($request->hasFile('foto_kk')) {
            $file = $request->file('foto_kk');
            $filename = $request->no_anggota . '_kk.' . $file->getClientOriginalExtension();
            $anggota->foto_kk = $file->storeAs('images/kk', $filename, 'public');
        }

        // Save the new anggota
        $anggota->save();

        // Redirect back to anggota index with success message
        return redirect()->route('anggota.index')->with('success', 'Anggota created successfully.');
    }

    public function edit(Anggota $anggota)
    {
        // Return view for editing anggota
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'no_anggota' => 'required|string|max:255|unique:anggota,no_anggota,' . $anggota->id,
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_ktp' => 'required',
            'alamat_domisili' => 'required',
            'no_hp' => 'required|string|max:15',
            'pekerjaan' => 'required|string|max:255',
            'marketing' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:204800',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:204800',
        ]);

        // Fill the anggota instance with validated data
        $anggota->fill($validatedData);

        // Handle the file uploads for foto_ktp and foto_kk
        if ($request->hasFile('foto_ktp')) {
            if ($anggota->foto_ktp) {
                Storage::delete($anggota->foto_ktp);
            }
            $file = $request->file('foto_ktp');
            $filename = $request->no_anggota . '_ktp.' . $file->getClientOriginalExtension();
            $anggota->foto_ktp = $file->storeAs('images/ktp', $filename, 'public');
        }

        if ($request->hasFile('foto_kk')) {
            if ($anggota->foto_kk) {
                Storage::delete($anggota->foto_kk);
            }
            $file = $request->file('foto_kk');
            $filename = $request->no_anggota . '_kk.' . $file->getClientOriginalExtension();
            $anggota->foto_kk = $file->storeAs('images/kk', $filename, 'public');
        }

        // Save the updated anggota
        $anggota->save();

        // Redirect back to anggota index with success message
        return redirect()->route('anggota.index')->with('success', 'Anggota updated successfully.');
    }

    public function destroy(Anggota $anggota)
    {
        try {
            // Delete the uploaded files if they exist
            if ($anggota->foto_ktp && Storage::disk('public')->exists($anggota->foto_ktp)) {
                Storage::disk('public')->delete($anggota->foto_ktp);
            }

            if ($anggota->foto_kk && Storage::disk('public')->exists($anggota->foto_kk)) {
                Storage::disk('public')->delete($anggota->foto_kk);
            }

            // Delete the anggota record
            $anggota->delete();

            // Redirect back to anggota index with success message
            return redirect()->route('anggota.index')->with('success', 'Anggota deleted successfully.');
        } catch (\Exception $e) {
            // Handle any errors that occur during deletion
            return redirect()->route('anggota.index')->with('error', 'Failed to delete anggota: ' . $e->getMessage());
        }
    }
}