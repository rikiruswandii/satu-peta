<x-guest-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->

    <div class="content" id="main-content">
        <x-map-container mapId="explorerMapId" :height="'100vh'" />
    </div>

    @push('scripts')
        <script>
            var $m = jQuery.noConflict();
            $m(document).ready(function() {
                initMap('explorerMapId', 'osm', '', {
                    scale: true,
                    fullScreen: true,
                    zoomSlider: true
                }, {
                    dragPan: true,
                    mouseWheelZoom: false
                });
            });
        </script>
    @endpush
</x-guest-layout>
