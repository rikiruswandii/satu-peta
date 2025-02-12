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
        export: true,
        draw: true
    }, {
        dragPan: true
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
        if ($m("#layerList li").length >= 10) {
            alert("Maksimal 10 layer yang dapat ditambahkan.");
            return;
        }

        let layerId = `layer-${Date.now()}`;
        layer.set("id", layerId);

        let listItem = `
    <li data-layer-id="${layerId}" class="list-group-item text-success d-flex justify-content-between align-items-center" style="cursor:pointer;font-size:14px;">
        <div class="d-flex align-items-center gap-2">
            <input type="checkbox" id="is-active" value="" checked>
            <span>${name}</span>
        </div>
        <div class="d-flex gap-2">
            <i id="boundToLayer" class="bi bi-aspect-ratio link-secondary"></i>
            <i id="removeLayer" class="bi bi-x-circle-fill link-danger"></i>
        </div>
    </li>`;

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
        $m('.modal-title').attr('style', 'color: white !important; font-size: 16px !important;');
        $m('.modal-content').css({
            'background': 'rgba(255, 255, 255, 0.5)',
            'backdrop-filter': 'blur(3px)'
        });
        $m('.modal-dialog').css({
            position: 'fixed',
            width: '100%',
            margin: 0,
            padding: '10px'
        });

        if (!($m('.modal.in').length)) {
            $m('.modal-dialog').css({
                top: 200,
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

        $m("#search-dataset").on("input", function () {
            let searchText = $m(this).val().toLowerCase();
            let hasResults = false;

            // Loop melalui setiap accordion item (regional agency)
            $m(".accordion-item").each(function () {
                let agencyName = $m(this).find(".accordion-button").text().trim().toLowerCase();
                let hasVisibleDataset = false;

                // Loop melalui setiap dataset dalam regional agency
                $m(this).find(".list-group-item").each(function () {
                    let datasetName = $m(this).text().trim().toLowerCase();

                    // Cek apakah pencarian cocok dengan agency atau dataset
                    let isMatch = agencyName.includes(searchText) || datasetName.includes(searchText);
                    $m(this).toggle(isMatch); // Tampilkan/sembunyikan dataset

                    if (isMatch) {
                        hasVisibleDataset = true;
                        hasResults = true;
                    }
                });

                // Tampilkan/sembunyikan regional agency berdasarkan apakah ada dataset yang cocok
                $m(this).toggle(hasVisibleDataset || agencyName.includes(searchText));

                if (agencyName.includes(searchText)) {
                    hasResults = true;
                }
            });

            // Tampilkan pesan jika tidak ada hasil
            if (!hasResults) {
                $m("#no-results").removeClass("d-none");
            } else {
                $m("#no-results").addClass("d-none");
            }
        });
    });

    $m(document).on("change", "#layerList li #is-active", function () {
        let layerId = $m(this).closest("li").data("layer-id");
        let layer = map.getLayers().getArray().find(l => l.get("id") === layerId);
        if (layer) {
            layer.setVisible($m(this).is(":checked"));
        }
    });

    $m(document).on("click", "#layerList li #boundToLayer", function () {
        let layerId = $m(this).closest("li").data("layer-id");
        let layer = map.getLayers().getArray().find(l => l.get("id") === layerId);
        if (layer) {
            let extent = layer.getSource().getExtent();
            if (extent) {
                map.getView().fit(extent, { duration: 500 });
            }
        }
    });

    $m(document).on("click", "#layerList li #removeLayer", function () {
        let layerId = $m(this).closest("li").data("layer-id");
        let layer = map.getLayers().getArray().find(l => l.get("id") === layerId);
        if (layer) {
            map.removeLayer(layer);
        }
        $m(this).closest("li").remove();
    });

    function searchLocation() {
        const query = $m("#search-from-aside").val().trim();
        if (!query) {
            console.warn("Query kosong, pencarian dibatalkan.");
            return;
        }

        console.log("Mencari lokasi:", query);

        fetch(`${import.meta.env.VITE_PHOTON_URL}?q=${encodeURIComponent(query)}&limit=10`)
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
                        .addClass("list-group-item text-success")
                        .text(name)
                        .css({ cursor: "pointer" })
                        .on("click", function () {
                            map.getView().animate({
                                center: fromLonLat([lon, lat]),
                                zoom: 14,
                                duration: 1000
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

                            // Fungsi untuk memperbarui marker tanpa menghapus yang lama

                            // Buat marker baru dengan ID unik
                            const markerFeature = new Feature({
                                geometry: new Point(fromLonLat([lon, lat])),
                                name: name, // Tambahkan nama untuk referensi
                            });

                            vectorSource.addFeature(markerFeature);
                            addLayerToList(vectorLayer, name); // Tambahkan ke daftar layer
                            updateLayerOrder();
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
