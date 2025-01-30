<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalTambah = [
            'title' => 'Tambah Tautan',
            'footer' => '<button type="submit" class="btn btn-primary" form="addForm">Tambah</button>',
        ];

        $modalDelete = [
            'title' => 'Hapus Tautan',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];

        $modalUpdate = [
            'title' => 'Sunting Tautan',
            'footer' => '<button type="submit" class="btn btn-primary" form="updateForm">Simpan</button>',
        ];
    @endphp
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title text-primary">Pengaturan</h3>
                            <div class="nk-block-des text-soft">
                                <p>Pengaturan informasi dasar
                                    <strong
                                        class="text-primary">{{ config('app.name', 'Satu Peta Purwakarta') }}</strong>
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="container my-2 py-2">
                            <form action="{{ route('settings.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <h4>Dasar</h4>
                                    <h6>Ubah data untuk memperbarui informasi.</h6>
                                    <hr />
                                    <br>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Nama</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ $data->name }}" placeholder="masukkan name..">
                                                @error('name')
                                                    <span class="alert alert-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="form-label">No Telp</label>
                                                <input type="text" class="form-control" name="phone" id="phone"
                                                    value="{{ $data->phone }}" placeholder="masukkan nomor..">
                                                @error('phone')
                                                    <span class="alert alert-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="{{ $data->email }}" placeholder="masukkan email..">
                                                @error('email')
                                                    <span class="alert alert-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="about" class="form-label">Tentang</label>
                                                <textarea name="about" class="form-control" id="about">{{ $data->about }}</textarea>
                                                @error('about')
                                                    <span class="alert alert-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address" class="form-label">Alamat</label>
                                                <textarea type="text" name="address" id="address" class="form-control" placeholder="keterangan..">{{ $data->address }}</textarea>
                                                @error('address')
                                                    <span class="alert alert-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="logo" class="form-label">Logo</label>
                                                <div class="user-avatar mt-3">
                                                    @if ($data->logo)
                                                        @php
                                                            $logo = $data->logo;
                                                        @endphp
                                                        @if ($logo)
                                                            <img src="{{ Storage::url('logos/' . $logo) }}"
                                                                alt="Avatar Pengguna">
                                                        @else
                                                            <img src="{{ asset('assets/images/default.png') }}"
                                                                alt="Avatar Default">
                                                        @endif
                                                    @else
                                                        <img src="{{ asset('assets/images/default.png') }}"
                                                            alt="Avatar Default">
                                                    @endif
                                                </div>
                                                <input type="file" name="logo" class="form-control mt-4"
                                                    accept=".png, .jpg, .jpeg" max="2024">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-primary">
                                            Simpan
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- .card -->
                </div><!-- .nk-block -->
                {{-- <div class="container mt-5">
                    <div class="card shadow-lg mb-4">
                        <div class="card-header bg-white border-0">
                            <button
                                class="btn btn-link text-decoration-none w-100 text-start fw-bold text-primary py-3 px-4"
                                data-bs-toggle="collapse" data-bs-target="#accordionExample" aria-expanded="false"
                                aria-controls="accordionExample">
                                Atur Postingan
                            </button>
                        </div>
                        <div id="accordionExample" class="collapse" data-bs-parent=".faq--accordion">
                            <div class="card-body">
                                <form action="#" method="post">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="post" class="form-label">Embed Instagram</label>
                                        <textarea class="form-control" name="post" id="post" cols="30" rows="10"
                                            placeholder="Masukkan embed di sini...">{{ $app->post }}</textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="nk-block">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex flex-col items-left">
                                    <h5>Link Terkait</h5>
                                    <h8>Masukkan data untuk informasi link terkait.
                                    </h8>
                                </div>
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#addModal" class="btn btn-primary"><em
                                                class="icon ni ni-plus-round-fill mr-2"></em><span>Tambah</span></a>
                                    </div><!-- .toggle-wrap -->
                                </div>
                            </div>
                            <table class="datatable-init table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>URL</th>
                                        <th>Logo</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($relatedLinks as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->title }}</td>
                                            <td>{{ $row->url }}</td>
                                            <td>
                                                <div class="user-avatar">
                                                    @if ($row->documents->isNotEmpty())
                                                        @php
                                                            $logo = $row->documents->where('type', 'logo')->first();
                                                        @endphp
                                                        @if ($logo)
                                                            <img src="{{ Storage::url($logo->path) }}"
                                                                alt="Avatar Pengguna">
                                                        @else
                                                            <img src="{{ asset('assets/images/default.png') }}"
                                                                alt="Avatar Default">
                                                        @endif
                                                    @else
                                                        <img src="{{ asset('assets/images/default.png') }}"
                                                            alt="Avatar Default">
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $row->created_at }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#"
                                                        class="btn btn-sm btn-icon btn-trigger dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <em
                                                            class="icon ni ni-more-h rounded-full hover:bg-color-secondary hover:bg-opacity-30 hover:text-gray-500"></em>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="javascript:void(0);"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updateModal"
                                                                    data-id="{{ Crypt::encrypt($row->id) }}"
                                                                    data-name="{{ $row->title }}"
                                                                    data-url="{{ $row->url }}">
                                                                    <em
                                                                        class="icon ni ni-edit"></em><span>Sunting</span>
                                                                </a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteMapModal"
                                                                    data-id="{{ Crypt::encrypt($row->id) }}"
                                                                    data-name="{{ $row->title }}">
                                                                    <em
                                                                        class="icon ni ni-trash"></em><span>Delete</span>
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
        <x-modal :id="'addModal'" :data="$modalTambah">
            <x-slot name="body">
                <form id="addForm" method="POST" action="{{ route('settings.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-gs">
                        <div class="col-md-12">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required>

                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="masukkan nama.." required>
                                @error('title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Div untuk input Image -->
                            <div class="form-group mb-3">
                                <label class="form-label" for="logo">Logo</label>
                                <input type="file" name="file" id="logo" class="filepond">
                            </div>

                            <!-- Div untuk input URL Video -->
                            <div class="form-group mb-3">
                                <label class="form-label" for="url">URL</label>
                                <input type="url" name="url" class="form-control" id="url"
                                    placeholder="masukkan url.." required>
                            </div>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal :id="'updateModal'" :data="$modalUpdate">
            <x-slot name="body">
                <form id="updateForm" method="POST" action="{{ route('related.link.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-gs">
                        <!-- Input untuk id_user -->
                        <input type="hidden" name="id" id="id" value="" required>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required>

                        <!-- Form input nama -->
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="masukkan nama.." value="" required>
                                @error('title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Div untuk input Image -->
                            <div class="form-group mb-3">
                                <label class="form-label" for="logo-update">Logo</label>
                                <input type="file" name="file" id="logo-update" class="filepond">
                            </div>

                            <!-- Div untuk input URL Video -->
                            <div class="form-group mb-3">
                                <label class="form-label" for="url">URL</label>
                                <input type="url" name="url" class="form-control" id="url"
                                    placeholder="masukkan url..">
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

            $(document).on('click', '[data-bs-target="#updateModal"]', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                var name = $(this).data('name');

                $('#updateModal').find('input[name="id"]').val(id);
                $('#updateModal').find('input[name="title"]').val(name);
                $('#updateModal').find('input[name="url"]').val(url);
            });
        </script>
    @endpush
</x-app-layout>
