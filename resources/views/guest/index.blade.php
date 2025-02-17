<x-guest-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="welcome-area hero6 bg-white">
        <div class="welcome4-slide-wrap">
            <!-- Slide Item -->
            <div class="welcome4-slide-item" style="background-image: url('./images/carrow1 (1).JPG')">
            </div>
            <!-- Slide Item -->
            <div class="welcome4-slide-item" style="background-image: url('./images/carrow1 (1).JPG')">
            </div>
        </div>
    </div>

    <!-- Floating Search Container -->
    <div class="search-area">
        <div class="container">
            <div class="row">
                <div class="row mb-4 justify-content-center align-items-center">
                    <h1 id="app-name" class="text-center text-light">{{ $app->name }}</h1>
                    <strong class="text-center text-light">{{ $app->about }}</strong>
                    <hr />
                    <strong class="text-center text-light">{{ __('Temukan dataset dengan mudah!') }}</strong>
                </div>
                <div class="rounded p-0 m-0 shadow-lg bg-text-gray border-0" id="searchCard"
                    style="background: rgba(255, 255, 255, 0.5) !important;">
                    <div class="card-body">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="d-flex align-items-center">
                                <!-- Gear Icon (Trigger Dropdown) -->
                                <button type="button" class="btn btn-light border me-2" id="dropdownTrigger">
                                    <i class="bi bi-gear text-dark"></i>
                                </button>

                                <div class="d-flex w-100">
                                    <!-- Input Pencarian -->
                                    <input id="input-search" name="search" type="text" class="form-control"
                                        placeholder="Masukkan kata kunci...">

                                    <!-- Pilihan Dataset & Instansi (Hidden by Default) -->
                                    <div id="extraOptions" class="d-flex w-100 d-none">
                                        <select name="sector" class="form-select select2 form-control">
                                            <option value="" selected disabled>Semua Kategori</option>
                                            @foreach ($categories as $sector)
                                                <option value="{{ $sector->name }}">{{ $sector->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="regional_agencies" class="form-select select2 form-control">
                                            <option value="" disabled selected>Semua Instansi</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Tombol Cari -->
                                <button class="btn btn-warning ms-2">Cari</button>
                            </div>
                        </form>

                        <!-- Dropdown untuk Peta (Sekarang di dalam card-body) -->
                        <div id="dropdownMenu" class="d-none bg-text-gray border-0 mt-3">
                            <x-map-container mapId="searchMapId" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Area-->
    <div class="saasbox-portfolio-area pt-120 pb-120 bg-gray">
        <div class="container">
            <div class="row align-items-end justify-content-between">
                <div class="col-12 col-sm-8 col-lg-7 col-xxl-6">
                    <div class="section-heading mb-0">
                        <h6>Dataset Peta</h6>
                        <h2>Jelajahi Peta Terbaru Kami</h2>
                        <p>Temukan berbagai dataset peta terbaru yang telah diperbarui dengan informasi terkini dan
                            detail yang akurat untuk kebutuhan analisis dan visualisasi Anda.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-5">
                    <div class="text-sm-end mt-5 mt-sm-0">
                        <a class="btn btn-warning" href="{{ route('search') }}">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-block mb-80"></div>
        <div class="container">
            <div class="row g-3">
                @foreach ($maps as $map)
                    <x-map-card :id="$map->id" :card_class="'col-12 col-md-6 col-lg-3 my-4'" :card_id="$map->id" :card_title="$map->name"
                        :card_opd="$map->regional_agency->name" :card_filename="$map->documents->first()->name ?? 'No file'" :geojson_path="$map->documents->first() ? Storage::url($map->documents->first()->path) : ''" :regional_agency="$map->regional_agency->name" :sector="$map->sector->name" />
                @endforeach
            </div>
        </div>
    </div>
    <div class="mb-120 d-block"></div>

    <!-- Category Area-->
    <div class="partner-area py-5 bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="partnerCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($groups->chunk(6) as $index => $opdChunk)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="row">
                                        @foreach ($opdChunk as $partner)
                                            <div class="col-2 text-center">
                                                <div class="partner-logo">
                                                    <img src="{{ asset('assets/images/logo.png') }}" alt=""
                                                        class="img-fluid" style="width:80px;height:85px;">
                                                    <p class="mt-2">{{ $partner->name }}</p>
                                                    <!-- Nama tampil di bawah gambar -->
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <!-- Tombol Navigasi -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#partnerCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#partnerCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    @endsection
    <div class="mb-120 d-block"></div>
    <div class="saasbox-news-area news2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-9 col-lg-7 col-xxl-6">
                    <div class="section-heading text-center">
                        <h6>Artikel Terbaru</h6>
                        <h2>Berita Terbaru Kami</h2>
                        <p>Website ini menyediakan informasi dan artikel terkait kepentingan publik.
                            {{ config('app.name') }} mendukung pelayanan publik yang transparan dan akuntabel.</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center g-4 g-md-5 g-lg-4 g-xl-5">
                <!-- Blog Card -->
                @foreach ($news as $value)
                    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                        <div class="card blog-card border-0">
                            <a class="image-wrap d-block" href="{{ route('article.show', $value->slug) }}"
                                style="width:100%; height: 200px; object-fit: cover;">
                                <img src="{{ Storage::url($value->documents->first()->path) }}" alt="">
                            </a>
                            <div class="card-body px-4 pb-0">
                                <a class="badge bg-primary text-white mb-1"
                                    href="{{ route('article.show', $value->slug) }}">{{ $value->category->name }}</a>
                                <a class="post-title d-block mb-3" href="{{ route('article.show', $value->slug) }}">
                                    {{ Str::limit($value->title, 40, '...') }}
                                </a>
                                <div class="post-meta">
                                    <span class="text-muted fz-14">
                                        <i
                                            class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="mb-120 d-block"></div>

    <!-- Group Area-->


    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

        <style>
            #dropdownMenu {
                width: 100%;
                transition: opacity 0.3s ease, transform 0.3s ease;
                opacity: 0;
                transform: translateY(10px);
                visibility: hidden;
                pointer-events: none;
                /* Menonaktifkan interaksi saat tersembunyi */
            }

            #dropdownMenu.d-block {
                opacity: 1;
                transform: translateY(0);
                visibility: visible;
                pointer-events: auto;
                /* Mengaktifkan interaksi saat terlihat */
            }

            #extraOptions {
                transition: opacity 0.3s ease, transform 0.3s ease;
                opacity: 0;
                transform: translateY(-10px);
                visibility: hidden;
            }

            #extraOptions.d-block {
                opacity: 1;
                transform: translateY(0);
                visibility: visible;
            }

            #input-search {
                transition: width 0.3s ease;
                width: 100%;
                /* Lebar default */
            }

            #searchCard {
                transition: width 0.3s ease;
            }

            #input-search.expanded {
                width: 50%;
                /* Lebar saat extraOptions muncul */
            }

            .select2-container .select2-selection--single {
                height: 52px !important;
                display: flex;
                align-items: center;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                line-height: 52px !important;
                padding-left: 10px;
            }

            .select2-container .select2-selection--single {
                background-color: #f8f9fa !important;
                border: 2px solid #d4d4d4a2 !important;
                color: #000 !important;
            }

            .select2-dropdown {
                background-color: #ffffff !important;
                border: 1px solid #0fac81 !important;
            }

            .select2-results__option {
                color: #333 !important;
            }

            .select2-results__option--highlighted {
                background-color: #0fac81 !important;
                color: white !important;
            }

            .select2-selection__placeholder {
                color: #6c757d !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                border-color: #0fac81 transparent transparent transparent !important;
            }

            .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option.select2-results__option--selected,
            .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option[aria-selected=true]:not(.select2-results__option--highlighted) {
                color: #fff !important;
                background-color: #0fac81;
            }

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

            .position-relative:hover .map-overlay {
                opacity: 1;
                pointer-events: auto;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
        @vite('resources/js/search.js')
        <script>
            var $jq = jQuery.noConflict();
            $jq(document).ready(function() {
                $jq('#dropdownTrigger').on('click', function(event) {
                    event.stopPropagation();

                    let dropdownMenu = $jq('#dropdownMenu');
                    let extraOptions = $jq('#extraOptions');
                    let inputSearch = $jq('#input-search');

                    $jq('#app-name').toggleClass('d-none').toggleClass('d-block');

                    // Toggle kelas d-block untuk dropdown dan extraOptions
                    dropdownMenu.toggleClass('d-none').toggleClass('d-block');
                    extraOptions.toggleClass('d-none').toggleClass('d-block');
                    inputSearch.toggleClass('expanded');
                });

                $jq('.select2').select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });

                initMap('searchMapId', '', {
                    scale: true,
                    fullScreen: true
                }, {});
            });
        </script>
    @endpush

</x-guest-layout>
