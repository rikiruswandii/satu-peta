<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Tags\Tag;
use Yajra\DataTables\Facades\DataTables;

class Category extends Controller
{
    public function index(): View
    {
        $count = Tag::count();
        $title = 'Kategori';
        $prefix = 'category';
        $description = 'Jelajahi kumpulan kategori dataset informatif dan terpercaya seputar '.env('APP_NAME', 'Satu Peta Purwakarta').'. Temukan wawasan, tips, dan panduan terbaru untuk meningkatkan pengetahuan Anda.';

        return view('panel.category', compact('prefix', 'count', 'title', 'description'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Tag::latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('tags', function ($row) {
                        return $row->name ?? '-';
                    })
                    ->addColumn('action', function ($row) {
                        return '<ul class="preview-list">
                                                    <li class="preview-item">
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-dim btn-outline-warning rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#editGroupModal"
                                                data-name="'.$row->name.'"
                                                data-type="'.$row->type.'"
                                                data-id="'.Crypt::encrypt($row->id).'">
                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                            </a>
                                                    </li>
                                                    <li class="preview-item">
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-dim btn-outline-danger rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#deleteMapModal"
                                                data-id="'.Crypt::encrypt($row->id).'"
                                                data-name="'.$row->name.'">
                                                <em class="icon ni ni-trash"></em><span>Delete</span>
                                            </a>
                                                    </li>
                                                </ul>';
                    })
                    ->rawColumns(['action', 'tags'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());

                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }

    public function store(Request $request): RedirectResponse
    {
        // Log data yang diterima dari request
        \Log::info('Data yang diterima:', $request->all());

        // Validasi input
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'category_type' => 'nullable|string',
        ]);

        // Log hasil validasi
        if ($validator->fails()) {
            \Log::info('Validasi gagal:', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Buat kategori baru
            $category = Tag::findOrCreate($request->name, $request->category_type ?? null);

            // Log keberhasilan pembuatan kategori
            \Log::info('Kategori berhasil ditambahkan:', $category->toArray());

            return redirect()->back()->with('success', 'Berhasil menambahkan kategori: '.$request->name);
        } catch (\Exception $e) {
            // Log error jika terjadi exception
            \Log::error('Gagal menambahkan kategori:', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat kategori: '.$e->getMessage());
        }
    }

    public function update(Request $request): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'category_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($request->id);
            $data = Tag::find($id);

            if (! $data) {
                return redirect()->back()->with('error', 'Tag tidak ditemukan.');
            }

            // Update kolom name dalam format JSON multi-language
            $data->setTranslation('name', 'id', $request->name);
            $data->setTranslation('slug', 'id', Str::slug($request->name)); // Slug juga dalam format JSON

            $data->type = $request->category_type;
            $data->save();

            return redirect()->back()->with('success', 'Berhasil menyunting tag: '.$request->name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyunting tag: '.$e->getMessage());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            $id = Crypt::decrypt($request->id);
            $sector = Tag::findOrFail($id);
            $sector->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
