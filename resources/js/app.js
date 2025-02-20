//filepond
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

//openlayer
import 'ol/ol.css';
import 'ol-layerswitcher/dist/ol-layerswitcher.css';
import { Map, View } from 'ol';
import { OSM, XYZ, Vector as VectorSource } from 'ol/source';
import Overlay from 'ol/Overlay.js';
import { Tile as TileLayer, Vector as VectorLayer } from 'ol/layer';
import GeoJSON from 'ol/format/GeoJSON';
import { ZoomSlider, FullScreen, ScaleLine, defaults as defaultControls, Control } from 'ol/control';
import { Modify, DoubleClickZoom, MouseWheelZoom, DragPan, defaults as defaultInteractions } from 'ol/interaction';
import { Style, Fill, Stroke, Circle } from 'ol/style';
import { fromLonLat } from 'ol/proj';
import Draw, { createBox } from 'ol/interaction/Draw';

//to png
import domtoimage from 'dom-to-image-more';

//to pdf
import jsPDF from 'jspdf';

class ExportControl extends Control {
    constructor(opt_options) {
        const options = opt_options || {};
        if (!options.export) {
            return;
        }

        const buttonToggle = document.createElement('button');
        buttonToggle.innerHTML = '<i class="bi bi-printer-fill"></i>';
        buttonToggle.title = 'Print';
        buttonToggle.setAttribute("data-bs-toggle", "tooltip");
        buttonToggle.setAttribute("data-bs-placement", "left");
        var tooltip = new bootstrap.Tooltip(buttonToggle);
        buttonToggle.className = 'export-toggle';
        buttonToggle.addEventListener('click', () => this.toggleModal());

        const element = document.createElement('div');
        element.className = 'ol-unselectable ol-control export-control';
        element.appendChild(buttonToggle);

        super({
            element: element,
            target: options.target,
        });

        this.initModalEvents();
    }

    async toggleModal() {
        const modal = new bootstrap.Modal(document.getElementById('exportModal'));
        modal.show();
        await this.updatePreviewMap();
    }

    async updatePreviewMap() {
        try {
            const mapElement = this.getMap().getTargetElement();

            // Sembunyikan semua control OpenLayers termasuk ol-scale-line
            const controls = mapElement.querySelectorAll('.ol-control, .ol-scale-line');
            controls.forEach(control => control.style.display = 'none');

            const dataUrl = await domtoimage.toPng(mapElement);

            // Kembalikan ol-control setelah tangkapan layar
            controls.forEach(control => control.style.display = '');

            const previewMap = document.getElementById('preview-map');
            previewMap.src = dataUrl;
            previewMap.style.display = 'block';
        } catch (error) {
            console.error('Error generating preview map:', error);
        }
    }

    async exportMap(format) {
        try {
            const mapElement = this.getMap().getTargetElement();
            const title = document.getElementById('map-title').value;

            // Sembunyikan semua kontrol OpenLayers sebelum mengambil screenshot
            const controls = mapElement.querySelectorAll('.ol-control, .ol-scale-line');
            controls.forEach(control => control.style.display = 'none');

            // Ambil tangkapan layar peta
            const dataUrl = await domtoimage.toPng(mapElement);

            // Kembalikan tampilan kontrol setelah screenshot selesai
            controls.forEach(control => control.style.display = '');

            document.getElementById('preview-map').src = dataUrl;
            document.getElementById('preview-map').style.display = 'block';

            if (format === 'png') {
                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = `${title}.png`;
                link.click();
            } else if (format === 'pdf') {
                const pdf = new jsPDF();
                pdf.text(title, 10, 10);
                pdf.addImage(dataUrl, 'PNG', 10, 20, 180, 120);
                pdf.save(`${title}.pdf`);
            }
        } catch (error) {
            console.error('Error exporting map:', error);
        }
    }


    initModalEvents() {
        document.getElementById('map-title').addEventListener('input', (e) => {
            document.getElementById('preview-title').innerText = e.target.value;
        });

        document.getElementById('export-png').addEventListener('click', () => this.exportMap('png'));
        document.getElementById('export-pdf').addEventListener('click', () => this.exportMap('pdf'));
    }
}

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

