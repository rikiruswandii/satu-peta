<x-guest-layout>
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
                <div class="col-12">
                    <div class="card shadow-lg bg-text-gray border-0" id="searchCard"
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
                                        <input id="input-search" type="text" class="form-control"
                                            placeholder="Masukkan kata kunci...">

                                        <!-- Pilihan Dataset & Instansi (Hidden by Default) -->
                                        <div id="extraOptions" class="d-flex w-100 d-none">
                                            <select class="form-select select2 form-control">
                                                <option selected>Semua Kategori</option>
                                                <option>Dataset A</option>
                                                <option>Dataset B</option>
                                            </select>
                                            <select class="form-select select2 form-control">
                                                <option selected>Semua Instansi</option>
                                                <option>Instansi X</option>
                                                <option>Instansi Y</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Tombol Cari -->
                                    <button class="btn btn-warning ms-2">Cari</button>
                                </div>
                            </form>

                            <!-- Dropdown untuk Peta (Sekarang di dalam card-body) -->
                            <div id="dropdownMenu" class="d-none mt-3">
                                <x-map-container mapId="searchMapId" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

        <style>
            #dropdownMenu {
                transition: opacity 0.3s ease, transform 0.3s ease;
                opacity: 0;
                transform: translateY(-10px);
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

                    // Toggle kelas d-block untuk dropdown dan extraOptions
                    dropdownMenu.toggleClass('d-none').toggleClass('d-block');
                    extraOptions.toggleClass('d-none').toggleClass('d-block');
                    inputSearch.toggleClass('expanded');
                });

                $jq('.select2').select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });

                initMap('searchMapId', 'osm', [], []);
            });
        </script>
    @endpush

</x-guest-layout>
