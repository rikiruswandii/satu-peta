//filepond
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

//openlayer
import 'ol/ol.css';
import 'ol-layerswitcher/dist/ol-layerswitcher.css';
import { Map, View } from 'ol';
import { OSM, XYZ, Vector as VectorSource } from 'ol/source';
import Overlay from 'ol/Overlay.js';
import { Tile as TileLayer, Vector as VectorLayer } from 'ol/layer';
import GeoJSON from 'ol/format/GeoJSON';
import { ZoomSlider, FullScreen, ScaleLine, defaults as defaultControls, Control} from 'ol/control';
import { DoubleClickZoom, MouseWheelZoom, DragPan, defaults as defaultInteractions } from 'ol/interaction';
import { Style, Fill, Stroke, Circle } from 'ol/style';
import { fromLonLat } from 'ol/proj';
import LayerSwitcher from 'ol-layerswitcher';
import Draw from 'ol/interaction/Draw.js';

//layer styles
function getStyle(feature) {
    const geometryType = feature.getGeometry().getType();

    let style;
    switch (geometryType) {
        case 'Point':
            style = new Style({
                image: new Circle({
                    radius: 5,
                    fill: new Fill({ color: '#73EC8B' }),
                    stroke: new Stroke({ color: 'white', width: 1 })
                })
            });
            break;
        case 'LineString':
            style = new Style({
                stroke: new Stroke({
                    color: '#73EC8B',
                    width: 3
                })
            });
            break;
        case 'Polygon':
            style = new Style({
                fill: new Fill({
                    color: '#73EC8B'
                }),
                stroke: new Stroke({
                    color: 'white',
                    width: 1
                })
            });
            break;
        default:
            style = new Style({
                stroke: new Stroke({
                    color: '#73EC8B',
                    width: 1
                })
            });
    }

    return style;
}

window.getStyle = getStyle;

// Daftar basemap dengan URL thumbnail sesuai
const baseMaps = {
    'OpenStreetMap': new TileLayer({
        source: new OSM(),
        visible: true,
        title: 'OpenStreetMap',
        thumbnail: 'https://a.tile.openstreetmap.org/2/2/2.png'
    }),
    'Carto Light': new TileLayer({
        source: new XYZ({
            url: 'https://cartodb-basemaps-a.global.ssl.fastly.net/light_all/{z}/{x}/{y}{scale}.png'
        }),
        visible: false,
        title: 'Carto Light',
        thumbnail: 'https://cartodb-basemaps-a.global.ssl.fastly.net/light_all/2/2/2.png'
    })
};

// Fungsi Membuat Custom Control untuk Basemap
class BasemapControl extends Control {
    constructor(opt_options) {
        const options = opt_options || {};
        const element = document.createElement('div');
        element.className = 'ol-basemap-control ol-unselectable ol-control';

        // Elemen UI
        element.innerHTML = `
            <div class="card basemap-container hidden">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-map"></i> Pilih Basemap</span>
                    <button class="btn btn-sm btn-light toggle-basemap"><i class="bi bi-eye"></i></button>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        ${Object.keys(baseMaps).map(layer => `
                            <div class="col-4">
                                <div class="basemap-item ${baseMaps[layer].getVisible() ? 'active' : ''}" data-layer="${layer}">
                                    <img src="${baseMaps[layer].get('thumbnail')}" alt="${layer}" data-toggle="tooltip" data-placement="bottom" title="${layer}">
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        // Tombol Floating untuk Menampilkan Basemap Control
        const toggleButton = document.createElement('button');
        toggleButton.className = 'btn btn-success btn-sm basemap-toggle-btn';
        toggleButton.innerHTML = '<i class="bi bi-layers"></i>';
        document.body.appendChild(toggleButton);

        super({
            element: element,
            target: options.target
        });

        // Event Klik Thumbnail untuk Mengubah Basemap
        element.querySelectorAll('.basemap-item').forEach(item => {
            item.addEventListener('click', function () {
                const selectedLayer = this.getAttribute('data-layer');

                Object.values(baseMaps).forEach(layer => {
                    layer.setVisible(layer.get('title') === selectedLayer);
                });

                // Highlight basemap aktif
                element.querySelectorAll('.basemap-item').forEach(el => el.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Event Toggle Basemap (Sembunyikan & Munculkan)
        element.querySelector('.toggle-basemap').addEventListener('click', function () {
            const container = element.querySelector('.basemap-container');
            container.classList.toggle('hidden');

            if (container.classList.contains('hidden')) {
                this.innerHTML = '<i class="bi bi-eye"></i>';
                toggleButton.classList.remove('hidden'); // Munculkan tombol floating dengan transisi
            } else {
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                toggleButton.classList.add('hidden'); // Sembunyikan tombol floating dengan transisi
            }
        });

        // Event untuk Memunculkan Kembali Basemap Control
        toggleButton.addEventListener('click', function () {
            const container = element.querySelector('.basemap-container');
            container.classList.remove('hidden');
            element.querySelector('.toggle-basemap').innerHTML = '<i class="bi bi-eye-slash"></i>';
            this.classList.add('hidden'); // Sembunyikan tombol floating dengan transisi
        });

    }
}


//openlayer configuration
function initMap(mapId, baseLayerType = 'osm', geoJsonPath, controlOptions = {}, interactionOptions = {}) {

    const map = new Map({
        target: mapId,
        layers: Object.values(baseMaps),
        view: new View({
            center: fromLonLat([107.5244, -6.5799]),
            zoom: 10
        }),
        controls: createControls(controlOptions),
        interactions: createInteractions(interactionOptions)
    });

    const vectorSource = new VectorSource({
        url: geoJsonPath,
        format: new GeoJSON()
    });

    const vectorLayer = new VectorLayer({
        source: vectorSource,
        style: function (feature) {
            return getStyle(feature);
        }
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

    map.on('pointermove', function (event) {
        let hit = map.hasFeatureAtPixel(event.pixel);

        if (hit) {
            map.getTargetElement().style.cursor = 'pointer';
        } else {
            map.getTargetElement().style.cursor = '';
        }
    });

    map.on('singleclick', displayPopup);

    // Fungsi untuk menutup popup
    popupCloser.onclick = function () {
        popupOverlay.setPosition(undefined);
    };

    return map;
}

function createControls(options) {
    const availableControls = {
        scale: new ScaleLine({ units: 'imperial' }),
        fullScreen: new FullScreen(),
        zoomSlider: new ZoomSlider(),
        basemap: new BasemapControl()
    };

    return defaultControls().extend(Object.keys(options).map(key => options[key] ? availableControls[key] : null).filter(Boolean));
}

function createInteractions(options) {
    const availableInteractions = {
        dragPan: new DragPan(),
        doubleClickZoom: new DoubleClickZoom(),
        mouseWheelZoom: new MouseWheelZoom(),
    };

    return defaultInteractions().extend(Object.keys(options).map(key => options[key] ? availableInteractions[key] : null).filter(Boolean));
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

var $jq = jQuery.noConflict();
$jq(document).ready(function () {
    if ($jq("#success-alert").length) {
        $jq("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
            $(this).slideUp(500);
        });
    }

    if ($jq("#error-alert").length) {
        $jq("#error-alert").fadeTo(2000, 500).slideUp(500, function () {
            $(this).slideUp(500);
        });
    }

    if ($jq("#info-alert").length) {
        $jq("#info-alert").fadeTo(2000, 500).slideUp(500, function () {
            $(this).slideUp(500);
        });
    }
});

