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
                            <table class="table table-striped" style="width:100%" id="articles-table">
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
             var $r = jQuery.noConflict();
            $r(document).ready(function() {
                $r('#articles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('articles.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'category.name',
                            name: 'category.name'
                        },
                        {
                            data: 'thumbnail',
                            name: 'thumbnail',
                            orderable: false,
                            searchable: false
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

            $r(document).on('click', '[data-bs-target="#deleteMapModal"]', function() {
                var userId = $r(this).data('id');
                $r('#deleteMapModal').find('input[name="id"]').val(userId);
                var userName = $r(this).data('name');
                $r('#nameAccount').text(userName);
            });
        </script>
    @endpush
</x-app-layout>
