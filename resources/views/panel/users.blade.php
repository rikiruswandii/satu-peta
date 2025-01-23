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
            <table class="datatable-init table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Hak Akses</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->email }}</td>
                            <td>{{ $d->role->name }}</td>
                            <td>
                                <div class="drodown">
                                    <a href="#" class="btn btn-sm btn-icon btn-trigger dropdown-toggle"
                                        data-bs-toggle="dropdown"><em
                                            class="icon ni ni-more-h rounded-full hover:!bg-color-secondary hover:!bg-opacity-30 hover:!text-gray-500"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route('user.detail', ['id' => Crypt::encrypt($d->id)]) }}"><em
                                                        class="icon ni ni-eye text-blue-500"></em><span>View
                                                        Details</span></a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#resetPasswordModal"><em
                                                        class="icon ni ni-shield-star text-color-secondary"></em><span>Reset
                                                        Pass</span></a></li>
                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal" data-id="{{ $d->id }}"
                                                    data-name="{{ $d->name }}"><em
                                                        class="icon ni ni-trash text-red-500"></em><span>Delete
                                                        User</span></a></li>
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
    @php
        $modalTambah = [
            'title' => 'Tambah Pengguna Baru',
            'footer' => '<button type="submit" class="btn btn-primary" form="addUserForm">Tambah</button>',
        ];

        $modalDelete = [
            'title' => 'Hapus Pengguna',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteUserForm">Hapus</button>',
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

        <x-modal :id="'deleteUserModal'" :data="$modalDelete">
            <x-slot name="body">
                <form method="DELETE" id="deleteUserForm" action="{{ route('users.destroy') }}">
                    @csrf

                    <div class="row g-gs">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Apakah kamu yakin ingin menghapus akun dengan nama : <strong
                                        id="nameAccount"></strong> ?</span>
                            </div>
                            <input type="hidden" name="id" value="">
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal :id="'resetPasswordModal'" :data="$modalReset">
            <x-slot name="body">
                <form id="resetPasswordForm" method="POST" action="{{ route('users.reset') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right form-label">{{ __('Alamat Email') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email" required
                                autocomplete="email" autofocus placeholder="Masukkan alamat email..">

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
            $(document).on('click', '[data-bs-target="#deleteUserModal"]', function() {
                var userId = $(this).data('id');
                $('#deleteUserModal').find('input[name="id"]').val(userId);

                var userName = $(this).data('name');
                $('#nameAccount').text(userName);
            });
        </script>
    @endpush
</x-app-layout>
