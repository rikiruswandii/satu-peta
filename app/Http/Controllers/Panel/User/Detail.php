<?php

namespace App\Http\Controllers\Panel\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
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
        $description = $title.' page!';

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

            // Simpan perubahan ke database
            $user->save();

            return redirect()->route('user.detail', ['id' => $id])->with([
                'success' => 'Profil berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui profil:', ['message' => $e->getMessage()]);

            return back()->withInput()->with([
                'error' => 'Gagal memperbarui profil: '.$e->getMessage(),
            ]);
        }
    }

    public function photo(Request $request, $id)
    {
        // Validasi input
        $validator = \Validator::make(
            $request->all(),
            [
                'file' => ['required', 'string'], // Path file sementara dari FilePond
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $decryptId = Crypt::decrypt($id);

            $user = User::with('documents')->findOrFail($decryptId);
            if ($request->filled('file')) {
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
                $newFilePath = 'uploads/avatars/'.basename($temporaryPath);
                $temporaryFile = Storage::disk($disk)->get($temporaryPath);
                Storage::put($newFilePath, $temporaryFile);

                \Log::info("File berhasil dipindahkan ke: $newFilePath");

                $fileInfo = pathinfo($fullpath);

                // Perbarui data file dalam database, bukan menghapusnya
                $document = $user->documents->where('documentable_id', $user->id)->first();
                if ($document && Storage::exists($document->path)) {
                    Storage::delete($document->path);
                    // Update user dokumen lama
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
                        'type' => 'avatar',
                        'documentable_type' => User::class,
                        'documentable_id' => $user->id,
                        'mime_type' => mime_content_type($fullpath),
                        'size' => filesize($fullpath),
                    ]);
                    \Log::info('tidak ditemukan');
                }

                return redirect()->back()->with('success', 'Avatar berhasil diperbarui.');
            } else {
                \Log::warning('File tidak ditemukan dalam request.');
            }
        } catch (\Exception $e) {
            // Tangani error
            \Log::error('Error updating avatar', ['error' => $e->getMessage()]);

            return redirect()->back()->withInput()->with([
                'error' => 'Gagal memperbarui avatar: '.$e->getMessage(),
            ]);
        }
    }

    public function change(Request $request, $id): RedirectResponse
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
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $decrypt = Crypt::decrypt($id);
            $user = User::findOrFail($decrypt);

            if (! Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'error' => ['Kata sandi yang diberikan tidak cocok dengan catatan kami.'],
                ]);
            }

            // Logout from all other devices before changing the password
            Auth::logoutOtherDevices($request->current_password);

            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('user.detail', ['id' => $id])->with([
                'success' => 'Kata sandi berhasil diganti!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('profile.user', ['id' => $id])->with('error', 'Kata sandi gagal diganti!');
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
