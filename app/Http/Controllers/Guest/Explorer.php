<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Explorer extends Controller
{
    public function index()
    {
        $title = env('APP_NAME', 'Satu Peta Purwakarta');
        $description = 'Website Satu Peta Purwakarta adalah platform informasi geospasial yang menyajikan data peta terintegrasi untuk mendukung pembangunan dan layanan publik di Kabupaten Purwakarta';
        return view('guest.explorer', compact('title', 'description'));
    }
}