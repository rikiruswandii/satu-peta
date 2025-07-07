<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDelete = [
            'title' => 'Hapus Layer',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];
        $modalAktivasi = [
            'title' => 'Aktivasi Layer',
            'footer' => '<button type="submit" class="btn btn-primary" form="aktivasiMapForm">Aktifkan</button>',
        ];
        $modalDeaktivasi = [
            'title' => 'Deaktivasi Layer',
            'footer' => '<button type="submit" class="btn btn-primary" form="deaktivasiMapForm">Deaktivasi</button>',
        ];
        $modalDetail = [
            'title' => 'Lihat Layer',
            'footer' => '',
        ];
        $modalTambah = [
            'title' => 'Tambah Layer',
            'footer' => '<button type="submit" class="btn btn-primary" form="addMapForm">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    <span>Tambah</span>
</button>',
        ];
        $modalEdit = [
            'title' => 'Sunting Peta',
            'footer' => '<button type="submit" class="btn btn-primary" form="editMapForm">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    <span>Simpan</span>
</button>',
        ];
    @endphp
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title text-primary">Peta</h3>
                            <div class="nk-block-des text-soft">
                                <p>Anda memiliki total
                                    <strong class="text-primary"> {{ $count }} peta</strong>
                                    .
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addMapModal"
                                    class="btn btn-primary"><em
                                        class="icon ni ni-plus-round-fill mr-2"></em><span>Tambah</span></a>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner">
                            <div class="table-responsive">
                                <table class="table table-striped" style="width:100%" id="maps-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Grup</th>
                                            <th>Kategori</th>
                                            <th>File</th>
                                            <th>Aktif</th>
                                            <th>Diperbarui</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>

    @section('modal')
        <x-modal :id="'addMapModal'" :data="$modalTambah">
            <x-slot name="body">
                <form id="addMapForm" method="POST" action="{{ route('maps.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-gs">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="Masukkan nama..">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="file">Geojson <span
                                        class="text-danger">*</span></label><br>
                                <span>Masukkan file JSON/geoJSON disini.</span>
                                <div class="form-control-wrap">
                                    <input type="file" class="filepond @error('file') is-invalid @enderror"
                                        name="file" id="file"
                                        accept="application/json,application/geo+json,application/vnd.geo+json,.geojson,.kmz"
                                        required>
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="regional_agency_id">Grup <span
                                        class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select class="form-select js-select2 @error('regional_agency_id') is-invalid @enderror"
                                        data-search="on" data-dropdown-parent="#addMapModal" name="regional_agency_id"
                                        id="regional_agency_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Grup</option>
                                        @foreach ($regional_agencies as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                @error('regional_agency_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="sector_id">Kategori <span
                                        class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select class="form-select js-select2 @error('sector_id') is-invalid @enderror"
                                        data-search="on" data-dropdown-parent="#addMapModal" name="tag" id="sector_id">
                                        <option value="Pilih Kategori" disabled>Pilih Kategori</option>
                                        @foreach ($sectors as $d)
                                            <option value="{{ $d->name }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sector_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="can_download" value="1" class="custom-control-input"
                                        id="can_download" {{ old('can_download') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="can_download">Izinkan unduhan untuk layer
                                        ini</label>
                                </div>
                                @error('can_download')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
        <x-modal :id="'editMapModal'" :data="$modalEdit">
            <x-slot name="body">
                <form id="editMapForm" method="POST" action="{{ route('maps.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-gs">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="edit-name">Nama <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="edit-name" value="{{ old('name') }}" required
                                        placeholder="Masukkan nama..">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="edit-file">Geojson
                                    {{-- <span class="text-danger">*</span> --}}
                                </label><br>
                                <span>Masukkan file JSON/geoJSON disini.</span>
                                <div class="form-control-wrap">
                                    <input type="file" class="filepond @error('file') is-invalid @enderror"
                                        name="file" id="edit-file" accept="application/json">
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="edit_regional_agency_id">Grup <span
                                        class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select
                                        class="form-select js-select2 @error('regional_agency_id') is-invalid @enderror"
                                        data-search="on" data-dropdown-parent="#editMapModal" name="regional_agency_id"
                                        id="edit_regional_agency_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Grup</option>
                                        @foreach ($regional_agencies as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('regional_agency_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="edit_sector_id">Kategori <span
                                        class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select class="form-select js-select2 @error('sector_id') is-invalid @enderror"
                                        data-search="on" data-dropdown-parent="#editMapModal" name="tag"
                                        id="edit_sector_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Kategori</option>
                                        @foreach ($sectors as $d)
                                            <option value="{{ $d->name }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sector_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
        <x-modal :id="'detailMapModal'" :data="$modalDetail" :size="'xl'" :cancelButtonText="'Tutup'">
            <x-slot name="body">
                <x-map-container geoJsonPath="" mapId="detailMap" />
                <table class="table table-sm table-bordered" style="width: 100%; font-size: 0.8rem;">
                    <tr>
                        <td>Nama</td>
                        <td id="map-name"></td>
                    </tr>
                    <tr>
                        <td>Grup</td>
                        <td id="map-regional-agency"></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td id="map-sector"></td>
                    </tr>
                </table>
            </x-slot>
        </x-modal>
        <x-modal :id="'aktivasiMapModal'" :data="$modalAktivasi">
            <x-slot name="body">
                <form method="POST" id="aktivasiMapForm" action="{{ route('maps.activate') }}">
                    @csrf
                    <div class="row g-gs">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Apakah Anda yakin ingin mengaktifkan layer <strong
                                        id="name-map-activated"></strong></span>
                            </div>
                            <input type="hidden" name="id" value="">
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
        <x-modal :id="'deaktivasiMapModal'" :data="$modalDeaktivasi">
            <x-slot name="body">
                <form method="POST" id="deaktivasiMapForm" action="{{ route('maps.activate') }}">
                    @csrf
                    <div class="row g-gs">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Apakah Anda yakin ingin menonaktifkan layer <strong
                                        id="name-map-deactivated"></strong></span>
                            </div>
                            <input type="hidden" name="id" value="">
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
        @include('panel.partials.delete')
    @endsection

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

            #popup.ol-popup {
                display: none !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            var $r = jQuery.noConflict();
            $r(document).ready(function() {
                var searchValue = new URLSearchParams(window.location.search).get('search') || '';

                var table = $r('#maps-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('maps.datatable') }}",
                        data: function(d) {
                            d.search = $r('#search-maps').val() || searchValue;
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'regional_agency.name',
                            name: 'regional_agency.name'
                        },
                        {
                            data: 'tags',
                            name: 'tags'
                        },
                        {
                            data: 'download',
                            name: 'download',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'checkbox',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        "lengthMenu": "Tampilkan _MENU_ data per halaman",
                        "zeroRecords": "Tidak ditemukan data yang sesuai",
                        "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                        "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                        "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                        "search": "Cari:",
                        "emptyTable": "Tidak ada data yang tersedia",
                        "loadingRecords": "Memuat...",
                        "aria": {
                            "sortAscending": ": aktifkan untuk mengurutkan kolom secara menaik",
                            "sortDescending": ": aktifkan untuk mengurutkan kolom secara menurun"
                        }
                    }
                });

                // Jika ada pencarian dari URL, langsung reload DataTables
                if (searchValue) {
                    table.ajax.reload();
                }

                // Jika user mengetik di input search di halaman Maps
                $r('#search-maps').on('keyup', function() {
                    table.ajax.reload();
                });
            });

            $r(document).on('click', '[data-bs-target="#deleteMapModal"]', function() {
                var id = $r(this).data('id');
                $r('#deleteMapModal').find('input[name="id"]').val(id);
                var name = $r(this).data('name');
                $r('#nameAccount').text(name);
            });

            $r(document).on('click', '[data-bs-target="#aktivasiMapModal"]', function() {
                var id = $r(this).data('id');
                $r('#aktivasiMapModal').find('input[name="id"]').val(id);
                var name = $r(this).data('name');
                $r('#name-map-activated').text(name);
            });
            $r(document).on('click', '[data-bs-target="#deaktivasiMapModal"]', function() {
                var id = $r(this).data('id');
                $r('#deaktivasiMapModal').find('input[name="id"]').val(id);
                var name = $r(this).data('name');
                $r('#name-map-deactivated').text(name);
            });

            $r(document).ready(function() {
                $r("#addMapForm").on("submit", function() {
                    let submitButton = $r("button[form='addMapForm']");
                    submitButton.prop("disabled", true); // Nonaktifkan tombol saat submit
                    submitButton.find(".spinner-border").show(); // Tampilkan spinner
                    submitButton.find("span:last-child").hide(); // Sembunyikan teks tombol
                });
            });

            $r(document).on('click', '[data-bs-target="#editMapModal"]', function() {
                var id = $r(this).data('id');
                var regional_agency = $r(this).data('regional-agency');
                var sector = $r(this).data('sector');
                var name = $r(this).data('name');

                var modal = $r('#editMapModal');

                modal.find('input[name="id"]').val(id);
                modal.find('input[name="name"]').val(name);
                modal.find('select[name="tag"]').val(sector[0]).trigger('change');
                modal.find('select[name="regional_agency_id"]').val(regional_agency).trigger('change');
            });

            $r(document).on('click', '[data-bs-target="#detailMapModal"]', function() {
                var currentMap = null;
                var path = $r(this).data('geojson');
                var regional_agency = $r(this).data('regional-agency');
                var sector = $r(this).data('sector');
                var name = $r(this).data('name');
                console.log(name);
                console.log(regional_agency);
                console.log(sector);

                var modal = $('#detailMapModal');
                if (!modal) {
                    console.error("Modal tidak ditemukan");
                    return;
                }

                modal.find('td[id="map-name"]').html(name);
                modal.find('td[id="map-regional-agency"]').html(regional_agency);
                modal.find('td[id="map-sector"]').html(sector);

                if (!path) {
                    console.error('GeoJSON path tidak ditemukan.');
                    return;
                }

                var mapContainer = $r('#detailMapModal').find('x-map-container');
                mapContainer.attr('geoJsonPath', path);

                // Hapus peta yang ada jika ada
                if (currentMap) {
                    currentMap.setTarget(null);
                    currentMap = null;
                }

                // Bersihkan container peta
                $r('#detailMap').empty();

                var mapElement = document.getElementById('detailMap');
                if (mapElement) {
                    mapElement.innerHTML = ''; // Kosongkan elemen peta

                    // Tambahkan overlay ke elemen peta
                    var overlay = document.createElement('div');
                    overlay.className = 'loading-overlay';
                    overlay.innerHTML =
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                    mapElement.appendChild(overlay);

                    currentMap = initMap('detailMap', path); // Inisialisasi peta baru dengan path GeoJSON

                    // Tunggu hingga peta selesai dimuat
                    currentMap.once('rendercomplete', function() {
                        // Hapus overlay setelah peta selesai dimuat
                        overlay.remove();
                    });
                } else {
                    console.error('Elemen peta tidak ditemukan.');
                    return;
                }
            });
        </script>
    @endpush
</x-app-layout>
