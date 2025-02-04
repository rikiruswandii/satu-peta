<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title !text-color-primary">Data Pengguna</h3>
                <div class="nk-block-des text-soft">
                    <p class="text-color-primary">Anda memiliki total <strong>{{ $count }} pengguna</strong> .
                    </p>
                </div>
            </div><!-- .nk-block-head-content -->
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addUserModal"
                        class="btn btn-primary text-light">
                        <em class="icon ni ni-plus-round-fill"></em><span>Add User</span>
                    </a>
                </div><!-- .toggle-wrap -->
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="card card-bordered card-preview">
        <div class="card-inner">
            <table class="table table-striped" style="width:100%" id="user-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Hak Akses</th>
                        <th>Dibuat</th>
                        <th>Diperbarui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div><!-- .card -->
    @php
        $modalTambah = [
            'title' => 'Tambah Pengguna Baru',
            'footer' => '<button type="submit" class="btn btn-primary" form="addUserForm">Tambah</button>',
        ];

        $modalDelete = [
            'title' => 'Hapus Pengguna',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];
        $modalReset = [
            'title' => 'Mengatur Ulang kata Sandi',
            'footer' =>
                '<button type="submit" class="btn btn-primary" form="resetPasswordForm">Mengatur Ulang</button>',
        ];
    @endphp
    @section('modal')
        <x-modal :id="'addUserModal'" :data="$modalTambah">
            <x-slot name="body">
                <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="row g-gs">
                        <div class="col-md-12">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <div class="form-control-wrap">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" id="email" value="{{ old('email') }}" required
                                        placeholder="Masukkan nama..">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="role_id">Hak Akses Pengguna</label>
                                <div class="form-control-wrap">
                                    <select class="form-select js-select2 @error('role_id') is-invalid @enderror"
                                        data-search="on" data-dropdown-parent="#addUserModal" name="role_id" id="role_id">
                                        <option value="Pilih Hak Akses" disabled>Pilih Hak Akses</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role_id')
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

        @include('panel.partials.delete')

        <x-modal :id="'resetPasswordModal'" :data="$modalReset">
            <x-slot name="body">
                <form id="resetPasswordForm" method="POST" action="{{ route('users.reset') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right form-label">{{ __('Alamat Email') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" required autocomplete="email" autofocus
                                placeholder="Masukkan alamat email..">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#user-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.datatable') }}",
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
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'role.name',
                            name: 'role.name'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
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

            $(document).on('click', '[data-bs-target="#deleteMapModal"]', function() {
                var userId = $(this).data('id');
                $('#deleteMapModal').find('input[name="id"]').val(userId);

                var userName = $(this).data('name');
                $('#nameAccount').text(userName);
            });
        </script>
    @endpush
</x-app-layout>
