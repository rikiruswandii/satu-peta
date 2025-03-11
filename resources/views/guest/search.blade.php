<x-guest-layout>
    @section('title', $title)
    @section('description', $description)
    <x-breadcrumb :title="$title" :images="['images/carrow1 (1).JPG', 'images/carrow1 (1).JPG', 'images/carrow1 (1).JPG']">
        <x-slot name="body">
            <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
        </x-slot>
    </x-breadcrumb>


    <div class="shop-with-sidebar">
        <div class="container">
            <div class="row justify-content-between align-items-start gx-5">
                <div class="col-12 col-sm-4 col-md-3 border py-4 rounded-1">
                    <div class="shop-sidebar-area mb-5">
                        <div class="shop-widget mb-4 mb-lg-5">
                            <h5 class="widget-title mb-4">Batas Pencarian</h5>
                            <x-map-container geoJsonPath="" mapId="viewportLayering" />
                            <form id="filterForm" action="{{ route('search') }}" method="GET">
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ old('search', request('search')) }}">
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
                    <div class="row mb-4">
                        <form action="{{ route('search') }}" method="get" id="searchForm">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Kata Kunci"
                                    aria-label="Kata Kunci" aria-describedby="basic-addon2" name="search"
                                    value="{{ old('search', request('search')) }}">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            @if (request('regional_agencies_checkbox'))
                                @foreach ((array) request('regional_agencies_checkbox') as $agency)
                                    <input type="hidden" name="regional_agencies_checkbox[]"
                                        value="{{ $agency }}">
                                @endforeach
                            @endif
                        </form>
                    </div>
                    <div class="row cardMapList">
                        @forelse ($maps as $map)
                            <x-map-card :id="$map->id" :card_class="'col-12 col-md-6 col-lg-3 mb-4'" :card_id="$map->id" :card_title="$map->name"
                                :card_opd="$map->regional_agency->name" :card_filename="$map->documents->first()->name ?? 'No file'" :geojson_path="$map->documents->first() ? Storage::url($map->documents->first()->path) : ''" :regional_agency="$map->regional_agency->name"
                                :sector="$map->tags
                                    ->map(fn($tag) => $tag->getTranslation('name', 'id'))
                                    ->implode(', ')" />
                        @empty
                            <!-- SVG image -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/undraw_friends_xscy.svg') }}" alt=""
                                    class="mx-auto d-block  w-25 h-auto">
                                <h1 class="mb-3">Oops! Data Tidak Tersedia.</h1>
                                <p class="lead">Data yang Anda cari saat ini tidak tersedia
                                    atau belum ditambahkan. Silakan coba lagi nanti.</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($maps->isNotEmpty())
                        <div class="saasbox-pagination-area my-5 mb-lg-0">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center mb-0">
                                    @if ($maps->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">Prev</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link link-success" href="{{ $maps->previousPageUrl() }}"
                                                rel="prev">Prev</a>
                                        </li>
                                    @endif

                                    <!-- Menampilkan halaman secara dinamis -->
                                    @foreach ($maps->getUrlRange(1, $maps->lastPage()) as $page => $url)
                                        <li class="page-item {{ $page == $maps->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    @if ($maps->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link link-success" href="{{ $maps->nextPageUrl() }}"
                                                rel="next">Next</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-120 d-block"></div>

    @section('modal')
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
    @endSection()

    @push('css')
        @vite('resources/css/search.css')
    @endpush

    @push('scripts')
        @vite('resources/js/search.js')
    @endpush


</x-guest-layout>