//Daftar basemap dengan URL thumbnail sesuai
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
    }),
    'Carto Dark': new TileLayer({
        source: new XYZ({
            url: 'https://cartodb-basemaps-a.global.ssl.fastly.net/dark_all/{z}/{x}/{y}{scale}.png'
        }),
        visible: false,
        title: 'Carto Dark',
        thumbnail: 'https://cartodb-basemaps-a.global.ssl.fastly.net/dark_all/2/2/2.png'
    }),
    'Esri World Street': new TileLayer({
        source: new XYZ({
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}'
        }),
        visible: false,
        title: 'Esri World Street',
        thumbnail: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/2/2/2'
    }),
    'OpenTopoMap': new TileLayer({
        source: new XYZ({
            url: 'https://a.tile.opentopomap.org/{z}/{x}/{y}.png'
        }),
        visible: false,
        title: 'OpenTopoMap',
        thumbnail: 'https://a.tile.opentopomap.org/2/2/2.png'
    }),
    'Esri Satellite': new TileLayer({
        source: new XYZ({
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        }),
        visible: false,
        title: 'Esri Satellite',
        thumbnail: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/2/2/2'
    }),
    'Sentinel-2 Cloudless': new TileLayer({
        source: new XYZ({
            url: 'https://tiles.maps.eox.at/wmts/1.0.0/s2cloudless-2021_3857/default/g/{z}/{y}/{x}.jpg'
        }),
        visible: false,
        title: 'Sentinel-2 Cloudless',
        thumbnail: 'https://tiles.maps.eox.at/wmts/1.0.0/s2cloudless-2021_3857/default/g/2/2/2.jpg'
    })
};



// Fungsi Membuat Custom Control untuk Basemap
class BasemapControl extends Control {
    constructor(opt_options) {
        const options = opt_options || {};

        // Panggil super() sebelum mengakses 'this'
        const element = document.createElement('div');
        super({ element });

        if (!options.basemap) {
            return; // Jika basemap dinonaktifkan, hentikan eksekusi
        }

        element.className = 'ol-basemap-control ol-unselectable ol-control';

        // Elemen UI
        element.innerHTML = `
            <div class="card basemap-container hidden">
                <div class="card-header bg-success-new text-warning d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-map"></i> Pilih Basemap</span>
                    <button class="btn btn-sm btn-light toggle-basemap"><i class="bi bi-eye"></i></button>
                </div>
                <div class="card-body-basemap">
                    <div class="row g-2">
                        ${Object.keys(baseMaps).map(layer => `
                            <div class="col-4">
                                <div class="basemap-item ${baseMaps[layer].getVisible() ? 'active' : ''}" data-layer="${layer}">
                                    <img src="${baseMaps[layer].get('thumbnail')}" alt="${layer}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${layer}">
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        // Tambahkan tombol floating toggle
        const toggleButton = document.createElement('button');
        toggleButton.title = 'Basemap';
        toggleButton.setAttribute("data-bs-toggle", "tooltip");
        toggleButton.setAttribute("data-bs-placement", "left");
        var tooltip = new bootstrap.Tooltip(toggleButton);
        toggleButton.className = 'basemap-toggle-btn';
        toggleButton.innerHTML = '<i class="bi bi-layers"></i>';
        document.body.appendChild(toggleButton);

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
                toggleButton.classList.remove('hidden'); // Munculkan tombol floating
            } else {
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                toggleButton.classList.add('hidden'); // Sembunyikan tombol floating
            }
        });

        // Event untuk Memunculkan Kembali Basemap Control
        toggleButton.addEventListener('click', function () {
            const container = element.querySelector('.basemap-container');
            container.classList.remove('hidden');
            element.querySelector('.toggle-basemap').innerHTML = '<i class="bi bi-eye-slash"></i>';
            this.classList.add('hidden'); // Sembunyikan tombol floating
        });

    }
}

