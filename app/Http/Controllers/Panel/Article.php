<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Article as ModelsArticle;
use App\Models\Category;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class Article extends Controller
{
    public function index(): View
    {
        $categories = Category::all();
        $count = ModelsArticle::count();
        $title = 'Artikel';
        $prefix = 'articles';
        $description = 'Jelajahi kumpulan artikel informatif dan terpercaya seputar '.env('APP_NAME', 'Satu Peta Purwakarta').'. Temukan wawasan, tips, dan panduan terbaru untuk meningkatkan pengetahuan Anda.';

        return view('panel.news.article', compact('prefix', 'categories', 'count', 'title', 'description'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = ModelsArticle::with('category')->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->editColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('thumbnail', function ($row) {
                        if ($row->documents->isNotEmpty()) {
                            $thumbnail = '<div class="user-avatar sq">';
                            foreach ($row->documents as $document) {
                                if ($document->path) {
                                    $thumbnail .= '<img src="'.Storage::url($document->path).'" alt="Avatar Pengguna">';
                                } else {
                                    $thumbnail .= '<img src="'.asset('assets/images/default.png').'" alt="Avatar Default">';
                                }
                            }
                            $thumbnail .= '</div>';

                            return $thumbnail;
                        }

                        return '<img src="'.asset('assets/images/default.png').'" alt="Avatar Default">';
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="dropdown">
                                <a href="#" class="btn btn-sm btn-icon btn-trigger dropdown-toggle" data-bs-toggle="dropdown">
                                    <em class="icon ni ni-more-h rounded-full"></em>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="'.route('articles.edit', ['id' => Crypt::encrypt($row->id)]).'"
                                                data-id="'.Crypt::encrypt($row->id).'">
                                                <em class="icon ni ni-edit"></em><span>Edit</span>
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
                    ->rawColumns(['action', 'thumbnail'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());

                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }

    public function create(): View
    {
        $categories = Category::all();
        $title = 'Artikel';
        $description = 'Gunakan halaman ini untuk membuat artikel baru. Isi judul, konten, dan kategori yang sesuai, lalu publikasikan untuk dibaca oleh pengunjung.';

        return view('panel.news.partials.create', compact('categories', 'title', 'description'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'category_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
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

                if (! file_exists($fullpath)) {
                    \Log::error("File tidak ditemukan di lokasi sementara: $fullpath");

                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file dari lokasi sementara ke folder final
                $newFilePath = 'uploads/thumbnails/'.basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);
                \Log::info("File berhasil dipindahkan ke: $newFilePath");

                $fileInfo = pathinfo($fullpath);

                // Simpan artikel
                $data = ModelsArticle::create([
                    'user_id' => $request->user_id,
                    'category_id' => $request->category_id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'slug' => Str::slug($request->title),
                ]);
                \Log::info("Artikel berhasil dibuat dengan ID: {$data->id}");

                // Simpan data file ke tabel documents
                Document::create([
                    'name' => $fileInfo['basename'],
                    'path' => $newFilePath,
                    'extension' => $fileInfo['extension'],
                    'type' => 'thumbnails',
                    'documentable_type' => ModelsArticle::class,
                    'documentable_id' => $data->id,
                    'mime_type' => mime_content_type($fullpath),
                    'size' => filesize($fullpath),
                ]);
                \Log::info("File berhasil disimpan dalam database dengan path: $newFilePath");

                return redirect()->back()->with('success', 'Artikel berhasil dibuat.');
            } else {
                \Log::warning('File tidak ditemukan dalam request.');
            }
        } catch (\Exception $e) {
            \Log::error('Gagal membuat artikel', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal membuat artikel: '.$e->getMessage());
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
        $data = ModelsArticle::findOrFail($id);

        // Ambil semua kategori
        $categories = Category::all();

        // Jika kategori kosong, berikan log peringatan
        if ($categories->isEmpty()) {
            \Log::warning('Tidak ada kategori yang ditemukan untuk artikel.', ['article_id' => $id]);
        }

        $title = 'Artikel';
        $description = 'Gunakan halaman ini untuk menyunting artikel. Isi judul, konten, dan kategori yang sesuai, lalu publikasikan untuk dibaca oleh pengunjung.';

        return view('panel.news.partials.edit', compact('data', 'categories', 'title', 'description'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'required|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validasi gagal:', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($id);
            $data = ModelsArticle::with('documents')->find($id);

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

                if (! file_exists($fullpath)) {
                    \Log::error("File tidak ditemukan di lokasi sementara: $fullpath");

                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file ke folder tujuan
                $newFilePath = 'uploads/thumbnails/'.basename($temporaryPath);
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
                        'type' => 'thumbnail',
                        'documentable_type' => ModelsArticle::class,
                        'documentable_id' => $data->id,
                        'mime_type' => mime_content_type($fullpath),
                        'size' => filesize($fullpath),
                    ]);
                    \Log::info('tidak ditemukan');
                }

                $data->slug = Str::slug($request->title);

                $data->fill($request->only(keys: ['user_id', 'category_id', 'title', 'content']))->save();
                \Log::info("File berhasil disimpan dalam database dengan path: $newFilePath");

                return redirect()->back()->with('success', 'Artikel berhasil dibuat.');
            } else {
                \Log::warning('File tidak ditemukan dalam request.');
            }
        } catch (\Exception $e) {
            \Log::error('Gagal membuat artikel', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal membuat artikel: '.$e->getMessage());
        }

        \Log::error('Permintaan tidak valid: File harus diunggah.');

        return redirect()->back()->with('error', 'File harus diunggah.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Pastikan request memiliki ID
            if (! $request->has('id')) {
                return redirect()->back()->with('error', 'ID artikel tidak ditemukan.');
            }

            // Dekripsi ID
            try {
                $id = Crypt::decrypt($request->id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                \Log::error('Gagal dekripsi ID artikel.', ['error' => $e->getMessage()]);

                return redirect()->back()->with('error', 'ID artikel tidak valid.');
            }

            // Cari artikel beserta dokumen terkait
            $artikel = ModelsArticle::with('documents')->findOrFail($id);

            // Hapus dokumen jika ada
            if ($artikel->documents->isNotEmpty()) {
                foreach ($artikel->documents as $document) {
                    if ($document->documentable_id === $artikel->id && $document->documentable_type === ModelsArticle::class) {
                        // Pastikan file masih ada sebelum dihapus
                        if (Storage::exists($document->path)) {
                            Storage::delete($document->path);
                        }
                        $document->delete(); // Hapus data dari tabel
                    }
                }
            }

            // Hapus artikel
            $artikel->delete();

            return redirect()->back()->with('success', 'Artikel berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Gagal hapus artikel', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus artikel.');
        }
    }

    public function category_store(Request $request): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        // Validasi input
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|string|max:255|unique:'.Category::class,
        ]);

        // Log hasil validasi
        if ($validator->fails()) {
            \Log::info('Validasi gagal:', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Buat kategori baru
            $category = Category::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            // Log keberhasilan pembuatan kategori
            \Log::info('Kategori berhasil dibuat:', $category->toArray());

            return redirect()->back()->with('success', 'Berhasil membuat kategori: '.$request->name);
        } catch (\Exception $e) {
            // Log error jika terjadi exception
            \Log::error('Gagal membuat kategori:', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat kategori: '.$e->getMessage());
        }
    }

    public function category_update(Request $request, $id): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($id);
            $data = Category::find($id);
            $data->slug = Str::slug($request->name);

            $data->fill($request->only(['user_id', 'name']))->save();

            return redirect()->back()->with('success', 'Berhasil menyunting kategori: ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyunting kategori: '.$e->getMessage());
        }
    }

    public function category_destroy(Request $request): RedirectResponse
    {
        try {
            $categories = Category::findOrFail($request->id);
            $categories->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
