import 'ol/ol.css';
import * as ol from 'ol';
import { fromLonLat } from 'ol/proj';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import Style from 'ol/style/Style';
import CircleStyle from 'ol/style/Circle';
import Fill from 'ol/style/Fill';
import Stroke from 'ol/style/Stroke';
document.addEventListener('DOMContentLoaded', function() {
    // Filter
    const checkboxes = document.querySelectorAll('.regional-agency-checkbox');
    const filterForm = document.getElementById('filterForm');
    const searchForm = document.getElementById('searchForm');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Tambahkan parameter pencarian ke form filter jika ada
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

    // Map
    var currentMap = null;

    // Fungsi untuk menginisialisasi preview peta
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
                                // color: 'rgba(211, 211, 211, 0.8)'
                                color: '#000000'
                            }),
                            stroke: new Stroke({
                                color: 'black',
                                width: 1
                            })
                        })
                    });

                    var vectorLayer = new VectorLayer({
                        source: vectorSource,
                        style: function (feature) {
                            if (feature.getGeometry().getType() === 'Point') {
                                return pointStyle;
                            }
                            return new Style({
                                fill: new Fill({
                                    // color: 'rgba(211, 211, 211, 0.8)'
                                    color: '#000000'
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

    document.querySelectorAll('.map-preview').forEach(function(element) {
        var geojsonPath = element.getAttribute('data-geojson');
        initPreviewMap(element.id, geojsonPath);
    });

    document.querySelectorAll('.detailMapModalTrigger').forEach(function(element) {
        element.addEventListener('click', function() {
            var path = this.getAttribute('data-geojson');
            var regional_agency = this.getAttribute('data-regional-agency');
            var sector = this.getAttribute('data-sector');
            var name = this.getAttribute('data-name');
            var cardId = this.getAttribute('data-card-id');
            console.log(name, regional_agency, sector, cardId);
    
            // Pengecekan modal apakah ada
            var modal = document.getElementById('detailMapModal');
            if (!modal) {
                console.error("Modal tidak ditemukan");
                return;
            }
    
            // Update konten modal
            modal.querySelector('#map-name').textContent = name || 'Nama tidak tersedia';
            modal.querySelector('#map-regional-agency').textContent = regional_agency || 'Agensi tidak tersedia';
            modal.querySelector('#map-sector').textContent = sector || 'Sektor tidak tersedia';
    
            // Pengecekan path GeoJSON
            if (!path) {
                console.error('GeoJSON path tidak ditemukan.');
                overlay.remove();
                return;
            }
    
            // Menghapus peta lama jika ada
            if (currentMap) {
                currentMap.setTarget(null);  // Menghapus target peta lama
                currentMap = null;  // Reset peta lama
            }
    
            // Inisialisasi peta baru
            var mapElement = document.getElementById('detailMap');
            if (mapElement) {
                mapElement.innerHTML = '';  // Kosongkan elemen peta
                
                // Tambahkan overlay ke elemen peta
                var overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                mapElement.appendChild(overlay);
                
                currentMap = initMap('detailMap', path);  // Inisialisasi peta baru dengan path GeoJSON

                // Tunggu hingga peta selesai dimuat
                currentMap.once('rendercomplete', function() {
                    // Hapus overlay setelah peta selesai dimuat
                    overlay.remove();
                });
            } else {
                console.error('Elemen peta tidak ditemukan.');
                return;
            }
        });
    });      
});