class DrawControl extends Control {
    constructor(options = {}) {
        const element = document.createElement('div');
        element.className = 'ol-draw-control ol-unselectable ol-control';
        element.style.position = 'absolute';
        element.style.top = '50px';
        element.style.right = '8px';
        element.style.borderRadius = '3px';
        element.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
        element.style.display = 'flex';
        element.style.flexDirection = 'column';
        element.style.gap = '1px';

        super({ element });

        this.map = options.map;
        this.drawLayer = new VectorLayer({
            source: new VectorSource(),
            style: function (feature) {
                return getStyle(feature);
            }
        });

        this.map.addLayer(this.drawLayer);
        this.activeDraw = null;
        this.modifyInteraction = new Modify({ source: this.drawLayer.getSource() });

        const buttons = [
            { type: 'Point', icon: 'bi bi-geo-alt' }, // Marker
            { type: 'LineString', icon: 'bi bi-slash-lg' }, // Polyline
            { type: 'Polygon', icon: 'bi bi-bounding-box' }, // Polygon
            { type: 'Box', icon: 'bi bi-aspect-ratio' }, // Rectangle
            { type: 'Circle', icon: 'bi bi-circle' } // Circle
        ];

        buttons.forEach(({ type, icon }) => {
            const button = document.createElement('button');
            button.innerHTML = `<i class="${icon}"></i>`;
            button.title = `Draw ${type}`;
            button.setAttribute("data-bs-toggle", "tooltip");
            button.setAttribute("data-bs-placement", "left");
            new bootstrap.Tooltip(button);
            button.className = '';
            button.onclick = () => this.activateDraw(type);
            element.appendChild(button);
        });

        // Tombol hapus gambar
        const clearButton = document.createElement('button');
        clearButton.innerHTML = '<i class="bi bi-trash"></i>';
        clearButton.title = 'Clear Drawings';
        clearButton.setAttribute("data-bs-toggle", "tooltip");
        clearButton.setAttribute("data-bs-placement", "left");
        new bootstrap.Tooltip(clearButton);
        clearButton.className = 'btn btn-danger btn-sm';
        clearButton.onclick = () => this.clearDrawings();
        element.appendChild(clearButton);

        // Tombol edit
        this.editButton = document.createElement('button');
        this.editButton.innerHTML = '<i class="bi bi-pencil"></i>';
        this.editButton.title = 'Edit Drawings';
        this.editButton.setAttribute("data-bs-toggle", "tooltip");
        this.editButton.setAttribute("data-bs-placement", "left");
        new bootstrap.Tooltip(this.editButton);
        this.editButton.className = 'btn btn-primary btn-sm';
        this.editButton.onclick = () => this.toggleEditMode();
        element.appendChild(this.editButton);

        // Tombol untuk mengakhiri draw mode
        this.stopDrawButton = document.createElement('button');
        this.stopDrawButton.innerHTML = '<i class="bi bi-x-circle"></i>';
        this.stopDrawButton.title = 'Stop Drawing';
        this.stopDrawButton.setAttribute("data-bs-toggle", "tooltip");
        this.stopDrawButton.setAttribute("data-bs-placement", "left");
        new bootstrap.Tooltip(this.stopDrawButton);
        this.stopDrawButton.className = 'btn btn-warning btn-sm';
        this.stopDrawButton.style.display = 'none';
        this.stopDrawButton.onclick = () => this.deactivateDraw();
        element.appendChild(this.stopDrawButton);
    }

    activateDraw(type) {
        if (this.activeDraw) {
            this.map.removeInteraction(this.activeDraw);
            this.activeDraw = null;
        }

        this.map.removeInteraction(this.modifyInteraction);

        let geometryFunction = null;
        let drawType = type;

        if (type === 'Box') {
            drawType = 'Circle';
            geometryFunction = createBox();
        }

        this.activeDraw = new Draw({
            source: this.drawLayer.getSource(),
            type: drawType,
            geometryFunction: geometryFunction
        });

        this.map.addInteraction(this.activeDraw);
        this.stopDrawButton.style.display = 'inline-block';
    }

    deactivateDraw() {
        if (this.activeDraw) {
            this.map.removeInteraction(this.activeDraw);
            this.activeDraw = null;
        }
        this.stopDrawButton.style.display = 'none';
    }

    clearDrawings() {
        this.drawLayer.getSource().clear();
        this.deactivateDraw();
    }

    toggleEditMode() {
        if (this.map.getInteractions().getArray().includes(this.modifyInteraction)) {
            this.map.removeInteraction(this.modifyInteraction);
        } else {
            this.map.addInteraction(this.modifyInteraction);
        }
    }
}

