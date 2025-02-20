<?php

namespace App\Http\Controllers\Api;

use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Tambahkan ini

class MapController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $keywords = $request->query('q');

        // Ambil data maps dengan filter
        $maps = Map::query()
            ->with(['documents', 'regional_agency', 'sector'])
            ->whereHas('sector', function ($query) use ($keywords) {
                if ($keywords) {
                    $query->where('name', 'like', "%$keywords%");
                }
            })
            ->whereHas('regional_agency', function ($query) use ($keywords) {
                if ($keywords) {
                    $query->where('name', 'like', "%$keywords%");
                }
            })
            ->cursorPaginate($limit);

        // Ekstrak metadata
        $meta = collect($maps)->except('data')->toArray();

        // Ubah format data sebelum dikirim
        $data = collect($maps->items())->map(function ($item) {
            $documents = $item->documents->where('type', 'geojson');

            return [
                'id' => $item->id,
                'title' => $item->name,
                'slug' => $item->slug,
                'is_active' => $item->is_active,
                'can_download' => $item->can_download,
                'longitude' => $item->latitude ?? null,
                'latitude' => $item->longitude ?? null,
                'dataset_id' => $item->sector_id,
                'dataset_name' => $item->sector->name ?? null,
                'opd_id' => $item->regional_agency_id,
                'opd_name' => $item->regional_agency->name ?? null,
                'documents' => $documents->map(fn ($doc) => [
                    'id' => $doc->id,
                    'filename' => $doc->name,
                    'extension' => $doc->extension,
                    'type' => $doc->type,
                    'size' => $doc->size,
                    'url' => isset($doc->path) ? str_replace(' ', '%20', Storage::url($doc->path)) : null,
                    'created_at' => $doc->created_at,
                    'updated_at' => $doc->updated_at,
                ])->values(),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return $this->sendResponse($data, 'Data peta berhasil diambil.', $meta);
    }
}
