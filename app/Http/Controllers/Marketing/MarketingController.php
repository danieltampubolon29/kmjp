<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;

class MarketingController extends Controller
{
    public function index()
    {
        return view('marketing.dashboard');
    }
}
