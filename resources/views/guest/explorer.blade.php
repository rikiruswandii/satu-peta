<x-guest-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDataset = [
            'title' => 'Tambah Layer',
            'footer' => '',
        ];
    @endphp
    @push('css')
        <style>
            #openModalDataset {
                position: relative;
            }

            #openModalDataset .modal-dialog {
                position: fixed;
                width: 100%;
                margin: 0;
                padding: 10px;
            }

            .btn-login {
                background: #0fac81;
                color: #ffffff;
                padding: 10px;
                margin: 0;
                border-radius: 3px;
                height: 30px;
                display: flex;
                align-items: center;
            }

            .btn-login:hover {
                background: none;
                color: #0fac81;
                border: 5px;
                border-radius: 3px;
                border-color: #0fac81;
                box-shadow: 5px 5px 10px rgba(7, 160, 96, 0.5);
            }

            #exportModal .modal-dialog {
                position: relative;
                margin: auto;
            }

            #preview-map {
                max-height: 300px;
                object-fit: contain;
            }

            .ol-control.ol-zoom {
                position: absolute !important;
                top: auto !important;
                bottom: 5.7em !important;
                /* Atur jarak dari bawah */
                left: auto !important;
                right: .5em !important;
                /* Atur jarak dari kanan */
                z-index: 1000 !important;
                /* Pastikan tampil di atas */
            }
            .ol-control.ol-zoomslider {
                position: absolute !important;
                top: auto !important;
                bottom: 9em !important;
                /* Atur jarak dari bawah */
                left: auto !important;
                right: .5em !important;
                /* Atur jarak dari kanan */
                z-index: 1000 !important;
                /* Pastikan tampil di atas */
            }
        </style>
    @endpush

    <div class="d-flex">

        @include('guest.partials.sidebar')

        <div class="content" id="main-content">
            <x-map-container geoJsonPath="" mapId="explorerMapId" :height="'91vh'" />
        </div>
    </div>

    @section('modal')
        <!-- Modal -->
        <x-modal :id="'openModalDataset'" :data="$modalDataset" :showCancelButton="false" :size="'sm'">
            <x-slot name="body">
                <div class="p-2 d-flex justify-content-center align-items-center">
                    <input class="border-0 p-1 rounded-start" type="text" name="search-dataset" id="search-dataset"
                        placeholder="cari.."><button class="border-0 p-1 rounded-end" type="button" id="search-btn"><i
                            class="bi bi-search text-success ms-1"></i></button>
                </div>
                <div class="mt-1 row p-2 overflow-x-auto body-dataset" style="max-height: 370px; scrollbar-width: none;">
                    <div id="no-results" class="alert alert-warning text-center d-none">
                        <i class="bi bi-exclamation-triangle-fill"></i> Data tidak ditemukan.
                    </div>

                    <div class="col-12 text-success text-sm">
                        <div class="accordion" id="accordionExample">
                            @if ($data->isNotEmpty())
                                <div class="accordion" id="accordionExample">
                                    @foreach ($data as $index => $regionalAgency)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                <button class="accordion-button collapsed text-success" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                                    aria-expanded="false" aria-controls="collapse{{ $index }}">
                                                    <i class="bi bi-card-image me-2"></i>
                                                    {{ $regionalAgency->name ?? 'Tidak Ada Regional Agency' }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $index }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul class="list-group overflow-y-auto">
                                                        @foreach ($regionalAgency->map as $map)
                                                            @foreach ($map->documents as $document)
                                                                <li class="list-group-item">
                                                                    <a href="javascript:void(0);" id="activateLayerButton"
                                                                        data-geojson="{{ $document->path ? Storage::url($document->path) : '' }}"
                                                                        data-name="{{ $map->name }}"
                                                                        class="d-flex text-nowrap">
                                                                        <i
                                                                            class="bi bi-plus me-2"></i>{{ $map->name ?? 'Tidak Ada Nama' }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Data tidak tersedia.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </x-slot>
        </x-modal>
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="exportModalLabel">Cetak Layer</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="map-title" class="form-label">Judul Peta:</label>
                        <input type="text" id="map-title" class="form-control" value="Judul Peta">
                        <div id="preview" class="border border-dashed p-3 mt-3 text-center">
                            <h3 id="preview-title">Judul Peta</h3>
                            <img id="preview-map" class="mt-3 w-100" style="display:none; border: 1px solid #ccc;" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="export-png" class="btn btn-outline-success">PNG</button>
                        <button id="export-pdf" class="btn btn-success">PDF</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        @vite('resources/js/explorer.js')
    @endpush
    @push('js')
    @endpush
</x-guest-layout>
