<div id="{{ $mapId }}" style="width: 100%; height: 400px;"></div>
<div id="popup" class="ol-popup">
    <a href="#" id="popup-closer" class="ol-popup-closer"></a>
    <div id="popup-content"></div>
</div>

{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initMap('{{ $mapId }}', '{{ $baseLayerType }}', '{{ $geoJsonPath }}', {{ json_encode($controls) }}, {{ json_encode($interactions) }});
        });
    </script>
@endpush --}}
