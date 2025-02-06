<?php

namespace App\Http\Controllers\Panel\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class Log extends Controller
{
    public function index(): View
    {
        $count = Activity::where('causer_id', '!=', 1)->count();

        $header = 'Users';
        $title = 'Log Aktivitas';
        $description = $title . ' page!';
        $data = Activity::select('activity_log.*', 'users.name as causer_name')
            ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
            ->where('causer_id', '!=', 2)
            ->latest()->get();

        return view('panel.logs', compact('data', 'count', 'header', 'title', 'description'))->with('encrypt');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Activity::select('activity_log.*', 'users.name as causer_name')
                    ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
                    ->where('causer_id', '!=', 1)->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function datatable_id(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $decrypt = Crypt::decrypt($id);
                $user = User::findOrFail($decrypt);
                $data = Activity::where('causer_id', $user->id) // Menggunakan ID user
                    ->where('causer_type', User::class)
                    ->orderByDesc('created_at')->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }

    public function userLog($id)
    {
        // Dekripsi ID
        $decrypt = Crypt::decrypt($id);

        // Mencari user berdasarkan ID yang didekripsi
        $user = User::findOrFail($decrypt);

        // Menghitung jumlah aktivitas user
        $count = Activity::where('causer_id', $user->id)->count();

        // Menentukan judul dan deskripsi
        $title = 'Log Aktivitas';
        $description = $title . ' page!';

        // Mengambil data aktivitas user
        $data = Activity::where('causer_id', $user->id) // Menggunakan ID user
            ->where('causer_type', User::class)
            ->orderByDesc('created_at')
            ->get();

        // Mengirim data ke view
        return view('panel.user.log', compact('data', 'count', 'user', 'title', 'description', 'id'))->with('encrypt', $id);
    }

}