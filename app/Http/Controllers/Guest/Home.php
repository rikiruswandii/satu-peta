<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Map;
use App\Models\RegionalAgency;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Tags\Tag;

class Home extends Controller
{
    public function index(): View
    {
        // Mengambil data dari database
        $maps = Map::with('regional_agency', 'tags', 'documents')
            ->where('is_active', 1)
            ->latest()
            ->take(4)
            ->get();

        $groups = RegionalAgency::select('id', 'name', 'slug')->get();
        $categories = Tag::where('type', 'map')->get();

        $total_maps = Map::count(); // Total seluruh dataset

        $chartData = [
            'name' => 'Dataset',
            'value' => $total_maps, // Induk (paling besar)
            'children' => [
                [
                    'name' => 'Instansi',
                    'value' => $groups->sum(function ($group) {
                        return Map::where('regional_agency_id', $group->id)->count();
                    }), // Jumlah total map yang terkait dengan semua instansi
                    'expanded' => false,
                    'children' => $groups->map(function ($group) {
                        $maps_count = Map::where('regional_agency_id', $group->id)->count();

                        return [
                            'name' => $group->name,
                            'value' => $maps_count, // Jumlah map yang terkait dengan instansi ini
                        ];
                    })->toArray(),
                ],
                [
                    'name' => 'Kategori',
                    'value' => $categories->sum(function ($tag) {
                        return Map::withAnyTags([$tag->name], 'map')->count();
                    }), // Jumlah total map yang terkait dengan semua kategori
                    'children' => $categories->map(function ($tag) {
                        $tag_count = Map::withAnyTags([$tag->name], 'map')->count();

                        return [
                            'name' => $tag->getTranslation('name', 'id'),
                            'value' => $tag_count, // Jumlah map yang terkait dengan kategori ini
                        ];
                    })->toArray(),
                ],
            ],
        ];

        $news = Article::with('tags', 'documents')->latest()->take(3)->get();

        // Data untuk dikirim ke view
        $title = env('APP_NAME', 'Satu Peta Purwakarta');
        $description = 'Website Satu Peta Purwakarta adalah platform informasi geospasial yang menyajikan data peta terintegrasi untuk mendukung pembangunan dan layanan publik di Kabupaten Purwakarta';

        return view('guest.index', compact('title', 'description', 'categories', 'chartData', 'groups', 'maps', 'news'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $category = $request->input('category');
        $agency = $request->input('agency');

        // Query dengan eager loading
        $results = Map::with(['regional_agency', 'tags', 'documents']);

        if ($query) {
            $results->where('name', 'like', "%$query%");
        }

        if ($category && $category !== 'Semua Kategori') {
            $results->whereHas('tags', function ($q) use ($category) {
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
