@props(['card_title', 'card_filename', 'card_opd', 'card_id', 'geojson_path', 'regional_agency', 'sector'])

<div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card shop-card hover-card">
        <div class="product-img-wrap">
            <div id="map-{{ $card_id }}" class="map-preview detailMapModalTrigger"
                style="height: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#detailMapModal"
                data-geojson="{{ $geojson_path }}" data-regional-agency="{{ $regional_agency }}"
                data-sector="{{ $sector }}" data-name="{{ $card_title }}" data-card-id="{{ $card_id }}">
            </div>
            <div class="map-overlay detailMapModalTrigger" data-bs-toggle="modal" data-bs-target="#detailMapModal"
                data-geojson="{{ $geojson_path }}" data-regional-agency="{{ $regional_agency }}"
                data-sector="{{ $sector }}" data-name="{{ $card_title }}" data-card-id="{{ $card_id }}">
                <span class="view-details">Lihat Detail</span>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <h5 class="card-title">{{ $card_title }}</h5>
                <div class="card-info">
                    <div class="info-item">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>{{ $card_filename }}</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-building"></i>
                        <span>{{ $card_opd }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
