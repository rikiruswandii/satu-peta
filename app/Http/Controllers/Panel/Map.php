<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Sector;
use App\Models\RegionalAgency;
use App\Models\Map as ModelMap;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class Map extends Controller
{
    public function index(): View
    {
        $maps = ModelMap::with('regional_agency', 'sector')->latest()->get();
        $regional_agencies = RegionalAgency::all();
        $sectors = Sector::all();
        $count = ModelMap::count();
        $title = 'Peta';
        $prefix = 'maps';
        $description = 'Jelajahi kumpulan peta informatif dan terpercaya seputar ' . env('APP_NAME', 'Satu Peta Purwakarta') . '. Temukan wawasan, tips, dan panduan terbaru untuk meningkatkan pengetahuan Anda.';

        return view('panel.geospatials.map', compact('prefix', 'maps', 'regional_agencies','sectors', 'count', 'title', 'description'));
    }

    public function create(): View
    {
        $regional_agencies = RegionalAgency::all();
        $sectors = Sector::all();
        $title = 'Peta';
        $description = 'Gunakan halaman ini untuk menambahkan peta baru. Isi judul, konten, dan kategori yang sesuai, lalu publikasikan untuk dibaca oleh pengunjung.';

        return view('panel.geospatials.partials.create', compact('regional_agencies', 'sectors', 'title', 'description'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
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

                $serverId = $request->input('file'); // FilePond mengirimkan serverId
                \Log::info("Server ID FilePond: $serverId");

                $filepond = app(\Sopamo\LaravelFilepond\Filepond::class);
                $disk = config('filepond.temporary_files_disk');
                $temporaryPath = $filepond->getPathFromServerId($serverId);
                $fullpath = Storage::disk($disk)->path($temporaryPath);

                \Log::info("Path sementara file: $temporaryPath");
                \Log::info("Path lengkap file: $fullpath");

                if (!file_exists($fullpath)) {
                    \Log::error("File tidak ditemukan di lokasi sementara: $fullpath");
                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file dari lokasi sementara ke folder final
                $newFilePath = 'uploads/maps/' . basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);
                \Log::info("File berhasil dipindahkan ke: $newFilePath");

                $fileInfo = pathinfo($fullpath);

                // Simpan artikel
                $data = ModelMap::create([
                    'user_id' => $request->user_id,
                    'regional_agency_id' => $request->regional_agency_id,
                    'sector_id' => $request->sector_id,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                ]);
                \Log::info("Peta berhasil dibuat dengan ID: {$data->id}");

                // Simpan data file ke tabel documents
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
            }
        } catch (\Exception $e) {
            \Log::error('Gagal membuat peta', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal membuat peta: ' . $e->getMessage());
        }

        \Log::error('Permintaan tidak valid: File harus diunggah.');
        return redirect()->back()->with('error', 'File harus diunggah.');
    }
    public function edit(Request $request, $id): View
    {
        try {
            // Dekripsi ID
            $id = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Log::error('Gagal dekripsi ID artikel.', ['error' => $e->getMessage()]);
            abort(404, 'Artikel tidak ditemukan.');
        }

        // Ambil data artikel
        $data = ModelMap::findOrFail($id);

        // Ambil semua kategori
        $regional_agencies = RegionalAgency::all();
        $sectors = Sector::all();

        // Jika kategori kosong, berikan log peringatan
        if ($regional_agencies->isEmpty()) {
            \Log::warning('Tidak ada perangkat daerah yang ditemukan untuk peta.', ['map_id' => $id]);
        }

        // Jika sectors kosong, berikan log peringatan
        if ($sectors->isEmpty()) {
            \Log::warning('Tidak ada perangkat daerah yang ditemukan untuk peta.', ['map_id' => $id]);
        }

        $title = 'Peta';
        $description = 'Gunakan halaman ini untuk menyunting peta. Isi nama, perangkat daerah, dan sektor yang sesuai, lalu publikasikan untuk dilihat oleh pengunjung.';

        return view('panel.geospatials.partials.edit', compact('data', 'regional_agencies', 'sectors', 'title', 'description'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

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
            $id = Crypt::decrypt($id);
            $data = ModelMap::with('documents')->find($id);

            if ($request->filled('file')) {
                \Log::info('File terdeteksi dalam request.');

                $serverId = $request->input('file'); // FilePond mengirimkan serverId
                \Log::info("Server ID FilePond: $serverId");

                $filepond = app(\Sopamo\LaravelFilepond\Filepond::class);
                $disk = config('filepond.temporary_files_disk');
                $temporaryPath = $filepond->getPathFromServerId($serverId);
                $fullpath = Storage::disk($disk)->path($temporaryPath);

                \Log::info("Path sementara file: $temporaryPath");
                \Log::info("Path lengkap file: $fullpath");

                if (!file_exists($fullpath)) {
                    \Log::error("File tidak ditemukan di lokasi sementara: $fullpath");
                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file ke folder tujuan
                $newFilePath = 'uploads/maps/' . basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);

                \Log::info("File berhasil dipindahkan ke: $newFilePath");

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
                    \Log::info('ditemukan');
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
                    \Log::info('tidak ditemukan');
                }

                $data->slug = Str::slug($request->name);

                $data->fill($request->only(keys: ['user_id', 'regional_agency_id', 'sector_id', 'name']))->save();
                \Log::info("File berhasil disimpan dalam database dengan path: $newFilePath");

                return redirect()->back()->with('success', 'Peta berhasil dibuat.');
            } else {
                \Log::warning('File tidak ditemukan dalam request.');
            }
        } catch (\Exception $e) {
            \Log::error('Gagal membuat peta', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal membuat peta: ' . $e->getMessage());
        }

        \Log::error('Permintaan tidak valid: File harus diunggah.');
        return redirect()->back()->with('error', 'File harus diunggah.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Pastikan request memiliki ID
            if (!$request->has('id')) {
                return redirect()->back()->with('error', 'ID peta tidak ditemukan.');
            }

            // Dekripsi ID
            try {
                $id = Crypt::decrypt($request->id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                \Log::error('Gagal dekripsi ID maps.', ['error' => $e->getMessage()]);
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
            \Log::error('Gagal hapus peta', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus peta.');
        }
    }


    public function regional_agency_store(Request $request): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        // Validasi input
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:regional_agencies,name',
        ]);

        // Log hasil validasi
        if ($validator->fails()) {
            \Log::info('Validasi gagal:', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Buat kategori baru
            $regional_agency = RegionalAgency::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            // Log keberhasilan pembuatan kategori
            \Log::info('Perangkat Daerah berhasil ditambahkan:', $regional_agency->toArray());

            return redirect()->back()->with('success', 'Berhasil menambahkan perangkat daerah: ' . $request->name);
        } catch (\Exception $e) {
            // Log error jika terjadi exception
            \Log::error('Gagal menambahkan perangkat daerah:', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat perangkat daerah: ' . $e->getMessage());
        }
    }
    
    public function sector_store(Request $request): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        // Validasi input
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:sectors,name',
        ]);

        // Log hasil validasi
        if ($validator->fails()) {
            \Log::info('Validasi gagal:', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Buat kategori baru
            $sector = Sector::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            // Log keberhasilan pembuatan kategori
            \Log::info('Sektor berhasil ditambahkan:', $sector->toArray());

            return redirect()->back()->with('success', 'Berhasil menambahkan sektor: ' . $request->name);
        } catch (\Exception $e) {
            // Log error jika terjadi exception
            \Log::error('Gagal menambahkan sektor:', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat sektor: ' . $e->getMessage());
        }
    }

    public function regional_agency_update(Request $request, $id): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:regional_agencies,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($id);
            $data = RegionalAgency::find($id);
            $data->slug = Str::slug($request->name);

            $data->fill($request->only(['user_id', 'name']))->save();

            return redirect()->back()->with('success', 'Berhasil menyunting perangkat daerah: ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyunting perangkat daerah: ' . $e->getMessage());
        }
    }
    
    public function sector_update(Request $request, $id): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:sectors,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($id);
            $data = Sector::find($id);
            $data->slug = Str::slug($request->name);

            $data->fill($request->only(['user_id', 'name']))->save();

            return redirect()->back()->with('success', 'Berhasil menyunting sektor: ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyunting sektor: ' . $e->getMessage());
        }
    }

    public function regional_agency_destroy(Request $request): RedirectResponse
    {
        try {
            $regional_agency = RegionalAgency::findOrFail($request->id);
            $regional_agency->delete();

            return redirect()->back()->with('success', 'Perangkat Daerah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus perangkat daerah.');
        }
    }
    
    public function sector_destroy(Request $request): RedirectResponse
    {
        try {
            $sector = Sector::findOrFail($request->id);
            $sector->delete();

            return redirect()->back()->with('success', 'Sektor berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus sektor.');
        }
    }
}