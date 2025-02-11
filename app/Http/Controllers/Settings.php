<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\RelatedLink;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class Settings extends Controller
{
    protected $setting;

    public function __construct(GeneralSettings $settings)
    {
        $this->setting = $settings;
    }

    public function index(): View
    {
        $tautan = RelatedLink::where('is_active', true)->orderBy('order')->get();
        $data = $this->setting;
        $relatedLinks = RelatedLink::with('documents')->latest()
            ->get();
        $prefix = 'settings';
        $title = 'Settings';
        $description = $title.' page!';

        return view('panel.settings', compact('relatedLinks', 'data', 'tautan', 'prefix', 'title', 'description'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = RelatedLink::with('documents')->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('logo', function ($row) {
                        if ($row->documents->isNotEmpty()) {
                            $logo = '<div class="user-avatar sq">';
                            foreach ($row->documents as $document) {
                                if ($document->path) {
                                    $logo .= '<img src="'.Storage::url($document->path).'" alt="Avatar Pengguna">';
                                } else {
                                    $logo .= '<img src="'.asset('assets/images/default.png').'" alt="Avatar Default">';
                                }
                            }
                            $logo .= '</div>';

                            return $logo;
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
                                        <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#updateModal"
                                                data-id="'.Crypt::encrypt($row->id).'"
                                                data-name="'.$row->title.'"
                                                data-url="'.$row->url.'"
                                                >
                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                            </a></li>
                                        <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#deleteMapModal"
                                                data-id="'.Crypt::encrypt($row->id).'"
                                                data-name="'.$row->name.'">
                                                <em class="icon ni ni-trash"></em><span>Delete</span>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>';
                    })
                    ->rawColumns(['action', 'logo'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());

                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }

    public function update(Request $request): RedirectResponse
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'about' => 'required|string',
                'email' => 'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'address' => 'required',
                'string',
                'phone' => 'required',
                'string',
                'max:13',
                'logo' => 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->hasFile('logo')) {
                if ($this->setting->logo) {
                    Storage::disk('public')->delete('logos/'.$this->setting->logo);
                }

                $file = $request->file('logo');

                $fileName = $file->hashName();

                $stored = $file->storeAs('logos', $fileName, 'public');

                if (! $stored) {
                    return redirect()->back()->withErrors('errorr', 'Gagal menyimpan file!');
                }

                $this->setting->logo = $fileName;
            }

            $this->setting->name = $request->name;
            $this->setting->about = $request->about;
            $this->setting->email = $request->email;
            $this->setting->address = $request->address;
            $this->setting->phone = $request->phone;

            $this->setting->save();

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::info('error : '.$e->getMessage());

            // \Log::info('info : ' . $request->all());
            return redirect()->back()->withErrors(['error', 'Pengaturan gagal diperbarui.'.$e->getMessage()]);
        }
    }

    // public function posts(Request $request): RedirectResponse
    // {
    //     $validator = \Validator::make(
    //         $request->all(),
    //         [
    //             'post' => 'required',
    //             'string',
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     try {

    //         $this->setting->post = $request->post;

    //         $this->setting->save();

    //         return redirect()->back()->with('success', 'Setting App berhasil diperbarui!');
    //     } catch (\Exception $e) {
    //         \Log::info('error : ' . $e->getMessage());

    //         // \Log::info('info : ' . $request->all());
    //         return redirect()->back()->withErrors(['error', 'Setting App gagal diperbarui.' . $e->getMessage()]);
    //     }
    // }

    public function store(Request $request): RedirectResponse
    {
        \Log::info('Request store diterima: ', $request->all());

        $validator = \Validator::make(
            $request->all(),
            [
                'user_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'url' => 'required|url|max:2048',
                'file' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            \Log::info('Validasi gagal: ', $validator->errors()->toArray());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \Log::info('Memproses request store...');

            if ($request->has('file')) {
                \Log::info('File ditemukan dalam request.');

                // Ambil serverId dari request
                $serverId = $request->input('file');
                \Log::info('Server ID dari FilePond: ', ['serverId' => $serverId]);

                // Mendapatkan path file sementara dari server menggunakan serverId
                $filepond = app(\Sopamo\LaravelFilepond\Filepond::class);
                $disk = config('filepond.temporary_files_disk');
                \Log::info('Disk yang digunakan untuk file sementara: ', ['disk' => $disk]);

                // Mendapatkan path sementara dari FilePond
                $temporaryPath = $filepond->getPathFromServerId($serverId);
                \Log::info('Path sementara file: ', ['temporaryPath' => $temporaryPath]);

                $fullpath = Storage::disk($disk)->path($temporaryPath);
                \Log::info('Path lengkap file sementara: ', ['fullpath' => $fullpath]);

                // Cek jika file sementara ada
                if (! file_exists($fullpath)) {
                    \Log::error('File sementara tidak ditemukan: ', ['fullpath' => $fullpath]);

                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file dari lokasi sementara ke folder final
                $newFilePath = 'uploads/logos/'.basename($temporaryPath);
                \Log::info('Path baru untuk file: ', ['newFilePath' => $newFilePath]);

                // Menggunakan put untuk menyimpan file ke disk 'public'
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::disk(env('FILESYSTEM_DISK'))->put($newFilePath, $temporaryFile);
                \Log::info('File berhasil dipindahkan ke lokasi final.');

                // Ambil informasi file
                $fileInfo = pathinfo($fullpath);
                \Log::info('Informasi file: ', ['fileInfo' => $fileInfo]);

                // Simpan data ke tabel RelatedLink
                $relatedLink = RelatedLink::create([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'url' => $request->url,
                ]);
                \Log::info('Data RelatedLink berhasil dibuat: ', ['relatedLink' => $relatedLink]);

                // Simpan data file ke tabel documents
                $document = Document::create([
                    'name' => $fileInfo['basename'], // Nama lengkap file (termasuk ekstensi)
                    'path' => $newFilePath,
                    'extension' => $fileInfo['extension'], // Ekstensi file
                    'type' => 'logo',
                    'documentable_type' => RelatedLink::class,
                    'documentable_id' => $relatedLink->id,
                    'mime_type' => mime_content_type($fullpath),
                    'size' => filesize($fullpath),
                ]);
                \Log::info('File berhasil disimpan ke tabel documents: ', ['document' => $document]);

                return redirect()->back()->with('success', 'Tautan berhasil dibuat.');
            } else {
                \Log::info('Tidak ada file dalam request.');

                // Jika tidak ada file logo, tetap simpan data
                RelatedLink::create([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'url' => $request->url,
                ]);

                return redirect()->back()->with('success', 'Tautan berhasil dibuat tanpa logo.');
            }
        } catch (\Exception $e) {
            \Log::error('Gagal membuat tautan: '.$e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat tautan: '.$e->getMessage());
        }
    }

    public function updateTautan(Request $request): RedirectResponse
    {
        \Log::info('Request update diterima: ', $request->all());

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'file' => 'required|string',
        ]);

        if ($validator->fails()) {
            \Log::info('Validasi gagal: ', $validator->errors()->toArray());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Dekripsi ID
            $id = Crypt::decrypt($request->id);
            \Log::info('ID berhasil didekripsi: ', ['id' => $id]);

            // Temukan data berdasarkan ID
            $data = RelatedLink::findOrFail($id);
            \Log::info('Data RelatedLink ditemukan: ', ['data' => $data]);

            if ($request->filled('file')) {
                Log::info('File terdeteksi dalam request.');

                $serverId = $request->input('file'); // FilePond mengirimkan serverId
                Log::info("Server ID FilePond: $serverId");

                $filepond = app(\Sopamo\LaravelFilepond\Filepond::class);
                $disk = config('filepond.temporary_files_disk');
                $temporaryPath = $filepond->getPathFromServerId($serverId);
                $fullpath = Storage::disk($disk)->path($temporaryPath);

                Log::info("Path sementara file: $temporaryPath");
                Log::info("Path lengkap file: $fullpath");

                if (! file_exists($fullpath)) {
                    Log::error("File tidak ditemukan di lokasi sementara: $fullpath");

                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }

                // Pindahkan file ke folder tujuan
                $newFilePath = 'uploads/logos/'.basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);

                Log::info("File berhasil dipindahkan ke: $newFilePath");

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
                    Log::info('ditemukan');
                } else {
                    // Jika tidak ada dokumen, buat baru
                    Document::create([
                        'name' => $fileInfo['basename'],
                        'path' => $newFilePath,
                        'extension' => $fileInfo['extension'],
                        'type' => 'logo',
                        'documentable_type' => RelatedLink::class,
                        'documentable_id' => $data->id,
                        'mime_type' => mime_content_type($fullpath),
                        'size' => filesize($fullpath),
                    ]);
                    Log::info('tidak ditemukan');
                }
                \Log::info('File berhasil disimpan ke tabel documents: ', ['document' => $document]);
            }

            // Simpan data lainnya
            $data->fill($request->only(['title', 'user_id', 'url']))->save();
            \Log::info('Data RelatedLink berhasil diupdate: ', ['data' => $data]);

            return redirect()->back()->with('success', 'Tautan berhasil diupdate.');
        } catch (\Exception $e) {
            \Log::error('Gagal mengupdate tautan: '.$e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->withErrors([
                'error' => 'Gagal mengupdate tautan: '.$e->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $tautan = RelatedLink::findOrFail($id);
            $oldDocument = $tautan->documents()->where('documentable_id', $tautan->id)->first();
            if ($oldDocument) {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($oldDocument->path); // Hapus file lama
                $oldDocument->delete(); // Hapus data lama dari tabel documents
            }
            $tautan->delete();

            return redirect()->back()->with('success', 'Tautan berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Gagal hapus tautan: '.$e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus tautan.');
        }
    }
}
