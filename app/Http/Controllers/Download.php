<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class Download extends Controller
{
    public function download(Request $request, $id): Response
    {
        try {
            $id = Crypt::decrypt($id);
            // Cari dokumen berdasarkan ID dan tipe documentable
            $document = Document::findOrFail($id);

            // Ambil path file dari database
            $filePath = $document->path;
            $fileName = $document->name;
            $mimeType = $document->mime_type;

            // Cek apakah file ada di storage
            if (! Storage::disk('public')->exists($filePath)) {
                \Log::error("File tidak ditemukan di path: $filePath");

                return redirect()->back()->with('error', 'File tidak ditemukan di server.');
            }

            // Kembalikan response download file
            return response()->download(Storage::disk('public')->path($filePath), $fileName, [
                'Content-Type' => $mimeType,
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal mendownload file', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal mendownload file: '.$e->getMessage());
        }
    }
}
