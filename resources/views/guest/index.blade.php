<x-guest-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="welcome-area hero6 bg-white">
        <div class="welcome4-slide-wrap">
            <!-- Slide Item-->
            <div class="welcome4-slide-item" style="background-image: url('./images/carrow1 (1).JPG')">
            </div>
            <!-- Slide Item-->
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
                <div class="rounded p-3 m-0 shadow-lg bg-text-gray border-0" id="searchCard"
                    style="background: rgba(255, 255, 255, 0.5) !important;">
                    <div class="card-body">
                        <form action="#" method="GET">
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
                                        <select name="category" class="form-select select2 form-control">
                                            <option selected>Semua Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="agency" class="form-select select2 form-control">
                                            <option selected>Semua Instansi</option>
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
                        <p>Temukan berbagai dataset peta terbaru yang telah diperbarui dengan informasi terkini dan detail yang akurat untuk kebutuhan analisis dan visualisasi Anda.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-5">
                    <div class="text-sm-end mt-5 mt-sm-0">
                        <a class="btn btn-warning" href="dataset-map.html">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-block mb-80"></div>
        <div class="container-fluid">
            <div class="portfolio2-wrapper px-3">
                <div class="portfolio2-slides">
                    <div>
                        <!-- Single Portfolio Area -->
                        <div class="single-portfolio-area"><img src="img/bg-img/p22.jpg" alt="">
                            <!-- Ovarlay Content -->
                            <div class="overlay-content">
                                <div class="portfolio-title">
                                    <h6 class="mb-0">Batas Kecamatan</h6>
                                </div>
                                <div class="portfolio-links"><a class="portfolio-img-zoom" href="img/bg-img/p22.jpg"><i
                                            class="bi bi-arrows-fullscreen"></i></a><a
                                        href="portfolio-details-one.html"><i class="bi bi-link-45deg"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Single Portfolio Area -->
                        <div class="single-portfolio-area"><img src="img/bg-img/p23.jpg" alt="">
                            <!-- Ovarlay Content -->
                            <div class="overlay-content">
                                <div class="portfolio-title">
                                    <h6 class="mb-0">Batas Kelurahan</h6>
                                </div>
                                <div class="portfolio-links"><a class="portfolio-img-zoom" href="img/bg-img/p23.jpg"><i
                                            class="bi bi-arrows-fullscreen"></i></a><a
                                        href="portfolio-details-one.html"><i class="bi bi-link-45deg"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Single Portfolio Area -->
                        <div class="single-portfolio-area"><img src="img/bg-img/p24.jpg" alt="">
                            <!-- Ovarlay Content -->
                            <div class="overlay-content">
                                <div class="portfolio-title">
                                    <h6 class="mb-0">Batas Desa</h6>
                                </div>
                                <div class="portfolio-links"><a class="portfolio-img-zoom" href="img/bg-img/p24.jpg"><i
                                            class="bi bi-arrows-fullscreen"></i></a><a
                                        href="portfolio-details-one.html"><i class="bi bi-link-45deg"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Single Portfolio Area-->
                        <div class="single-portfolio-area"><img src="img/bg-img/p25.jpg" alt="">
                            <!-- Ovarlay Content -->
                            <div class="overlay-content">
                                <div class="portfolio-title">
                                    <h6 class="mb-0">Administrasi Desa</h6>
                                </div>
                                <div class="portfolio-links"><a class="portfolio-img-zoom" href="img/bg-img/p25.jpg"><i
                                            class="bi bi-arrows-fullscreen"></i></a><a
                                        href="portfolio-details-one.html"><i class="bi bi-link-45deg"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Single Portfolio Area -->
                        <div class="single-portfolio-area"><img src="img/bg-img/p26.jpg" alt="">
                            <!-- Ovarlay Content -->
                            <div class="overlay-content">
                                <div class="portfolio-title">
                                    <h6 class="mb-0">Puskesmas</h6>
                                </div>
                                <div class="portfolio-links"><a class="portfolio-img-zoom"
                                        href="img/bg-img/p26.jpg"><i class="bi bi-arrows-fullscreen"></i></a><a
                                        href="portfolio-details-one.html"><i class="bi bi-link-45deg"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <!-- Slide 1 -->
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid"  style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide 2 -->
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="partner-logo">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid"  style="width:80px;height:85px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#partnerCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#partnerCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
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
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
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

                initMap('searchMapId', '' , { scale: true, fullScreen: true, zoomSlider: false, basemap:false, draw:false }, { dragPan: false, mouseWheelZoom: false });
            });
        </script>
    @endpush

</x-guest-layout>
