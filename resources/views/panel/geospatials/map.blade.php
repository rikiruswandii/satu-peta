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
                            <h3 class="nk-block-title page-title text-color-primary">Peta</h3>
                            <div class="nk-block-des text-soft">
                                <p class="text-color-primary">Anda memiliki total {{ $count }}
                                    <strong>peta</strong>
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
                            <table class="datatable-init table">
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
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($maps as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->regional_agency?->name }}</td>
                                            <td>{{ $row->sector?->name }}</td>
                                            @if ($row->documents->isNotEmpty())
                                                @foreach ($row->documents as $document)
                                                    <td>
                                                        <a
                                                            href="{{ route('maps.download', ['map' => Crypt::encrypt($row->id), 'id' => Crypt::encrypt($document->id)]) }}">
                                                            <span class="badge rounded-pill bg-primary"><em
                                                                    class="icon ni ni-download-cloud"></em>Unduh</span>
                                                        </a>
                                                    </td>
                                                @endforeach
                                            @else
                                                <td><span class="badge bg-secondary">Tidak ada dokumen</span></td>
                                            @endif
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customCheck1"
                                                        {{ $row->is_active === true ? 'checked' : '' }} disabled>
                                                    <label class="custom-control-label" for="customCheck1">
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{{ $row->updated_at }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#"
                                                        class="btn btn-sm btn-icon btn-trigger dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <em class="icon ni ni-more-h rounded-full"></em>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#detailMapModal"
                                                                    data-regional-agency="{{ $row->regional_agency?->name }}"
                                                                    data-sector="{{ $row->sector?->name }}"
                                                                    data-geojson="{{ optional($row->documents)->first()->path ? Storage::url($row->documents->first()->path) : '' }}"
                                                                    data-name="{{ $row->name }}"
                                                                    data-id="{{ $row->id }}">
                                                                    <em class="icon ni ni-eye"></em><span>Lihat</span>
                                                                </a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#editMapModal"
                                                                    data-regional-agency="{{ $row->regional_agency?->id }}"
                                                                    data-sector="{{ $row->sector?->id }}"
                                                                    data-name="{{ $row->name }}"
                                                                    data-id="{{ $row->id }}">
                                                                    <em class="icon ni ni-edit"></em><span>Edit</span>
                                                                </a></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="{{ $row->is_active === false ? '#aktivasiMapModal' : '#deaktivasiMapModal'}}"
                                                                    data-name="{{ $row->name }}"
                                                                    data-id="{{ Crypt::encrypt($row->id) }}">
                                                                    <em
                                                                        class="icon ni {{ $row->is_active === false ? 'ni-check-round' : 'ni-cross-round' }} "></em><span>{{ $row->is_active === false ? 'Aktivasi' : 'Deaktivasi' }}</span>
                                                                </a></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#deleteMapModal"
                                                                    data-id="{{ Crypt::encrypt($row->id) }}"
                                                                    data-name="{{ $row->name }}">
                                                                    <em
                                                                        class="icon ni ni-trash text-red-500"></em><span>Delete</span>
                                                                </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                        name="file" id="file" accept="application/json" required>
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
                                    <select
                                        class="form-select js-select2 @error('regional_agency_id') is-invalid @enderror"
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
                                        data-search="on" data-dropdown-parent="#addMapModal" name="sector_id"
                                        id="sector_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Kategori</option>
                                        @foreach ($sectors as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
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
                                    <input type="checkbox" name="can_download" value="1" class="custom-control-input" id="can_download" {{ old('can_download') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="can_download">Izinkan unduhan untuk layer ini</label>
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
                                        name="file" id="edit-file" accept="application/json" required>
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
                                        data-search="on" data-dropdown-parent="#editMapModal" name="sector_id"
                                        id="edit_sector_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Kategori</option>
                                        @foreach ($sectors as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
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
                                <span>Apakah Anda yakin ingin mengaktifkan layer <strong id="name-map-activated"></strong></span>
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
                                <span>Apakah Anda yakin ingin menonaktifkan layer <strong id="name-map-deactivated"></strong></span>
                            </div>
                            <input type="hidden" name="id" value="">
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        @include('panel.partials.delete')
    @endsection

    @push('scripts')
        <script>
            $(document).on('click', '[data-bs-target="#deleteMapModal"]', function() {
                var id = $(this).data('id');
                $('#deleteMapModal').find('input[name="id"]').val(id);
                var name = $(this).data('name');
                $('#nameAccount').text(name);
            });

            $(document).on('click', '[data-bs-target="#aktivasiMapModal"]', function() {
                var id = $(this).data('id');
                $('#aktivasiMapModal').find('input[name="id"]').val(id);
                var name = $(this).data('name');
                $('#name-map-activated').text(name);
            });
            $(document).on('click', '[data-bs-target="#deaktivasiMapModal"]', function() {
                var id = $(this).data('id');
                $('#deaktivasiMapModal').find('input[name="id"]').val(id);
                var name = $(this).data('name');
                $('#name-map-deactivated').text(name);
            });

            $(document).ready(function() {
                $("#addMapForm").on("submit", function() {
                    let submitButton = $("button[form='addMapForm']");
                    submitButton.prop("disabled", true); // Nonaktifkan tombol saat submit
                    submitButton.find(".spinner-border").show(); // Tampilkan spinner
                    submitButton.find("span:last-child").hide(); // Sembunyikan teks tombol
                });
            });

            $(document).on('click', '[data-bs-target="#editMapModal"]', function() {
                var id = $(this).data('id');
                var regional_agency = $(this).data('regional-agency');
                var sector = $(this).data('sector');
                var name = $(this).data('name');

                var modal = $('#editMapModal');

                modal.find('input[name="id"]').val(id);
                modal.find('input[name="name"]').val(name);
                modal.find('select[name="sector_id"]').val(sector).trigger('change');
                modal.find('select[name="regional_agency_id"]').val(regional_agency).trigger('change');
            });

            $(document).on('click', '[data-bs-target="#detailMapModal"]', function() {
                var path = $(this).data('geojson');
                var regional_agency = $(this).data('regional-agency');
                var sector = $(this).data('sector');
                var name = $(this).data('name');
                console.log(name); // tidak kosong
                console.log(regional_agency); // tidak kosong
                console.log(sector); // tidak kosong

                var modal = $('#detailMapModal');

                modal.find('td[id="map-name"]').html(name);
                modal.find('td[id="map-regional-agency"]').html(regional_agency);
                modal.find('td[id="map-sector"]').html(sector);

                // Pastikan nilai path tidak kosong
                if (!path) {
                    console.error('GeoJSON path tidak ditemukan.');
                    return;
                }

                // Temukan elemen x-map-container dan perbarui atributnya
                var mapContainer = $('#detailMapModal').find('x-map-container');
                mapContainer.attr('geoJsonPath', path);

                // Perbarui peta dengan path baru
                initMap('detailMap', 'osm', path, [], []);
            });
        </script>
    @endpush
</x-app-layout>
