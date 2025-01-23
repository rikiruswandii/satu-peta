<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class Users extends Controller
{
    public function index(): View
    {
        $count = User::count() - 1;
        $roles = Role::all();
        $title = 'List User';
        $description = $title . ' page!';
        $data = User::with('role')
            ->latest()
            ->where('email', '!=', 'mikeumikeudeh@gmail.com')
            ->get();

        return view('panel.users', compact('data','count', 'roles', 'title', 'description'));
    }

    public function getDataTable(Request $request)
    {
        if ($request->ajax()) {
            try {
                \Log::info('Memulai proses pengambilan data pengguna.');

                $data = User::with('role')
                ->latest()
                    ->where('email', '!=', 'mikeumikeudeh@gmail.com')
                    ->get();
                \Log::info('Data pengguna berhasil diambil.', ['data_count' => $data->count()]);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<div class="drodown">
                                                    <a href="#" class="btn btn-sm btn-icon btn-trigger dropdown-toggle" data-bs-toggle="dropdown"><em class="icon ni ni-more-h rounded-full hover:!bg-color-secondary hover:!bg-opacity-30 hover:!text-gray-500"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="' . route('user.detail', ['id' => Crypt::encrypt($row->id)]) . '"><em class="icon ni ni-eye text-blue-500"></em><span>View Details</span></a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#resetPasswordModal"><em class="icon ni ni-shield-star text-color-secondary"></em><span>Reset Pass</span></a></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                data-bs-target="#deleteUserModal" data-id="' . $row->id . '" data-name="' . $row->name . '"><em class="icon ni ni-trash text-red-500"></em><span>Delete User</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('Terjadi kesalahan saat mengambil data pengguna: ' . $e->getMessage());

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
            $user = User::findOrFail($request->id);
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->back()->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }
}
