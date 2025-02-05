<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Home extends Controller
{
    public function index(): View
    {
        return view('guest.index');
    }
}