import { Vector as VectorLayer } from 'ol/layer';
import { Vector as VectorSource } from 'ol/source';
import GeoJSON from 'ol/format/GeoJSON';
import { fromLonLat } from 'ol/proj';
import { Point } from 'ol/geom';
import { Feature } from 'ol';


var $m = jQuery.noConflict();
$m(document).ready(function () {
    const sidebar = $m("#sidebar");
    const content = $m("#main-content");
    const toggleBtn = $m(".toggle-btn, #anotherToggle"); // Ambil elemen pembungkus toggle
    const toggleIcon = $m("#toggleSidebar"); // Ambil ikon di dalam toggle
    const result = $m("#result-area"); // Ambil ikon di dalam toggle

    // Mencegah klik tombol tertentu agar tidak memicu toggle
    $m("#search-btn, #close-result-btn").on("click", function (e) {
        e.stopPropagation(); // Mencegah event naik ke parent
    });

    // Event listener utama untuk toggle sidebar
    toggleBtn.on("click", function (e) {
        const target = $m(e.target);

        // Pastikan hanya ikon <i> yang dapat memicu toggle
        if (!target.is("i")) return;

        sidebar.toggleClass("closed");
        content.toggleClass("shifted");
        toggleBtn.toggleClass("closed");
        result.toggleClass("closed");
        toggleIcon.toggleClass("bi-list bi-arrow-bar-right");
    });

    let map = initMap('explorerMapId', '', {
        scale: true,
        fullScreen: true,
        zoomSlider: true,
        basemap: true,
        draw: true
    }, {
        dragPan: true,
        mouseWheelZoom: false
    })

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
            let layer = map.getLayers().getArray().find(l => l.get("id") === layerId);
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

        let listItem = `<li data-layer-id="${layerId}" class="list-group-item" style="cursor:pointer;">${name}</li>`;
        $m("#layerList").append(listItem);
    }

    // Saat tombol diklik, tambahkan layer baru dan masukkan ke dalam list
    $m(".list-group-item a").on("click", function () {
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

        map.addLayer(vectorLayer);
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

    // Sumber data untuk marker
    const vectorSource = new VectorSource();

    // Layer untuk marker dengan style khusus
    const vectorLayer = new VectorLayer({
        source: vectorSource,
        style: function (feature) {
            return getStyle(feature);
        }
    });

    // Tambahkan layer marker ke dalam peta
    map.addLayer(vectorLayer);

    // Fungsi untuk memperbarui marker menggunakan getStyle
    function updateMarker(lon, lat) {
        console.log("Menampilkan marker di:", lon, lat);

        // Hapus semua marker lama
        vectorSource.clear();

        // Tambahkan marker baru
        const markerFeature = new Feature({
            geometry: new Point(fromLonLat([lon, lat])),
        });

        vectorSource.addFeature(markerFeature);
    }

    function searchLocation() {
        const query = $m("#search-from-aside").val().trim();
        if (!query) {
            console.warn("Query kosong, pencarian dibatalkan.");
            return;
        }

        console.log("Mencari lokasi:", query);

        fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                const results = data.features || [];
                const resultList = $m("#search-results");
                resultList.html(""); // Kosongkan hasil sebelumnya

                if (!Array.isArray(results) || results.length === 0) {
                    resultList.html("<li class='list-group-item text-muted'>Tidak ada hasil ditemukan.</li>");
                    return;
                }

                $m("#result-area").show(); // Tampilkan hasil pencarian
                $m("#result-area").css({
                    width: '360px',
                    maxWidth: '360px',
                });
                $m("#close-result-btn").removeClass("d-none"); // Munculkan tombol tutup
                $m(".toggle-btn").css({
                    width: '300px',
                    maxWidth: '300px',
                });

                results.forEach((place) => {
                    const name = place.properties?.name || "Tidak diketahui";
                    const lon = place.geometry?.coordinates?.[0];
                    const lat = place.geometry?.coordinates?.[1];

                    if (lon === undefined || lat === undefined) return;

                    const listItem = $m("<li></li>")
                        .addClass("list-group-item")
                        .text(name)
                        .css({ cursor: "pointer" })
                        .on("click", function () {
                            map.getView().animate({
                                center: fromLonLat([lon, lat]),
                                zoom: 14,
                                duration: 1000
                            });
                            updateMarker(lon, lat);
                        });

                    resultList.append(listItem);
                });
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    $m("#search-from-aside").on("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            searchLocation();
        }
    });

    $m("#search-btn").on("click", searchLocation);
    // Event listener untuk tombol tutup
    $m("#close-result-btn").on("click", function () {
        $m("#result-area").css({
            width: '330px',
            maxWidth: '330px',
        });
        $m(".toggle-btn").css({
            width: '270px',
            maxWidth: '270px',
        });
        $m("#result-area").hide();
        $m("#close-result-btn").addClass("d-none"); // Sembunyikan tombol tutup
    });
});
