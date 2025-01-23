<?php

namespace App\Http\Controllers\Panel\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class Detail extends Controller
{
    public function index($id): View
    {
        $decrypt = Crypt::decrypt($id);
        $user = User::findOrFail($decrypt);
        $title = 'Profil User';
        $description = $title . ' page!';

        return view('panel.user.detail', compact('user', 'title', 'description'))->with('encrypt', $id);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        \Log::info('start.');
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $decrypt = Crypt::decrypt($id);
            $user = User::findOrFail($decrypt);


            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;

            // Simpan perubahan ke database
            $user->save();
            \Log::info('User berhasil diupdate:', $user->toArray());
            if (request()->routeIs('user.detail') || request()->routeIs('user.activity')) {
                return redirect()->route('user.detail', ['id' => $id])->with([
                    'success' => 'Profil berhasil diperbarui.',
                ]);
            }

            return redirect()->route('profile.user', ['id' => $id])->with([
                'success' => 'Profil berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui profil:', ['message' => $e->getMessage()]);

            return back()->withInput()->with([
                'error' => 'Gagal memperbarui profil: ' . $e->getMessage(),
            ]);
        }
    }

    public function photo(Request $request, $id): RedirectResponse
    {
        // Validasi file avatar
        $validator = \Validator::make(
            $request->all(),[
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $decrypt = Crypt::decrypt($id);
            $user = User::findOrFail($decrypt);

            // Jika ada file image yang diunggah
            if ($request->hasFile('image')) {
                // Hapus file lama dari storage jika ada
                if ($user->image) {
                    Storage::disk('public')->delete('uploads/' . $user->image);
                }

                // Dapatkan file dari request
                $file = $request->file('image');

                // Tentukan nama file yang di-hash
                $fileName = $file->hashName();

                // Simpan file baru ke storage di folder 'uploads' (disk 'public') dengan nama yang di-hash
                $path = $file->storeAs('uploads', $fileName, 'public');

                if (! $path) {
                    return redirect()->route('profile.user', ['id' => $id])->with('error', 'Gagal menyimpan file.');
                }

                // Simpan nama file baru (yang di-hash) ke dalam database user
                $user->image = $fileName;
                $user->save();
            }

            // Cek route yang dipanggil
            if (request()->routeIs('user.detail') || request()->routeIs('user.activity')) {
                return redirect()->route('user.detail', ['id' => $id])->with([
                    'success' => 'Avatar berhasil diperbarui.',
                ]);
            }

            return redirect()->route('profile.user', ['id' => $id])->with([
                'success' => 'Avatar berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->with([
                'error' => 'Gagal memperbarui profil: ' . $e->getMessage(),
            ]);
        }
    }

    public function changePassword(Request $request, $id): RedirectResponse
    {
        $validator = \Validator::make(
            $request->all(),
            [
            'current_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W]).*$/',
                'confirmed',
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $decrypt = Crypt::decrypt($id);
            $user = User::findOrFail($decrypt);


            if (! Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'error' => ['The provided password does not match our records.'],
                ]);
            }

            // Logout from all other devices before changing the password
            Auth::logoutOtherDevices($request->current_password);

            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            if (request()->routeIs('user.detail')) {
                return redirect()->route('user.detail', ['id' => $id])->with([
                    'success' => 'Password changed successfully!',
                ]);
            }

            return redirect()->route('profile.user', ['id' => $id])->with([
                'success' => 'Password changed successfully!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('profile.user', ['id' => $id])->with('error', 'Password change failed!');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back()->with([
            'success' => 'Account berhasil dihapus.',
        ]);
    }
}
