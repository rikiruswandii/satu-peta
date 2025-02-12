<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDelete = [
            'title' => 'Hapus Kategori',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];
        $modalEdit = [
            'title' => 'Sunting Kategori',
            'footer' => '<button type="submit" class="btn btn-primary" form="editGroupForm">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    <span>Simpan</span>
</button>',
        ];
        $modalTambahSector = [
            'title' => 'Tambah Kategori',
            'footer' => '<button type="submit" class="btn btn-primary" form="addSectorForm">Tambah</button>',
        ];
    @endphp
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title text-color-primary">Kategori</h3>
                            <div class="nk-block-des text-soft">
                                <p class="text-color-primary">Anda memiliki total {{ $count }}
                                    <strong>kategori</strong>
                                    .
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addSectorModal"
                                    class="btn btn-primary"><em
                                        class="icon ni ni-plus-round-fill mr-2"></em><span>Tambah</span></a>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner">
                            <table class="table table-striped" style="width:100%" id="datasets-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th></th>
                                        <th>Diperbarui</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>

    @section('modal')
        <x-modal :id="'editGroupModal'" :data="$modalEdit">
            <x-slot name="body">
                <form id="editGroupForm" method="POST" action="{{ route('datasets.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-gs">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="Masukkan nama grup..">
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
                <form id="addSectorForm" method="POST" action="{{ route('datasets.store') }}">
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
             var $r = jQuery.noConflict();
             $r(document).ready(function() {
                $r('#datasets-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('datasets.datatable') }}",
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
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });

            $r(document).on('click', '[data-bs-target="#deleteMapModal"]', function() {
                var userId = $r(this).data('id');
                $r('#deleteMapModal').find('input[name="id"]').val(userId);
                var userName = $r(this).data('name');
                $r('#nameAccount').text(userName);
            });

            $r(document).ready(function() {
                $r("#addSectorForm").on("submit", function() {
                    let submitButton = $r("button[form='addSectorForm']");
                    submitButton.prop("disabled", true); // Nonaktifkan tombol saat submit
                    submitButton.find(".spinner-border").show(); // Tampilkan spinner
                    submitButton.find("span:last-child").hide(); // Sembunyikan teks tombol
                });
            });

            $r(document).on('click', '[data-bs-target="#editGroupModal"]', function() {
                var id = $r(this).data('id');
                var name = $r(this).data('name');

                var modal = $r('#editGroupModal');

                modal.find('input[name="id"]').val(id);
                modal.find('input[name="name"]').val(name);
            });
        </script>
    @endpush
</x-app-layout>
