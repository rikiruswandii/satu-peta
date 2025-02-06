<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\RegionalAgency;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Home extends Controller
{
    public function index(): View
    {
        $categories = Sector::select('id', 'name')->get();
        $groups = RegionalAgency::select('id', 'name')->get();

        $title = env('APP_NAME', 'Satu Peta Purwakarta');
        $description = 'Website Satu Peta Purwakarta adalah platform informasi geospasial yang menyajikan data peta terintegrasi untuk mendukung pembangunan dan layanan publik di Kabupaten Purwakarta';
        return view('guest.index', compact('title', 'description', 'categories', 'groups'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $category = $request->input('category');
        $agency = $request->input('agency');

        // Query dengan eager loading
        $results = Map::with(['regional_agency', 'sector', 'documents']);

        if ($query) {
            $results->where('name', 'like', "%$query%");
        }

        if ($category && $category !== 'Semua Kategori') {
            $results->whereHas('sector', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        if ($agency && $agency !== 'Semua Instansi') {
            $results->whereHas('regional_agency', function ($q) use ($agency) {
                $q->where('name', $agency);
            });
        }

        $results = $results->get();

        return view('search.results', compact('results'));
    }
}