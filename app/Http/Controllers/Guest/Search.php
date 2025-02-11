<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\RegionalAgency;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Search extends Controller
{
    public function index(Request $request)
    {
        $regionalAgencySum = RegionalAgency::leftJoin('maps', 'maps.regional_agency_id', '=', 'regional_agencies.id')
            ->select('regional_agencies.id', 'regional_agencies.name', DB::raw('COUNT(maps.id) as total'))
            ->groupBy('regional_agencies.id', 'regional_agencies.name')
            ->get();

        $mapsQuery = Map::with('regional_agency', 'sector', 'documents')->where('is_active', 1);

        if ($request->has('regional_agencies')) {
            $mapsQuery->whereIn('regional_agency_id', $request->regional_agencies);
        }

        if ($request->has('sector') && $request->sector != '') {
            $mapsQuery->whereIn('sector_id', $request->sector);
        }

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $mapsQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%'.$searchTerm.'%');
            });
        }

        $maps = $mapsQuery->paginate(9);

        $data = [
            'title' => 'Pencarian',
            'description' => 'Masukkan kata kunci pencarian anda di sini.',
            'categories' => Sector::all(),
            'groups' => RegionalAgency::with('map')->get(),
            'maps' => $maps,
            'regionalAgencySum' => $regionalAgencySum,
        ];

        return view('guest.search', $data);
    }
}
