<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AngsuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Angsuran $angsuran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Angsuran $angsuran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Angsuran $angsuran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Angsuran $angsuran)
    {
        //
    }
}
