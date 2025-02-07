import { Vector as VectorLayer } from 'ol/layer';
import { Vector as VectorSource } from 'ol/source';
import GeoJSON from 'ol/format/GeoJSON';

var $m = jQuery.noConflict();
$m(document).ready(function () {
    const sidebar = $m("#sidebar");
    const content = $m("#main-content");
    const toggleButtons = $m("#toggleSidebar, #anotherToggle");

    toggleButtons.on("click", function () {
        sidebar.toggleClass("closed");
        content.toggleClass("shifted");
        toggleButtons.toggleClass("closed");
    });

    let mapInstance = initMap('explorerMapId', 'osm', '', {
        scale: true,
        fullScreen: true,
        zoomSlider: true
    }, {
        dragPan: true,
        mouseWheelZoom: false
    })

    // Inisialisasi sortable menggunakan jQuery UI
    $m("#layerList").sortable({
        update: function () {
            updateLayerOrder();
        }
    });

    // Fungsi untuk memperbarui urutan layer berdasarkan list
    function updateLayerOrder() {
        let layerOrder = [];
        $m("#layerList li").each(function () {
            let layerId = $m(this).data("layer-id");
            let layer = mapInstance.getLayers().getArray().find(l => l.get("id") === layerId);
            if (layer) {
                layerOrder.push(layer);
            }
        });

        // Atur ulang urutan layer
        layerOrder.forEach((layer, index) => {
            layer.setZIndex(index);
        });
    }

    // Tambahkan layer ke daftar dan beri fungsi drag-and-drop
    function addLayerToList(layer, name) {
        let layerId = `layer-${Date.now()}`;
        layer.set("id", layerId);

        let listItem = `<li data-layer-id="${layerId}" class="list-group-item">${name}</li>`;
        $m("#layerList").append(listItem);
    }

    // Saat tombol diklik, tambahkan layer baru dan masukkan ke dalam list
    $m("#activateLayerButton").on("click", function () {
        let path = $m(this).data("geojson");
        let name = $m(this).data("name");
        console.log("GeoJSON Path:", path);

        if (!path) {
            alert("GeoJSON path tidak tersedia");
            return;
        }

        let vectorLayer = new VectorLayer({
            source: new VectorSource({
                url: path,
                format: new GeoJSON()
            }),
            style: function (feature) {
                return getStyle(feature);
            }
        });

        mapInstance.addLayer(vectorLayer);
        addLayerToList(vectorLayer, name);

        // Setelah layer ditambahkan, update urutan berdasarkan list
        updateLayerOrder();
    });


    // Fungsi untuk menampilkan modal
    $m(document).on('click', '[data-bs-target="#openModalDataset"]', function () {
        $m('.modal-header').css({
            cursor: 'move',
            color: 'white',
            backgroundColor: '#0fac81'
        });
        $m('.modal-title').attr('style', 'color: white !important');
        $m('.modal-content').css({
            'background': 'rgba(255, 255, 255, 0.5)',
            'backdrop-filter': 'blur(3px)'
        });

        if (!($m('.modal.in').length)) {
            $m('.modal-dialog').css({
                top: 0,
                left: 280
            });
        }
        $m('#openModalDataset').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        $m('.modal-backdrop').remove();

        $m('.modal-dialog').draggable({
            handle: ".modal-header"
        });
    });
});
