<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeader
{
    private array $unwantedHeaders = ['X-Powered-By', 'server', 'Server'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! app()->environment('testing')) {
            // Pengaturan header keamanan
            $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

            // Memperbarui Content-Security-Policy (CSP)
            $csp = "default-src 'self'; script-src 'self' https://code.jquery.com https://cdn.jsdelivr.net https://cdn.datatables.net http://127.0.0.1:5173 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://cdn.datatables.net https://fonts.googleapis.com https://cdn.jsdelivr.net http://127.0.0.1:5173; img-src 'self' data: blob: https://photon.komoot.io https://tile.openstreetmap.org https://a.tile.openstreetmap.org https://b.tile.openstreetmap.org https://c.tile.openstreetmap.org https://cartodb-basemaps-a.global.ssl.fastly.net https://stamen-tiles.a.ssl.fastly.net https://server.arcgisonline.com https://gibs.earthdata.nasa.gov https://a.tile.opentopomap.org https://tiles.maps.eox.at https://gibs.earthdata.nasa.gov https://server.arcgisonline.com; font-src 'self' data: https://fonts.gstatic.com https://fonts.googleapis.com; connect-src 'self' ws://127.0.0.1:5173 http://127.0.0.1:5173 https://photon.komoot.io https://fonts.googleapis.com https://fonts.gstatic.com https://tile.openstreetmap.org https://a.tile.openstreetmap.org https://b.tile.openstreetmap.org https://c.tile.openstreetmap.org https://cartodb-basemaps-a.global.ssl.fastly.net https://stamen-tiles.a.ssl.fastly.net https://server.arcgisonline.com https://gibs.earthdata.nasa.gov https://a.tile.opentopomap.org https://tiles.maps.eox.at https://gibs.earthdata.nasa.gov https://server.arcgisonline.com; worker-src 'self' blob:;";

            $response->headers->set('Content-Security-Policy', $csp);

            // Header lainnya
            $response->headers->set('Expect-CT', 'enforce, max-age=30');
            $response->headers->set('Permissions-Policy', 'autoplay=(self), encrypted-media=(self), fullscreen=(), geolocation=(self), sync-xhr=(self)');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-Token');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            // Menghapus header yang tidak diinginkan
            $this->removeUnwantedHeaders($response);
        }

        return $response;
    }

    /**
     * Menghapus header yang tidak diinginkan dari respons.
     */
    private function removeUnwantedHeaders(Response $response): void
    {
        foreach ($this->unwantedHeaders as $header) {
            $response->headers->remove($header);
        }
    }
}
