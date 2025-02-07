<x-guest-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDataset = [
            'title' => 'Layer',
            'footer' => '',
        ];
    @endphp
    @push('css')
        <style>
            #openModalDataset {
                position: relative;
            }

            .modal-dialog {
                position: fixed;
                width: 100%;
                margin: 0;
                padding: 10px;
            }
        </style>
    @endpush

    <div class="d-flex">

        @include('guest.partials.sidebar')

        <div class="content" id="main-content">
            <x-map-container mapId="explorerMapId" :height="'91vh'" />
        </div>
    </div>

    @section('modal')
        <!-- Modal -->
        <x-modal :id="'openModalDataset'" :data="$modalDataset" :showCancelButton="false" :size="'sm'">
            <x-slot name="body">
                <div class="p-2 d-flex justify-content-center align-items-center">
                    <input class="border-0 p-1 rounded" type="text" name="search-dataset" id="search-dataset"
                        placeholder="cari.."><button class="border-0 p-1 rounded" type="submit"><i
                            class="bi bi-search text-success ms-1"></i></button>
                </div>
                <div class="mt-1 row p-2 overflow-x-auto body-dataset" style="max-height: 370px; scrollbar-width: none;">
                    <div class="col-12 text-success text-sm">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed text-success" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                                        aria-controls="collapseOne">
                                        <i class="bi bi-card-image me-2"></i>Dataset #1
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group overflow-y-auto">
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed text-success" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        <i class="bi bi-card-image me-2"></i>Dataset #2
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group overflow-y-auto">
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed text-success" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        <i class="bi bi-card-image me-2"></i>Dataset #3
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group overflow-y-auto">
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                            <li class="list-group-item"><a href="" class="d-flex"><i
                                                        class="bi bi-plus me-2"></i>Layer</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-modal>

    @endsection
    @push('scripts')
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script>
            var $m = jQuery.noConflict();
            $m(document).ready(function() {
                const sidebar = $m("#sidebar");
                const content = $m("#main-content");
                const toggleButtons = $m("#toggleSidebar, #anotherToggle");

                toggleButtons.on("click", function() {
                    sidebar.toggleClass("closed");
                    content.toggleClass("shifted");
                    toggleButtons.toggleClass("closed");
                });

                initMap('explorerMapId', 'osm', '', {
                    scale: true,
                    fullScreen: true,
                    zoomSlider: true
                }, {
                    dragPan: true,
                    mouseWheelZoom: false
                });

                // Fungsi untuk menampilkan modal
                $m(document).on('click', '[data-bs-target="#openModalDataset"]', function() {
                    $m('.modal-header').css({
                        cursor: 'move',
                        color: 'white',
                        backgroundColor: '#0fac81'
                    });
                    $m('.modal-title').attr('style', 'color: white !important');
                    $m('.modal-content').css({
                        'background': 'rgba(255, 255, 255, 0.5)',
                        'backdrop-filter': 'blur(3px)'
                    });

                    if (!($m('.modal.in').length)) {
                        $m('.modal-dialog').css({
                            top: 0,
                            left: 280
                        });
                    }
                    $m('#openModalDataset').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                    $m('.modal-backdrop').remove();

                    $m('.modal-dialog').draggable({
                        handle: ".modal-header"
                    });
                });
            });
        </script>
    @endpush
</x-guest-layout>
