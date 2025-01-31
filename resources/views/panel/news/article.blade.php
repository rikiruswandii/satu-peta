<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDelete = [
            'title' => 'Hapus Artikel',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];
    @endphp
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title text-color-primary">Artikel </h3>
                            <div class="nk-block-des text-soft">
                                <p class="text-color-primary">Anda memiliki total {{ $count }}
                                    <strong>artikel</strong>
                                    .
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="{{ route('articles.create') }}" class="btn btn-primary"><em
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
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Gambar Mini</th>
                                        <th>Diterbitkan</th>
                                        <th>Diperbarui</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($articles as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->title }}</td>
                                            <td>{{ $row->category?->name }}</td>
                                            <td>
                                                <div class="user-avatar sq">
                                                    @if ($row->documents->isNotEmpty())
                                                        @php
                                                            $logo = $row->documents->where('documentable_id', $row->id)->first();
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
                                                            <li><a
                                                                    href="{{ route('articles.edit', ['id' => Crypt::encrypt($row->id)]) }}">
                                                                    <em
                                                                        class="icon ni ni-edit text-color-secondary"></em><span>Edit</span>
                                                                </a></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#deleteMapModal"
                                                                    data-id="{{ Crypt::encrypt($row->id) }}"
                                                                    data-name="{{ $row->title }}">
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
        </script>
    @endpush
</x-app-layout>
