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
            <x-map-container geoJsonPath="" mapId="explorerMapId" :height="'91vh'" />
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

    @endsection
    @push('scripts')
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        @vite('resources/js/explorer.js')
    @endpush
    @push('js')
        
    @endpush
</x-guest-layout>
