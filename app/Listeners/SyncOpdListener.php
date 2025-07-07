<?php

namespace App\Listeners;

use App\Events\OpdSyncRequested;
use App\Models\RegionalAgency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncOpdListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OpdSyncRequested $event): void
    {
        // Ambil URL API dari environment variable
        $apiUrl = env('OPD_API');
        $key = env('API_KEY');

        if (! $apiUrl) {
            Log::error('OPD_API environment variable is not set.');

            return;
        }

        // Tambahkan log untuk memeriksa nilai dari $apiUrl
        Log::info('OPD_API URL:', ['url' => $apiUrl]);

        // Lakukan permintaan HTTP GET ke URL API
        try {
            $response = Http::get($apiUrl, ['key' => $key]);

            // Tambahkan log untuk memeriksa response body
            Log::info('API Response:', ['response' => $response->body()]);

            if ($response->successful()) {
                $responseData = $response->json(); // Dapatkan data dari respons API

                // Periksa apakah 'data' ada di respons langsung
                if (isset($responseData['data'])) {
                    $data = $responseData['data'];

                    // Tambahkan log untuk memeriksa isi $data
                    Log::info('Data JSON:', ['data' => $data]);

                    // Langsung gunakan array hasil decode dari response->json()
                    foreach ($data as $item) {
                        Log::info('Processing item:', ['id' => $item['id'], 'name' => $item['name']]);
                        try {
                            $data = RegionalAgency::updateOrCreate(
                                ['id' => $item['id']], // Kondisi pencarian
                                [   // Data yang akan diupdate atau dibuat
                                    'user_id' => 2,
                                    'name' => $item['name'],
                                    'slug' => Str::slug($item['name']),
                                ]
                            );

                            if ($data) {
                                Log::info('Sinkronisasi berhasil untuk ID: '.$item['id']);
                            } else {
                                Log::warning('Data tidak berhasil disinkronkan untuk ID: '.$item['id']);
                            }
                        } catch (\Exception $e) {
                            Log::error('Error updating or creating district ID '.$item['id'].': '.$e->getMessage());
                        }
                        Log::info('Item processed:', ['id' => $item['id']]);
                    }
                } else {
                    Log::error('Unexpected data structure in API response', ['data' => $responseData]);
                }
            } else {
                Log::error('Failed to fetch data from API', [
                    'url' => $apiUrl,
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to make API request: '.$e->getMessage());
        }
    }
}
