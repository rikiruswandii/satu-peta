@props(['card_class', 'card_title', 'card_filename', 'card_opd', 'card_id', 'geojson_path', 'regional_agency', 'sector'])

<div class="{{ $card_class }}">
    <div class="card rounded-1 hover-card shadow-sm border overflow-hidden" style="width: 100%; max-width: 350px; min-height: 350px;">
        <div class="position-relative" style="height: 200px;">
            <div id="map-{{ $card_id }}" class="map-preview detailMapModalTrigger w-100 h-100"
                style="cursor: pointer; border-radius: 0.5rem 0.5rem 0 0;"
                data-bs-toggle="modal" data-bs-target="#detailMapModal"
                data-geojson="{{ $geojson_path }}" data-regional-agency="{{ $regional_agency }}"
                data-sector="{{ $sector }}" data-name="{{ $card_title }}" data-card-id="{{ $card_id }}">
            </div>
            <div class="map-overlay detailMapModalTrigger position-absolute top-50 start-50 translate-middle bg-success bg-opacity-50 text-white p-0 rounded shadow h-25"
                data-bs-toggle="modal" data-bs-target="#detailMapModal" data-geojson="{{ $geojson_path }}"
                data-regional-agency="{{ $regional_agency }}" data-sector="{{ $sector }}" data-name="{{ $card_title }}"
                data-card-id="{{ $card_id }}" style="cursor: pointer;">
                <span class="fw-bold">Lihat Detail</span>
            </div>
        </div>
        <div class="card-body bg-light d-flex flex-column justify-content-between" style="min-height: 150px;">
            <h6>{{ $card_title }}</h6>
            <div class="d-flex flex-column gap-2 mt-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text text-secondary"></i>
                    <span class="text-muted text-truncate fs-6" style="max-width: 100%;">{{ $card_filename }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-building text-secondary"></i>
                    <span class="text-muted text-truncate fs-6" style="max-width: 100%;">{{ $card_opd }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
