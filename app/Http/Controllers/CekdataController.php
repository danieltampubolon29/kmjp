<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CekdataController extends Controller
{
    public function marketing()
    {
        return view('marketing.cekdata.index');
    }

    public function admin()
    {
        return view('admin.cekdata.index');
    }
    
    public function angsuran()
    {
        return view('angsuran.input');
    }
}
