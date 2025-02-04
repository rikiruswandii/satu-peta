<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class Users extends Controller
{
    public function index(): View
    {
        $count = User::count() - 1;
        $roles = Role::all();
        $title = 'List User';
        $description = $title . ' page!';
        $prefix = 'users';

        return view('panel.users', compact('prefix','count', 'roles', 'title', 'description'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = User::with('role')
                    ->latest()
                    ->where('email', '!=', 'mikeumikeudeh@gmail.com')->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->editColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="dropdown">
                                <a href="#" class="btn btn-sm btn-icon btn-trigger dropdown-toggle" data-bs-toggle="dropdown">
                                    <em class="icon ni ni-more-h rounded-full"></em>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="' . route('user.detail', ['id' => Crypt::encrypt($row->id)]) . '"
                                                data-id="' . Crypt::encrypt($row->id) . '">
                                                <em class="icon ni ni-eye"></em><span>Detail</span>
                                            </a></li>
                                            <li class="divider"></li>
                                        <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#resetPasswordModal">
                                                <em class="icon ni ni-shield-star"></em><span>Reset Kata Sandi</span>
                                            </a></li>
                                        <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#deleteMapModal"
                                                data-id="' . Crypt::encrypt($row->id) . '"
                                                data-name="' . $row->name . '">
                                                <em class="icon ni ni-trash"></em><span>Delete</span>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }


    public function detail($id)
    {
        try {
            $decrypt = Crypt::decrypt($id);
            $user = User::findOrFail($decrypt);
            $title = 'Detail User';
            $description = $title . ' page!';

            return view('users.profile-user', compact('user', 'title', 'description'))->with('encrypt', $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function reset(Request $request): RedirectResponse
    {
        // Validasi input, pastikan email ada dalam tabel dan sesuai format
        $validator = \Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->exists()) {
                        $fail('The provided email does not match any user in our records.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Reset password ke password default
            $password = env('DEFAULT_PASSWORD');
            $user->password = Hash::make($password);
            $user->save();

            return redirect()->back()->with('success', 'Password has been reset to default.');
        }

        return back()->withErrors(['error' => 'No user found with this email address.']);
    }


    public function store(Request $request): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(env('DEFAULT_PASSWORD')),
            'email_verified_at' => Carbon::now(),
            'role_id' => $request->role_id,
        ]);

        if ($data){
            return redirect()->back()->with([
                'success' => 'Akun berhasil dibuat.',
            ]);
        }

        return redirect()->back()->with(['error' => 'Akun gagal dibuat.']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            $id = Crypt::decrypt($request->id);
            $user = User::findOrFail($id);
            
            // Logout pengguna jika mereka sedang login
            if (Auth::id() == $user->id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            $user->delete();

            return redirect()->back()->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }
}