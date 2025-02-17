<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Map as ModelMap;
use App\Models\RegionalAgency;
use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class Map extends Controller
{
    public function index(): View
    {
        $regional_agencies = RegionalAgency::all();
        $sectors = Sector::all();
        $count = ModelMap::count();
        $title = 'Peta';
        $prefix = 'maps';
        $description = 'Jelajahi kumpulan peta informatif dan terpercaya seputar '.env('APP_NAME', 'Satu Peta Purwakarta').'. Temukan wawasan, tips, dan panduan terbaru untuk meningkatkan pengetahuan Anda.';

        return view('panel.geospatials.map', compact('prefix', 'regional_agencies', 'sectors', 'count', 'title', 'description'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = ModelMap::with('regional_agency', 'sector', 'documents')->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('download', function ($row) {
                        if ($row->documents->isNotEmpty()) {
                            $downloadLinks = '';
                            foreach ($row->documents as $document) {
                                $downloadLinks .= '<a href="'.route('maps.download', ['map' => Crypt::encrypt($row->id), 'id' => Crypt::encrypt($document->id)]).'" class="badge rounded-pill bg-primary text-light">
                            <em class="icon ni ni-download-cloud"></em> Unduh
                        </a><br>';
                            }

                            return $downloadLinks;
                        }

                        return '<span class="badge bg-secondary">Tidak ada dokumen</span>';
                    })
                    ->addColumn('checkbox', function ($row) {
                        $checked = $row->is_active ? 'checked' : '';

                        return '<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck'.$row->id.'" '.$checked.' disabled>
                            <label class="custom-control-label" for="customCheck'.$row->id.'"></label>
                        </div>';
                    })
                    ->addColumn('action', function ($row) {
                        $detailLinks = '';
                        foreach ($row->documents as $document) {
                            $detailLinks .= '<li><a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#detailMapModal"
                                            data-regional-agency="'.optional($row->regional_agency)->name.'"
                                            data-sector="'.optional($row->sector)->name.'"
                                            data-geojson="'.Storage::url($document->path).'"
                                            data-name="'.$row->name.'"
                                            data-id="'.$row->id.'">
                                            <em class="icon ni ni-eye"></em><span>Lihat</span>
                                        </a></li>';
                        }

                        return '<div class="dropdown">
                            <a href="#" class="btn btn-sm btn-icon btn-trigger dropdown-toggle" data-bs-toggle="dropdown">
                                <em class="icon ni ni-more-h rounded-full"></em>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <ul class="link-list-opt no-bdr">
                                    '.$detailLinks.'
                                    <li class="divider"></li>
                                    <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#editMapModal"
                                            data-regional-agency="'.optional($row->regional_agency)->id.'"
                                            data-sector="'.optional($row->sector)->id.'"
                                            data-name="'.$row->name.'"
                                            data-id="'.Crypt::encrypt($row->id).'">
                                            <em class="icon ni ni-edit"></em><span>Edit</span>
                                        </a></li>
                                    <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="'.($row->is_active ? '#deaktivasiMapModal' : '#aktivasiMapModal').'"
                                            data-name="'.$row->name.'"
                                            data-id="'.Crypt::encrypt($row->id).'">
                                            <em class="icon ni '.($row->is_active ? 'ni-cross-round' : 'ni-check-round').'"></em><span>'.($row->is_active ? 'Deaktivasi' : 'Aktivasi').'</span>
                                        </a></li>
                                    <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#deleteMapModal"
                                            data-id="'.Crypt::encrypt($row->id).'"
                                            data-name="'.$row->name.'">
                                            <em class="icon ni ni-trash text-red-500"></em><span>Delete</span>
                                        </a></li>
                                </ul>
                            </div>
                        </div>';
                    })
                    ->rawColumns(['action', 'download', 'checkbox'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());

                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }

    public function store(Request $request): RedirectResponse
    {
        \Log::info('Data yang diterima:', $request->all());

        // Validasi input request
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'can_download' => 'boolean',
            'regional_agency_id' => 'required|exists:regional_agencies,id',
            'sector_id' => 'required|exists:sectors,id',
            'name' => 'required|string|max:80',
            'file' => 'required|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validasi gagal:', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->filled('file')) {
                \Log::info('File terdeteksi dalam request.');

                // Ambil server ID file dari request
                $serverId = $request->input('file');
                \Log::info("Server ID FilePond: $serverId");

                $filepond = app(\Sopamo\LaravelFilepond\Filepond::class);
                $disk = config('filepond.temporary_files_disk');
                $temporaryPath = $filepond->getPathFromServerId($serverId);
                $fullpath = Storage::disk($disk)->path($temporaryPath);

                \Log::info("Path sementara file: $temporaryPath");
                \Log::info("Path lengkap file: $fullpath");

                if (! file_exists($fullpath)) {
                    \Log::error("File tidak ditemukan di lokasi sementara: $fullpath");

                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file dari lokasi sementara ke folder final
                $newFilePath = 'uploads/maps/'.basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);
                \Log::info("File berhasil dipindahkan ke: $newFilePath");

                // Mendapatkan informasi file dan isi GeoJSON
                $fileInfo = pathinfo($fullpath);
                $geojsonContent = file_get_contents($fullpath);
                $geojsonData = json_decode($geojsonContent, true);

                // Validasi format GeoJSON
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Gagal parsing file GeoJSON: '.json_last_error_msg());

                    return redirect()->back()->with('error', 'File GeoJSON tidak valid.');
                }

                // Ekstraksi koordinat
                $coordinates = $this->extractCoordinates($geojsonData);
                \Log::info('Extracted Coordinates:', $coordinates);

                // Validasi koordinat
                if (empty($coordinates['latitude']) || empty($coordinates['longitude'])) {
                    \Log::error('Koordinat tidak valid dalam file GeoJSON.');

                    return redirect()->back()->with('error', 'Koordinat tidak valid dalam file GeoJSON.');
                }

                // Simpan data peta ke database
                $data = ModelMap::create([
                    'user_id' => $request->user_id,
                    'can_download' => $request->boolean('can_download') ? 1 : 0,
                    'regional_agency_id' => $request->regional_agency_id,
                    'sector_id' => $request->sector_id,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'latitude' => json_encode([$coordinates['latitude']]),
                    'longitude' => json_encode([$coordinates['longitude']]),
                ]);
                \Log::info("Peta berhasil dibuat dengan ID: {$data->id}");

                // Menyimpan informasi file GeoJSON dalam tabel Document
                Document::create([
                    'name' => $fileInfo['basename'],
                    'path' => $newFilePath,
                    'extension' => $fileInfo['extension'],
                    'type' => 'geojson',
                    'documentable_type' => ModelMap::class,
                    'documentable_id' => $data->id,
                    'mime_type' => mime_content_type($fullpath),
                    'size' => filesize($fullpath),
                ]);

                \Log::info("File berhasil disimpan dalam database dengan path: $newFilePath");

                return redirect()->back()->with('success', 'Peta berhasil ditambahkan.');
            } else {
                \Log::warning('File tidak ditemukan dalam request.');

                return redirect()->back()->with('error', 'File harus diunggah.');
            }
        } catch (\Exception $e) {

            \Log::error('Gagal membuat peta', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Gagal membuat peta: '.$e->getMessage());
        }
    }

    private function extractCoordinates($geojsonData)
    {
        $coordinates = [
            'latitude' => [],
            'longitude' => [],
        ];

        if (isset($geojsonData['features']) && is_array($geojsonData['features'])) {
            foreach ($geojsonData['features'] as $feature) {
                if (isset($feature['geometry']['coordinates'])) {
                    $coords = $feature['geometry']['coordinates'];

                    // Jika tipe geometri adalah Point
                    if ($feature['geometry']['type'] == 'Point' && is_array($coords) && count($coords) == 2) {
                        // Ambil koordinat dari Point
                        $coordinates['longitude'][] = floatval($coords[0]); // Longitude
                        $coordinates['latitude'][] = floatval($coords[1]);  // Latitude
                    }

                    // Jika tipe geometri adalah LineString
                    elseif ($feature['geometry']['type'] == 'LineString' && is_array($coords)) {
                        // Ambil seluruh koordinat dari LineString
                        foreach ($coords as $coord) {
                            // Pastikan koordinat valid
                            if (is_array($coord) && count($coord) == 2) {
                                $coordinates['longitude'][] = floatval($coord[0]); // Longitude
                                $coordinates['latitude'][] = floatval($coord[1]);  // Latitude
                            }
                        }
                    }

                    // Jika tipe geometri adalah Polygon
                    elseif ($feature['geometry']['type'] == 'Polygon' && is_array($coords) && count($coords) > 0) {
                        // Ambil seluruh koordinat pertama dari polygon
                        foreach ($coords[0] as $coord) {
                            // Pastikan koordinat valid
                            if (is_array($coord) && count($coord) == 2) {
                                $coordinates['longitude'][] = floatval($coord[0]); // Longitude
                                $coordinates['latitude'][] = floatval($coord[1]);  // Latitude
                            }
                        }
                    }
                }
            }
        }

        return $coordinates;
        //     \Log::error('Gagal membuat peta', ['error' => $e->getMessage()]);

        //     return redirect()->back()->with('error', 'Gagal membuat peta: '.$e->getMessage());
        // }

        // \Log::error('Permintaan tidak valid: File harus diunggah.');

        // return redirect()->back()->with('error', 'File harus diunggah.');
    }

    public function update(Request $request): RedirectResponse
    {
        // \Log data yang diterima dari request
        \Illuminate\Support\Facades\Log::info('Data yang diterima:', $request->all());

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'regional_agency_id' => 'required|exists:regional_agencies,id',
            'sector_id' => 'required|exists:sectors,id',
            'name' => 'required|string|max:80',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validasi gagal:', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($request->id);
            $data = ModelMap::with('documents')->find($id);

            if ($request->filled('file')) {
                \Illuminate\Support\Facades\Log::info('File terdeteksi dalam request.');

                $serverId = $request->input('file'); // FilePond mengirimkan serverId
                \Illuminate\Support\Facades\Log::info("Server ID FilePond: $serverId");

                $filepond = app(\Sopamo\LaravelFilepond\Filepond::class);
                $disk = config('filepond.temporary_files_disk');
                $temporaryPath = $filepond->getPathFromServerId($serverId);
                $fullpath = Storage::disk($disk)->path($temporaryPath);
                \Illuminate\Support\Facades\Log::info("Path sementara file: $temporaryPath");
                \Illuminate\Support\Facades\Log::info("Path lengkap file: $fullpath");

                if (! file_exists($fullpath)) {
                    \Log::error("File tidak ditemukan di lokasi sementara: $fullpath");

                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file ke folder tujuan
                $newFilePath = 'uploads/maps/'.basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);

                \Illuminate\Support\Facades\Log::info("File berhasil dipindahkan ke: $newFilePath");

                $fileInfo = pathinfo($fullpath);

                // Perbarui data file dalam database, bukan menghapusnya
                $document = $data->documents->where('documentable_id', $data->id)->first();
                if ($document && Storage::exists($document->path)) {
                    Storage::delete($document->path);
                    // Update data dokumen lama
                    $document->update([
                        'name' => $fileInfo['basename'],
                        'path' => $newFilePath,
                        'extension' => $fileInfo['extension'],
                        'mime_type' => mime_content_type($fullpath),
                        'size' => filesize($fullpath),
                    ]);
                    \Illuminate\Support\Facades\Log::info('ditemukan');
                } else {
                    // Jika tidak ada dokumen, buat baru
                    Document::create([
                        'name' => $fileInfo['basename'],
                        'path' => $newFilePath,
                        'extension' => $fileInfo['extension'],
                        'type' => 'map',
                        'documentable_type' => ModelMap::class,
                        'documentable_id' => $data->id,
                        'mime_type' => mime_content_type($fullpath),
                        'size' => filesize($fullpath),
                    ]);
                    \Illuminate\Support\Facades\Log::info('tidak ditemukan');
                }

                $data->slug = Str::slug($request->name);

                $data->fill($request->only(['user_id', 'regional_agency_id', 'sector_id', 'name']));

                // Extract GeoJSON coordinates
                $geojsonContent = file_get_contents($fullpath);
                $geojsonData = json_decode($geojsonContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Illuminate\Support\Facades\Log::error('Gagal parsing file GeoJSON: '.json_last_error_msg());

                    return redirect()->back()->with('error', 'File GeoJSON tidak valid.');
                }

                // Mengambil koordinat dari GeoJSON
                $coordinates = $this->extractCoordinates($geojsonData);
                \Illuminate\Support\Facades\Log::info('Extracted Coordinates:', $coordinates);

                if (empty($coordinates['latitude']) || empty($coordinates['longitude'])) {
                    \Illuminate\Support\Facades\Log::error('Koordinat tidak valid dalam file GeoJSON.');

                    return redirect()->back()->with('error', 'Koordinat tidak valid dalam file GeoJSON.');
                }

                // Update latitude dan longitude sebagai array dua dimensi
                $data->latitude = json_encode([$coordinates['latitude']]);
                $data->longitude = json_encode([$coordinates['longitude']]);

                // Simpan perubahan
                $data->save();

                \Illuminate\Support\Facades\Log::info('Peta berhasil diperbarui.');

                return redirect()->back()->with('success', 'Peta berhasil diperbarui.');
            } else {
                \Illuminate\Support\Facades\Log::warning('File tidak ditemukan dalam request.');
            }
        } catch (\Exception $e) {

            \Illuminate\Support\Facades\Log::error('Gagal memperbarui peta', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal memperbarui peta: '.$e->getMessage());
        }

        \Illuminate\Support\Facades\Log::error('Permintaan tidak valid: File harus diunggah.');

        return redirect()->back()->with('error', 'File harus diunggah.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Pastikan request memiliki ID
            if (! $request->has('id')) {
                return redirect()->back()->with('error', 'ID peta tidak ditemukan.');
            }

            // Dekripsi ID
            try {
                $id = Crypt::decrypt($request->id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                
                \Illuminate\Support\Facades\Log::error('Gagal dekripsi ID maps.', ['error' => $e->getMessage()]);

                return redirect()->back()->with('error', 'ID peta tidak valid.');
            }

            // Cari maps beserta dokumen terkait
            $maps = ModelMap::with('documents')->findOrFail($id);

            // Hapus dokumen jika ada
            if ($maps->documents->isNotEmpty()) {
                foreach ($maps->documents as $document) {
                    if ($document->documentable_id === $maps->id && $document->documentable_type === ModelMap::class) {
                        // Pastikan file masih ada sebelum dihapus
                        if (Storage::exists($document->path)) {
                            Storage::delete($document->path);
                        }
                        $document->delete(); // Hapus data dari tabel
                    }
                }
            }

            // Hapus maps
            $maps->delete();

            return redirect()->back()->with('success', 'peta berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal hapus peta', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus peta.');
        }
    }

    public function activate(Request $request): RedirectResponse
    {
        try {
            // Pastikan request memiliki ID
            if (! $request->has('id')) {
                return redirect()->back()->with('error', 'ID peta tidak ditemukan.');
            }

            // Dekripsi ID
            try {
                $id = Crypt::decrypt($request->id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                
                \Illuminate\Support\Facades\Log::error('Gagal dekripsi ID peta.', ['error' => $e->getMessage()]);

                return redirect()->back()->with('error', 'ID peta tidak valid.');
            }

            // Cari maps beserta dokumen terkait
            $maps = ModelMap::findOrFail($id);

            // Toggle status is_active
            $maps->is_active = ! $maps->is_active;
            $message = $maps->is_active ? 'diaktifkan' : 'dinonaktifkan';

            $maps->save();

            return redirect()->back()->with('success', "Peta berhasil $message.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengubah status peta', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status peta.');
        }
    }

    public function download(Request $request, $map, $id): Response
    {
        try {
            // Dekripsi ID
            $map = Crypt::decrypt($map);
            $id = Crypt::decrypt($id); // Dekripsi ID sebelum digunakan dalam query

            // Cari map berdasarkan ID
            $data = ModelMap::findOrFail($map);

            if ($data->can_download === false) {
                return redirect()->back()->with('info', 'Izin diperlukan untuk aksi ini.');
            }

            // Cari dokumen berdasarkan ID yang telah didekripsi
            $document = Document::findOrFail($id);

            // Ambil path file dari database
            $filePath = $document->path;
            $fileName = $document->name;
            $mimeType = $document->mime_type;

            // Cek apakah file ada di storage
            if (! Storage::disk('public')->exists($filePath)) {

                \Illuminate\Support\Facades\Log::error("File tidak ditemukan di path: $filePath");

                return redirect()->back()->with('error', 'File tidak ditemukan di server.');
            }

            // Kembalikan response download file
            return response()->download(Storage::disk('public')->path($filePath), $fileName, [
                'Content-Type' => $mimeType,
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            
            \Illuminate\Support\Facades\Log::error('Gagal dekripsi parameter', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'ID tidak valid.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::error('Data tidak ditemukan', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mendownload file', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal mendownload file.');
        }
    }
}
