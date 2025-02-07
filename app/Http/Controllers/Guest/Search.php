<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Search extends Controller
{
    public function index()
    {

        $data = [
            'title' => 'Pencarian',
            'description' => 'Masukkan kata kunci pencarian anda di sini.',
        ];

        return view('guest.search', $data);
    }
}
