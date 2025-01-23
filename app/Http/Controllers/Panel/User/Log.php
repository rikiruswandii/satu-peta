<?php

namespace App\Http\Controllers\Panel\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class Log extends Controller
{
    public function index(): View
    {
        $count = Activity::where('causer_id', '!=', 2)->count();

        $header = 'Users';
        $title = 'Log Aktivitas';
        $description = $title . ' page!';
        $data = Activity::select('activity_log.*', 'users.name as causer_name')
            ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
            ->where('causer_id', '!=', 2)
            ->latest()->get();

        return view('panel.logs', compact('data', 'count', 'header', 'title', 'description'))->with('encrypt');
    }

    public function userLog($id)
    {
        $decrypt = Crypt::decrypt($id);
        $user = User::findOrFail($decrypt);
        $count = Activity::where('causer_id', $user->id)->count();
        $title = 'Log Aktivitas';
        $description = $title . ' page!';
        $data = Activity::where('causer_id', $user)
            ->where('causer_type', User::class)
            ->orderByDesc('created_at')
            ->get();

        return view('panel.user.log', compact('data', 'count', 'user', 'title', 'description'))->with('encrypt', $id);
    }
}
