<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDelete = [
            'title' => 'Hapus Peta',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];
        $modalTambah = [
            'title' => 'Tambah Peta',
            'footer' => '<button type="submit" class="btn btn-primary" form="addMapForm">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    <span>Tambah</span>
</button>',
        ];
        $modalTambahRegionalAgency = [
            'title' => 'Tambah Grup',
            'footer' => '<button type="submit" class="btn btn-primary" form="addRegionalAgencyForm">Tambah</button>',
        ];
        $modalTambahSector = [
            'title' => 'Tambah Sektor',
            'footer' => '<button type="submit" class="btn btn-primary" form="addSectorForm">Tambah</button>',
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
                                        <th>Perangkat Daerah</th>
                                        <th>Sektor</th>
                                        <th>Diterbitkan</th>
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
                                            <td>{{ $row->created_at }}</td>
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
                                                            <li><a href="javascript:void(0);">
                                                                    <em
                                                                        class="icon ni ni-edit text-color-secondary"></em><span>Edit</span>
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
                                <label class="form-label" for="file">SHP <span class="text-danger">*</span></label><br>
                                <span>Masukkan file .shp disini.</span>
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
                                <label class="form-label" for="sector_id">Sektor <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select class="form-select js-select2 @error('sector_id') is-invalid @enderror"
                                        data-search="on" data-dropdown-parent="#addMapModal" name="sector_id"
                                        id="sector_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Sektor</option>
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

        <x-modal :id="'addRegionalAgencyModal'" :data="$modalTambahRegionalAgency">
            <x-slot name="body">
                <form id="addRegionalAgencyForm" method="POST" action="{{ route('regional.agency.store') }}">
                    @csrf
                    <div class="row g-gs">
                        <div class="col-md-12">
                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama</label>
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
                    </div>
                </form>
            </x-slot>
        </x-modal>
        <x-modal :id="'addSectorModal'" :data="$modalTambahSector">
            <x-slot name="body">
                <form id="addSectorForm" method="POST" action="{{ route('sector.store') }}">
                    @csrf
                    <div class="row g-gs">
                        <div class="col-md-12">
                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama</label>
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
                    </div>
                </form>
            </x-slot>
        </x-modal>

        @include('panel.partials.delete')
    @endsection

    @push('scripts')
        <script>
            $(document).on('click', '[data-bs-target="#deleteMapModal"]', function() {
                var userId = $(this).data('id');
                $('#deleteMapModal').find('input[name="id"]').val(userId);
                var userName = $(this).data('name');
                $('#nameAccount').text(userName);
            });

            $(document).ready(function() {
                $("#addMapForm").on("submit", function() {
                    let submitButton = $("button[form='addMapForm']");
                    submitButton.prop("disabled", true); // Nonaktifkan tombol saat submit
                    submitButton.find(".spinner-border").show(); // Tampilkan spinner
                    submitButton.find("span:last-child").hide(); // Sembunyikan teks tombol
                });
            });
        </script>
    @endpush
</x-app-layout>
