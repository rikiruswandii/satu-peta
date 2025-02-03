//filepond
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

//openlayer
import 'ol/ol.css';
import { Map, View } from 'ol';
import { OSM, Vector as VectorSource } from 'ol/source';
import Overlay from 'ol/Overlay.js';
import { Tile as TileLayer, Vector as VectorLayer } from 'ol/layer';
import GeoJSON from 'ol/format/GeoJSON';
import { defaults as defaultControls } from 'ol/control';
import { defaults as defaultInteractions } from 'ol/interaction';

//openlayer configuration
function initMap(mapId, baseLayerType, geoJsonPath, controls, interactions) {
    const baseLayers = {
        osm: new TileLayer({ source: new OSM() }),
    };

    const map = new Map({
        target: mapId,
        layers: [baseLayers[baseLayerType] || baseLayers['osm']],
        view: new View({
            center: [0, 0],
            zoom: 2
        }),
        controls: defaultControls(controls),
        interactions: defaultInteractions(interactions)
    });

    const vectorSource = new VectorSource({
        url: geoJsonPath,
        format: new GeoJSON()
    });

    const vectorLayer = new VectorLayer({
        source: vectorSource
    });

    map.addLayer(vectorLayer);

    vectorSource.once('change', function () {
        if (vectorSource.getState() === 'ready') {
            map.getView().fit(vectorSource.getExtent(), {
                padding: [50, 50, 50, 50],
                maxZoom: 10
            });
        }
    });

    // Membuat overlay untuk popup
    const popupElement = document.getElementById('popup');
    const popupContent = document.getElementById('popup-content');
    const popupCloser = document.getElementById('popup-closer');

    const popupOverlay = new Overlay({
        element: popupElement,
        autoPan: true,
        autoPanMargin: 50
    });
    map.addOverlay(popupOverlay);

    // Fungsi untuk menampilkan popup saat hover
    // Fungsi untuk menampilkan popup saat hover
    const displayPopup = function (event) {
        const feature = map.forEachFeatureAtPixel(event.pixel, function (feature) {
            return feature;
        });

        if (feature) {
            // Ambil properties dari fitur dan buat tabel
            const properties = feature.getProperties();
            let tableHTML = '<table class="table table-sm table-bordered" style="width: 200px; font-size: 0.8rem;">' +
                '<thead><tr><th>Property</th><th>Value</th></tr></thead><tbody>';

            for (const key in properties) {
                if (properties.hasOwnProperty(key)) {
                    tableHTML += `<tr><td>${key}</td><td>${properties[key]}</td></tr>`;
                }
            }

            tableHTML += '</tbody></table>';

            popupContent.innerHTML = tableHTML;
            popupOverlay.setPosition(event.coordinate);
        } else {
            popupOverlay.setPosition(undefined);
        }
    };


    map.on('singleclick', displayPopup);

    // Fungsi untuk menutup popup
    popupCloser.onclick = function () {
        popupOverlay.setPosition(undefined);
    };

    return map;
}

window.initMap = initMap;


//filepond configuration
// Daftarkan plugin FilePond
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
);

// Pilih semua elemen input dengan class 'filepond'
const inputElements = document.querySelectorAll('input[type="file"].filepond');

// Loop melalui setiap elemen dan inisialisasi FilePond
inputElements.forEach(inputElement => {
    const existingFileUrl = inputElement.getAttribute('data-existing-file');
    const pond = FilePond.create(inputElement, {
        labelIdle: `Drag & Drop your file or <span class="filepond--label-action">Browse</span>`,
        allowMultiple: false, // Hanya satu file yang diunggah
        acceptedFileTypes: ['image/*', 'application/json'], // Hanya file gambar yang diterima
    });
    pond.setOptions({
        server: {
            url: '/filepond/api',
            process: {
                url: "/process",
                headers: (file) => {
                    // Send the original file name which will be used for chunked uploads
                    return {
                        "Upload-Name": file.name,
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    };
                },
            },
            revert: '/process',
            patch: "?patch=",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });

    // Jika ada file lama, tampilkan di FilePond
    if (existingFileUrl) {
        pond.files = [{
            source: existingFileUrl,
            options: {
                type: 'public',
            }
        }];
    }
});

$("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});

$("#error-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});

$("#info-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});
