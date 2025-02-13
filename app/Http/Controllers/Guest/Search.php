<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\RegionalAgency;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class Search extends Controller
{
    public function index(Request $request)
    {
        $regionalAgencySum = RegionalAgency::leftJoin('maps', 'maps.regional_agency_id', '=', 'regional_agencies.id')
            ->leftJoin('sectors', 'sectors.id', '=', 'maps.sector_id')
            ->select(
                'regional_agencies.id',
                'regional_agencies.name',
                DB::raw('COUNT(maps.id) as total'),
                'sectors.name as nama_sektor',
                'sectors.created_at'
            )
            ->whereNull('maps.deleted_at')
            ->groupBy('regional_agencies.id', 'regional_agencies.name', 'sectors.name', 'sectors.created_at')
            ->latest('sectors.created_at')
            ->get();

        $mapsQuery = Map::with("regional_agency", "sector", "documents")->where("is_active", 1);

        if ($request->has('regional_agencies')) {
            $mapsQuery->whereIn('regional_agency_id', $request->regional_agencies);
        }

        if ($request->has('sector') && $request->sector != '') {
            $mapsQuery->whereIn('sector_id', $request->sector);
        }

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $mapsQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter berdasarkan nama regional agency
        if ($request->filled('regional_agencies')) {
            $regionalAgencies = is_array($request->regional_agencies) ? $request->regional_agencies : [$request->regional_agencies];
            $mapsQuery->whereHas('regional_agency', function ($query) use ($regionalAgencies) {
                $query->whereIn('name', $regionalAgencies); // Memfilter berdasarkan nama regional agency
            });
        }

        // Filter berdasarkan kata kunci pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $mapsQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');  // Mencocokkan berdasarkan nama map
            });
        }

        // Menyelesaikan query dengan paginasi
        $maps = $mapsQuery->paginate(9);


        $data = [
            'title' => 'Pencarian',
            'description' => 'Masukkan kata kunci pencarian anda di sini.',
            'categories' => Sector::all(),
            'groups' => RegionalAgency::with("map")->get(),
            'maps' => $maps,
            'regionalAgencySum' => $regionalAgencySum,
        ];
        

        return view('guest.search', $data);
    }

    public function getMapsByViewport(Request $request)
    {
        // Inisialisasi proj4php
        $proj4 = new Proj4php();

        // Definisikan proyeksi
        $proj4->addDef("EPSG:4326", "+proj=longlat +datum=WGS84 +no_defs");
        $proj4->addDef("EPSG:3857", "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs");

        // Buat objek proyeksi
        $projWGS84 = new Proj("EPSG:4326", $proj4);    // lat/long
        $projMercator = new Proj("EPSG:3857", $proj4);  // web mercator

        $minLat = $request->input('minLat');
        $maxLat = $request->input('maxLat');
        $minLng = $request->input('minLng');
        $maxLng = $request->input('maxLng');

        // Buat point
        $pointMin = new Point($minLng, $minLat);
        $pointMax = new Point($maxLng, $maxLat);

        // Transform koordinat dari EPSG:3857 ke EPSG:4326
        $pointMin = $proj4->transform($projMercator, $projWGS84, $pointMin);
        $pointMax = $proj4->transform($projMercator, $projWGS84, $pointMax);

        $minLatConverted = is_object($pointMin) && property_exists($pointMin, 'y') ? floatval($pointMin->y) : null;
        $maxLatConverted = is_object($pointMax) && property_exists($pointMax, 'y') ? floatval($pointMax->y) : null;
        $minLngConverted = is_object($pointMin) && property_exists($pointMin, 'x') ? floatval($pointMin->x) : null;
        $maxLngConverted = is_object($pointMax) && property_exists($pointMax, 'x') ? floatval($pointMax->x) : null;

        if ($minLatConverted === null || $maxLatConverted === null || $minLngConverted === null || $maxLngConverted === null) {
            Log::error("Konversi koordinat gagal");
            return response()->json(['error' => 'Konversi koordinat gagal'], 400);
        }

        // Log koordinat yang sudah dikonversi
        Log::info("Converted coordinates (WGS84):", [
            'minLat' => $minLatConverted,
            'maxLat' => $maxLatConverted,
            'minLng' => $minLngConverted,
            'maxLng' => $maxLngConverted
        ]);

        // Query database dengan koordinat yang sudah dikonversi
        $maps = Map::whereRaw('
            CAST(JSON_UNQUOTE(JSON_EXTRACT(`latitude`, "$[0][0]")) AS DECIMAL(10,8)) BETWEEN ? AND ?
            AND CAST(JSON_UNQUOTE(JSON_EXTRACT(`longitude`, "$[0][0]")) AS DECIMAL(11,8)) BETWEEN ? AND ?
        ', [
            $minLatConverted,
            $maxLatConverted,
            $minLngConverted,
            $maxLngConverted
        ])
            ->whereNull('deleted_at')
            ->with(['regional_agency', 'sector', 'documents'])
            ->paginate(9);

        // Render each map card
        $html = '';
        foreach ($maps as $map) {
            $html .= view('components.map-card', [
                'id' => $map->id,
                'card_class' => 'col-12 col-md-6 col-lg-4 mb-4',
                'card_id' => $map->id,
                'card_title' => $map->name,
                'card_opd' => $map->regional_agency->name,
                'card_filename' => $map->documents->first() ? $map->documents->first()->name : 'No file',
                'geojson_path' => $map->documents->first() ? Storage::url($map->documents->first()->path) : '',
                'regional_agency' => $map->regional_agency->name,
                'sector' => $map->sector->name,
                'data_map_id' => $map->id,
                'data_geojson_path' => $map->documents->first() ? Storage::url($map->documents->first()->path) : '',
                'data_title' => $map->name,
                'data_regional_agency' => $map->regional_agency->name,
                'data_sector' => $map->sector->name
            ])->render();
        }

        return response()->json([
            'html' => $html,
            'pagination' => $maps->links()->toHtml(),
            'maps' => $maps // Include the raw data for additional processing if needed
        ]);
    }
}