//openlayer configuration
function initMap(mapId, geoJsonPath, controlOptions = {}, interactionOptions = {}) {

    const map = new Map({
        target: mapId,
        layers: Object.values(baseMaps),
        view: new View({
            center: fromLonLat([107.5244, -6.5799]),
            zoom: 10
        }),
        interactions: createInteractions(interactionOptions) // Tambahkan interaksi dulu
    });

    // Setelah map dibuat, baru tambahkan kontrol
    const controls = createControls(controlOptions, map);
    controls.forEach(control => map.addControl(control));

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
    const popupElement = document.createElement('div');
    popupElement.className = 'ol-popup';
    const popupContent = document.createElement('div');
    popupContent.id = `popup-content-${mapId}`;
    const popupCloser = document.createElement('a');
    popupCloser.href = '#';
    popupCloser.className = 'ol-popup-closer';

    popupElement.appendChild(popupCloser);
    popupElement.appendChild(popupContent);

    const popupOverlay = new Overlay({
        element: popupElement,
        autoPan: true,
        autoPanMargin: 50
    });
    map.addOverlay(popupOverlay);

    // Fungsi untuk menampilkan popup saat hover
    const displayPopup = function (event) {
        const feature = map.forEachFeatureAtPixel(event.pixel, function (feature) {
            return feature;
        });

        if (feature) {
            const properties = feature.getProperties();
            let tableHTML = '<table class="table table-sm table-bordered" style="width: 200px; font-size: 0.8rem;">' +
                '<thead><tr><th>Property</th><th>Value</th></tr></thead><tbody>';

            for (const key in properties) {
                if (properties.hasOwnProperty(key) && key !== 'geometry') {
                    tableHTML += `<tr><td>${key}</td><td>${properties[key]}</td></tr>`;
                }
            }

            tableHTML += '</tbody></table>';

            popupContent.innerHTML = tableHTML;
            popupElement.style.display = 'block'; // Tampilkan popup
            popupOverlay.setPosition(event.coordinate);
        } else {
            popupOverlay.setPosition(undefined);
            popupElement.style.display = 'none'; // Sembunyikan popup
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

    map.on('singleclick', function (event) {
        const feature = map.forEachFeatureAtPixel(event.pixel, function (feature) {
            return feature;
        });

        if (feature) {
            displayPopup(event);
        } else {
            popupOverlay.setPosition(undefined);
            popupElement.style.display = 'none';
        }
    });



    // Fungsi untuk menutup popup
    popupCloser.onclick = function () {
        popupOverlay.setPosition(undefined);
        return false;
    };

    return map;
}

// Fungsi untuk membuat kontrol dengan opsi yang dapat diaktifkan atau dinonaktifkan
function createControls(options = {}, map) {
    const availableControls = {
        scale: () => new ScaleLine({ units: 'imperial' }),
        fullScreen: () => new FullScreen(),
        zoomSlider: () => new ZoomSlider(),
        basemap: () => new BasemapControl({ basemap: true }),
        export: () => new ExportControl({ export: true }),
        draw: () => map ? new DrawControl({ map }) : null
    };

    return defaultControls().extend(
        Object.keys(availableControls)
            .map(key => options[key] ? availableControls[key]() : null)
            .filter(Boolean)
    );
}

function createInteractions(options = {}) {
    const availableInteractions = {
        dragPan: () => new DragPan(),
        doubleClickZoom: () => new DoubleClickZoom(),
        mouseWheelZoom: () => new MouseWheelZoom(),
    };

    return defaultInteractions().extend(
        Object.keys(availableInteractions)
            .map(key => options[key] ? availableInteractions[key]() : null)
            .filter(Boolean)
    );
}

window.initMap = initMap;


//filepond configuration
// Daftarkan plugin FilePond
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
    FilePondPluginFileValidateSize,
);

// Pilih semua elemen input dengan class 'filepond'
const inputElements = document.querySelectorAll('input[type="file"].filepond');

// Loop melalui setiap elemen dan inisialisasi FilePond
inputElements.forEach(inputElement => {
    const existingFileUrl = inputElement.getAttribute('data-existing-file');
    const pond = FilePond.create(inputElement, {
        labelIdle: `Drag & Drop your file or <span class="filepond--label-action">Browse</span>`,
        allowMultiple: false, // Hanya satu file yang diunggah
        acceptedFileTypes: [
            'image/*',
            'application/json'
        ],
        maxFileSize: '10MB',
        fileValidateTypeDetectType: (source, type) => {
            return new Promise((resolve, reject) => {
                // Mendapatkan file dari sumber input
                const reader = new FileReader();

                reader.onloadend = () => {
                    const fileContent = reader.result;

                    // Menampilkan tipe awal yang diterima oleh FilePond
                    console.log("Tipe MIME yang diterima oleh FilePond: ", type);

                    // Periksa apakah file adalah geojson berdasarkan kontennya
                    if (typeof fileContent === 'string' && fileContent.includes('"type": "FeatureCollection"')) {
                        console.log("File ini terdeteksi sebagai GeoJSON.");
                        resolve('application/geo+json'); // Deteksi sebagai geojson
                    } else {
                        console.log("File ini tidak terdeteksi sebagai GeoJSON.");
                        resolve(type); // Kembalikan tipe yang ada jika tidak terdeteksi sebagai geojson
                    }
                };

                reader.onerror = () => {
                    console.error("Terjadi kesalahan dalam membaca file.");
                    reject('File error');
                };

                // Membaca file sebagai teks
                reader.readAsText(source);
            });
        },
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

    // Tunggu hingga OpenLayers selesai memuat elemen
    setTimeout(function () {
        var elements = {
            ".ol-zoom-in": "Perbesar Peta",
            ".ol-zoom-out": "Perkecil Peta",
            ".ol-zoomslider": "Gunakan slider untuk zoom",
            ".ol-attributes": "Atribut Peta",
            ".ol-full-screen": "Tampilan Layar Penuh"
        };

        // Tambahkan atribut tooltip ke setiap elemen
        $jq.each(elements, function (selector, titleText) {
            var element = $jq(selector);
            if (element.length) {
                element.attr("data-bs-toggle", "tooltip")
                    .attr("data-bs-placement", "left")
                    .attr("title", titleText);
            }
        });

        // Inisialisasi semua tooltip
        $jq('[data-bs-toggle="tooltip"]').tooltip();
    }, 500);
    
    $jq('.btn-tooltip').on('click', function () {
        $(this).tooltip('hide'); // Sembunyikan tooltip setelah klik
    });
});

