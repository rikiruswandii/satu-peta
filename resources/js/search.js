import 'ol/ol.css';
import * as ol from 'ol';
import Map from 'ol/Map';
import View from 'ol/View';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import { fromLonLat } from 'ol/proj';
import Style from 'ol/style/Style';
import Fill from 'ol/style/Fill';
import Stroke from 'ol/style/Stroke';
import CircleStyle from 'ol/style/Circle';
import Overlay from 'ol/Overlay';
document.addEventListener('DOMContentLoaded', function () {
    // Filter
    const checkboxes = document.querySelectorAll('.regional-agency-checkbox');
    const filterForm = document.getElementById('filterForm');
    const searchForm = document.getElementById('searchForm');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {

            const searchInput = searchForm.querySelector(
                'input[name="search"]');
            if (searchInput && searchInput.value) {

                let hiddenSearch = filterForm.querySelector(
                    'input[name="search"]');
                if (!hiddenSearch) {

                    hiddenSearch = document.createElement('input');
                    hiddenSearch.type = 'hidden';
                    hiddenSearch.name = 'search';
                    filterForm.appendChild(hiddenSearch);
                }
                hiddenSearch.value = searchInput.value;
            }
            filterForm.submit();
        });
    });

    var currentMap = null;

    function initPreviewMap(elementId, geojsonPath) {

        var map = new ol.Map({
            target: elementId,
            layers: [],
            view: new ol.View({
                center: fromLonLat([0, 0]),
                zoom: 0
            }),
            interactions: [],
            controls: [],
        });

        if (geojsonPath) {

            fetch(geojsonPath)
                .then(response => response.json())
                .then(geojsonObject => {

                    var vectorSource = new VectorSource({

                        features: (new GeoJSON()).readFeatures(geojsonObject, {

                            featureProjection: 'EPSG:3857'
                        })
                    });

                    var pointStyle = new Style({

                        image: new CircleStyle({

                            radius: 5,
                            fill: new Fill({

                                color: '#73EC8B'
                            }),
                            stroke: new Stroke({

                                color: 'black',
                                width: 1
                            })
                        })
                    });

                    // Style untuk LineString
                    var lineStyle = new Style({

                        stroke: new Stroke({

                            color: '#FF5733',
                            width: 2
                        })
                    });

                    var vectorLayer = new VectorLayer({
                        source: vectorSource,
                        style: function (feature) {
                            var geometryType = feature.getGeometry().getType();
                            if (geometryType === 'Point') {
                                return pointStyle;
                            } else if (geometryType === 'LineString') {
                                return lineStyle;
                            }
                            return new Style({
                                fill: new Fill({
                                    color: '#73EC8B'
                                })
                            });
                        }
                    });

                    map.addLayer(vectorLayer);
                    var extent = vectorSource.getExtent();
                    map.getView().fit(extent, {
                        padding: [10, 10, 10, 10]
                    });
                });
        }

        return map;
    }

    document.querySelectorAll('.map-preview').forEach(function (element) {

        var geojsonPath = element.getAttribute('data-geojson');
        initPreviewMap(element.id, geojsonPath);
    });

    document.querySelectorAll('.detailMapModalTrigger').forEach(function (element) {
        element.addEventListener('click', function () {
            var path = this.getAttribute('data-geojson');
            var regional_agency = this.getAttribute('data-regional-agency');
            var sector = this.getAttribute('data-sector');
            var name = this.getAttribute('data-name');
            var cardId = this.getAttribute('data-card-id');

            var modal = document.getElementById('detailMapModal');
            if (!modal) {
                return;
            }

            modal.querySelector('#map-name').textContent = name || 'Nama tidak tersedia';
            modal.querySelector('#map-regional-agency').textContent = regional_agency || 'Agensi tidak tersedia';
            modal.querySelector('#map-sector').textContent = sector || 'Sektor tidak tersedia';

            if (!path) {
                overlay.remove();
                return;
            }

            var mapElement = document.getElementById('detailMap');
            if (mapElement) {
                mapElement.innerHTML = '';

                var overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                mapElement.appendChild(overlay);

                var modalMap = initMap('detailMap', path);

                modalMap.once('rendercomplete', function () {
                    overlay.remove();
                });
            } else {
                return;
            }
        });
    });

    var mapViewportLayering = document.getElementById('viewportLayering');
    var viewportMap = null;

    if (mapViewportLayering) {
        mapViewportLayering.innerHTML = '';

        mapViewportLayering.style.height = '200px';
        mapViewportLayering.style.border = '1px solid black';

        var overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
        mapViewportLayering.appendChild(overlay);

        viewportMap = initMap('viewportLayering');

        viewportMap.once('rendercomplete', function () {

            viewportMap.on('moveend', function () {
                var extent = viewportMap.getView().calculateExtent();
                var minLat = extent[1];
                var maxLat = extent[3];
                var minLng = extent[0];
                var maxLng = extent[2];

                fetch(`/get-maps-by-viewport?minLat=${minLat}&maxLat=${maxLat}&minLng=${minLng}&maxLng=${maxLng}`)
                    .then(response => response.json())
                    .then(data => {
                        updateMapCards(data.html, data.pagination);
                        initializeVectorLayers();
                    })
                    .catch(error => console.error('Error:', error));
            });

            overlay.remove();
        });

        const detailModal = document.getElementById('detailMapModal');
        if (detailModal) {
            detailModal.addEventListener('hidden.bs.modal', function () {
                if (viewportMap) {
                    setTimeout(() => {
                        viewportMap.updateSize();
                        const view = viewportMap.getView();
                        const center = view.getCenter();
                        view.setCenter([center[0] + 1, center[1]]);
                        view.setCenter(center);
                    }, 100);
                }
            });

            detailModal.addEventListener('show.bs.modal', function () {
                if (viewportMap) {

                    const view = viewportMap.getView();
                    viewportMap._savedCenter = view.getCenter();
                    viewportMap._savedZoom = view.getZoom();
                }
            });
        }
    }

    function updateMapCards(html, pagination) {
        const mapCardsContainer = document.querySelector('.row.g-4.g-lg-5');
        mapCardsContainer.innerHTML = html;

        const paginationContainer = document.querySelector('.mt-4');
        if (paginationContainer) {
            paginationContainer.innerHTML = pagination;
        }

        // Tunggu DOM diperbarui
        setTimeout(() => {
            // Inisialisasi peta preview untuk setiap kartu
            document.querySelectorAll('.map-preview').forEach(function (element) {
                var geojsonPath = element.getAttribute('data-geojson');
                if (geojsonPath) {
                    initPreviewMap(element.id, geojsonPath);
                }
            });

            // Tambahkan event listener untuk trigger modal peta detail
            document.querySelectorAll('.detailMapModalTrigger').forEach(function (element) {
                element.addEventListener('click', function () {
                    var path = this.getAttribute('data-geojson');
                    var regional_agency = this.getAttribute('data-regional-agency');
                    var sector = this.getAttribute('data-sector');
                    var name = this.getAttribute('data-name');

                    var modal = document.getElementById('detailMapModal');
                    if (modal) {
                        modal.querySelector('#map-name').textContent = name || 'Nama tidak tersedia';
                        modal.querySelector('#map-regional-agency').textContent = regional_agency || 'Agensi tidak tersedia';
                        modal.querySelector('#map-sector').textContent = sector || 'Sektor tidak tersedia';

                        var mapElement = document.getElementById('detailMap');
                        if (mapElement && path) {
                            mapElement.innerHTML = '';

                            // Tambahkan overlay loading
                            var overlay = document.createElement('div');
                            overlay.className = 'loading-overlay';
                            overlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                            mapElement.appendChild(overlay);

                            // Tampilkan modal
                            var modalBootstrap = new bootstrap.Modal(modal);
                            modalBootstrap.show();

                            // Inisialisasi peta setelah modal ditampilkan
                            modal.addEventListener('shown.bs.modal', function () {
                                var detailMap = initMap('detailMap', path);
                                detailMap.once('rendercomplete', function () {
                                    overlay.remove();
                                });
                            }, { once: true });

                            // Hapus backdrop secara manual saat modal ditutup
                            modal.addEventListener('hidden.bs.modal', function () {
                                document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                                    backdrop.remove();
                                });

                                document.body.classList.remove('modal-open');
                                document.body.style.overflow = 'auto';
                            }, { once: true });
                        }
                    }
                });
            });

            // Inisialisasi layer vektor
            initializeVectorLayers();
        }, 100);
    }


    function initializeVectorLayers() {

        document.querySelectorAll('.map-preview').forEach(mapElement => {
            const geojsonPath = mapElement.dataset.geojsonPath;
            if (geojsonPath) {
                const previewMap = initMap(mapElement.id);
                loadGeoJSON(geojsonPath).then(features => {
                    addVectorLayer(previewMap, features);
                });
            }
        });
    }

    async function loadGeoJSON(path) {
        try {
            const response = await fetch(path);
            return await response.json();
        } catch (error) {
            
            return null;
        }
    }

    function addVectorLayer(map, features) {
        
        if (!features) return;

        const vectorSource = new ol.source.Vector({
            features: new ol.format.GeoJSON().readFeatures(features, {
                featureProjection: 'EPSG:3857'
            })
        });

        const vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            style: new ol.style.Style({
                fill: new ol.style.Fill({
                    color: 'rgba(0, 255, 0, 0.2)'
                }),
                stroke: new ol.style.Stroke({
                    color: '#00ff00',
                    width: 2
                })
            })
        });

        map.addLayer(vectorLayer);

        // Zoom to vector layer extent
        const extent = vectorSource.getExtent();
        map.getView().fit(extent, {
            padding: [50, 50, 50, 50]
        });
    }
});