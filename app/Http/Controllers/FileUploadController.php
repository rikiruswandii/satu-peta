<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use illuminate\

final class FileUploadController extends Controller
{
    public function process(Request $request): string
    {
        // Ambil semua file dari permintaan
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'Tidak ada file yang diunggah.');
        }

        if (count($files) > 1) {
            abort(422, 'Hanya satu file yang dapat diunggah dalam satu waktu.');
        }

        // Ambil kunci pertama dari input file
        $requestKey = array_key_first($files);

        // Ambil file (mendukung array file jika multiple upload)
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        // Validasi file
        $file->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan file ke lokasi sementara
        $temporaryPath = $file->store(
            path: 'tmp/' . now()->timestamp . '-' . Str::random(20),
            disk: 'public'
        );

        // Logika tambahan untuk memindahkan file ke lokasi permanen
        $permanentPath = 'uploads/avatars/' . $file->hashName();

        // Pindahkan file
        Storage::disk('public')->move($temporaryPath, $permanentPath);

        // Simpan file ke tabel documents
        $user = auth()->user(); // Ganti dengan model yang sesuai
        $user->documents()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $permanentPath,
            'type' => 'avatar', // Jenis file (avatar, dokumen, dll)
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        // Return response (bisa diatur sesuai kebutuhan FilePond)
        return response()->json([
            'message' => 'File berhasil diunggah.',
            'path' => $permanentPath,
        ]);
    }
}
