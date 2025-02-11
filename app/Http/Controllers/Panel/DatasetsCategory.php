<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DatasetsCategory extends Controller
{
    public function index(): View
    {
        $count = Sector::count();
        $title = 'Kategori';
        $prefix = 'datasets';
        $description = 'Jelajahi kumpulan kategori dataset informatif dan terpercaya seputar '.env('APP_NAME', 'Satu Peta Purwakarta').'. Temukan wawasan, tips, dan panduan terbaru untuk meningkatkan pengetahuan Anda.';

        return view('panel.datasets', compact('prefix', 'count', 'title', 'description'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Sector::latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('action', function ($row) {
                        return '<ul class="preview-list">
                                                    <li class="preview-item">
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-dim btn-outline-warning rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#editGroupModal"
                                                data-name="'.$row->name.'"
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
                    ->rawColumns(['action'])
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
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
            \Log::info('Kategori berhasil ditambahkan:', $sector->toArray());

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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Crypt::decrypt($request->id);
            $data = Sector::find($id);
            $data->slug = Str::slug($request->name);

            $data->fill($request->only(['user_id', 'name']))->save();

            return redirect()->back()->with('success', 'Berhasil menyunting kategori: ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyunting kategori: '.$e->getMessage());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            $id = Crypt::decrypt($request->id);
            $sector = Sector::findOrFail($id);
            $sector->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
