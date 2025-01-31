<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
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
                            <h3 class="nk-block-title page-title text-primary">{{ __('Tambah Peta') }}</h3>
                            <div class="nk-block-des text-soft">
                                <p class="text-sm">Masukkan form data
                                    <strong class="text-primary">{{ __('peta') }}</strong> yang ingin Anda simpan.
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <a href="{{ route('maps') }}" class="btn btn-secondary">
                                <em class="icon ni ni-chevron-left-circle-fill"></em><span>Kembali</span></a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="col-12 bg-white rounded-md">
                        <form action="{{ route('maps.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Nama <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-control-wrap">
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    name="name" id="name" value="{{ old('name') }}" required
                                                    placeholder="Masukkan nama..">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="file">SHP <span
                                                    class="text-danger">*</span></label><br>
                                            <span>Masukkan file .shp disini.</span>
                                            <div class="form-control-wrap">
                                                <input type="file"
                                                    class="filepond @error('file') is-invalid @enderror" name="file"
                                                    id="file" accept="image/jpeg, image/png" required>
                                                @error('file')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="regional_agency_id">Grup <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-control-wrap d-flex align-items-center gap-2">
                                                <select
                                                    class="form-select js-select2 @error('regional_agency_id') is-invalid @enderror"
                                                    data-search="on" data-dropdown-parent="#addUserModal"
                                                    name="regional_agency_id" id="regional_agency_id">
                                                    <option value="Pilih Hak Akses" disabled>Pilih Grup</option>
                                                    @foreach ($regional_agencies as $d)
                                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                    @endforeach
                                                </select>

                                                <!-- Tombol Tambah Kategori -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addRegionalAgencyModal">
                                                    <i class="ni ni-plus"></i>
                                                </button>
                                            </div>
                                            @error('regional_agency_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="sector_id">Sektor <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-control-wrap  d-flex align-items-center gap-2">
                                                <select
                                                    class="form-select js-select2 @error('sector_id') is-invalid @enderror"
                                                    data-search="on" data-dropdown-parent="#addUserModal"
                                                    name="sector_id" id="sector_id">
                                                    <option value="Pilih Hak Akses" disabled>Pilih Sektor</option>
                                                    @foreach ($sectors as $d)
                                                        <option value="{{ $d->id }}">{{ $d->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <!-- Tombol Tambah Kategori -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addSectorModal">
                                                    <i class="ni ni-plus"></i>
                                                </button>
                                            </div>
                                            @error('sector_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
    @section('modal')
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
    @endsection
</x-app-layout>
