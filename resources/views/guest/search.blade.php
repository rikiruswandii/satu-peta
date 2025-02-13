<x-guest-layout>
    @section('title', $title)
    @section('description', $description)
    <x-breadcrumb :title="$title">
        <x-slot name="body">
            <form action="{{ route('search') }}" method="get" id="searchForm">
                <div class="input-group m-3" style="width: 90%;">
                    <input type="text" class="form-control" placeholder="Kata Kunci" aria-label="Kata Kunci"
                        aria-describedby="basic-addon2" name="search" value="{{ request('search') }}"
                        style="height: 20px;">
                    <button type="submit" class="input-group-text bg-success text-white" id="basic-addon2"
                        style="height: 32px">Cari</button>
                </div>
                @if (request('regional_agencies'))
                    @foreach ((array) request('regional_agencies') as $agency)
                        <input type="hidden" name="regional_agencies[]" value="{{ $agency }}">
                    @endforeach
                @endif

            </form>
        </x-slot>
    </x-breadcrumb>

    <div class="shop-with-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-4 col-md-3">
                    <div class="shop-sidebar-area mb-5">
                        <div class="shop-widget mb-4 mb-lg-5">
                            <h5 class="widget-title mb-4">Batas Pencarian</h5>
                            <x-map-container geoJsonPath="" mapId="viewportLayering" />
                            <form id="filterForm" action="{{ route('search') }}" method="GET">
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                @php
                                    $groupedBySector = $regionalAgencySum->groupBy('nama_sektor');
                                @endphp

                                @foreach ($groupedBySector as $namaSektor => $items)
                                    <h5 class="widget-title my-4">{{ $namaSektor }}</h5>

                                    @foreach ($items as $item)
                                        <x-map-category :category_name="$item->name" :category_count="$item->total" :category_id="$item->name" />
                                    @endforeach
                                @endforeach
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-sm-8 col-md-9">
                    <div class="row g-4 g-lg-5">
                        @foreach ($maps as $map)
                            <x-map-card :id="$map->id" :card_class="'col-12 col-md-6 col-lg-4 mb-4'" :card_id="$map->id" :card_title="$map->name"
                                :card_opd="$map->regional_agency->name" :card_filename="$map->documents->first()->name ?? 'No file'" :geojson_path="$map->documents->first() ? Storage::url($map->documents->first()->path) : ''" :regional_agency="$map->regional_agency->name"
                                :sector="$map->sector->name" />
                        @endforeach
                    </div>
                    <!-- Menampilkan link pagination dengan Tailwind CSS -->
                    <div class="mt-4">
                        {{ $maps->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal id="detailMapModal" :data="['title' => 'Detail Peta', 'footer' => '']" :size="'xl'" :cancelButtonText="'Tutup'">
        <x-slot name="body">
            <x-map-container geoJsonPath="" mapId="detailMap" />
            <table class="table">
                <tr>
                    <th>Nama</th>
                    <td id="map-name"></td>
                </tr>
                <tr>
                    <th>Regional Agency</th>
                    <td id="map-regional-agency"></td>
                </tr>
                <tr>
                    <th>Sector</th>
                    <td id="map-sector"></td>
                </tr>
            </table>
        </x-slot>
    </x-modal>

    @push('css')
        <style>
            #detailMap {
                position: relative;
            }

            .loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(255, 255, 255, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .active>.page-link,
            .page-link.active {
                background-color: #009d6b !important;
                color: #fff !important;
                border-color: #009d6b !important;
            }

            .page-link {
                color: #333 !important;
                border: none;
                border-radius: 4px;
                padding: 0.5rem 1rem;
                margin: 0.25rem;
                transition: background-color 0.3s ease;
            }

            .basemap-toggle-btn {
                display: none !important;
            }

            #popup.ol-popup {
                display: none !important;
            }

            .hover-card {
                border: none;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .hover-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
                cursor: pointer;
            }

            .map-container {
                position: relative;
                height: 200px;
                overflow: hidden;
            }

            .map-preview {
                width: 100%;
                height: 100%;
                cursor: pointer;
            }

            .map-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .hover-card:hover .map-overlay {
                opacity: 1;
            }

            .view-details {
                color: white;
                background: rgba(0, 157, 107, 0.9);
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
                transform: translateY(20px);
                transition: transform 0.3s ease;
            }

            .hover-card:hover .view-details {
                transform: translateY(0);
            }

            .card {
                border: 0.5px solid rgb(157, 157, 157);
            }

            .card-content {
                border-top-left-radius: 25px;
                border-top-right-radius: 25px;
                border-top: 0.5px solid rgb(157, 157, 157);
                background: white;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 1rem;
                line-height: 1.4;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .card-info {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .info-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #64748b;
                font-size: 0.9rem;
            }

            .info-item i {
                color: #009d6b;
                font-size: 1rem;
            }

            .info-item span {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            @media (max-width: 768px) {
                .card-title {
                    font-size: 1rem;
                }

                .info-item {
                    font-size: 0.85rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        @vite('resources/js/search.js')
    @endpush


</x-guest-layout>
