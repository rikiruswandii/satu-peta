@props(['card_title', 'card_filename', 'card_opd', 'card_id'])

<div class="col-12 col-md-6 mb-4">
    <div class="card shop-card">
        <div class="product-img-wrap">
            <!-- Klik gambar membuka modal map -->
            <img class="card-img-top" src="img/bg-img/shop1.jpg" alt=""
                style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modal-map-{{ $card_id }}">
            
            <!-- Klik ikon membuka modal informasi -->
            <a class="love-product" href="#" data-bs-toggle="modal" data-bs-target="#modal-info-{{ $card_id }}" 
                title="Information">
                <i class="bi bi-info"></i>
            </a>
        </div>
        <div class="product-meta d-flex align-items-center justify-content-between p-4">
            <div class="product-name">
                <h6>{{ $card_title }}</h6>
                <h6 class="price">{{ $card_filename }}</h6>
                <h6 class="text-muted">{{ $card_opd }}</h6>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Map -->
<x-modal :id="'modal-map-' . $card_id" :data="['title' => 'Peta Lokasi: ' . $card_title, 'footer' => '']" :size="'xl'">
    <x-slot name="body">
        Peta lokasi untuk <strong>{{ $card_title }}</strong>.
    </x-slot>
</x-modal>

<!-- Modal untuk Informasi -->
<x-modal :id="'modal-info-' . $card_id" :data="['title' => 'Informasi Produk', 'footer' => '']" :size="'lg'">
    <x-slot name="body">
        Informasi tambahan untuk kartu <strong>{{ $card_title }}</strong>.  
        <p>Nama File: {{ $card_filename }}</p>
        <p>OPD: {{ $card_opd }}</p>
    </x-slot>
</x-modal>
