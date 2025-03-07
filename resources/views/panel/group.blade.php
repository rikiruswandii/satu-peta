<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    @php
        $modalDelete = [
            'title' => 'Hapus Grup',
            'footer' => '<button type="submit" class="btn btn-danger" form="deleteForm">Hapus</button>',
        ];
        $modalEdit = [
            'title' => 'Sunting Grup',
            'footer' => '<button type="submit" class="btn btn-primary" form="editGroupForm">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    <span>Simpan</span>
</button>',
        ];
        $modalTambahRegionalAgency = [
            'title' => 'Tambah Grup',
            'footer' => '<button type="submit" class="btn btn-primary" form="addRegionalAgencyForm">Tambah</button>',
        ];
    @endphp
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title text-primary">Grup</h3>
                            <div class="nk-block-des text-soft">
                                <p>Anda memiliki total
                                    <strong class="text-primary">{{ $count }} grup</strong>
                                    .
                                </p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="javascript:void(0);" id="syncBtn" class="btn btn-primary">
                                    <span class="spinner-border spinner-border-sm" id="spinner" role="status"
                                        aria-hidden="true" style="display: none;"></span>
                                    <span id="processText" style="display: none;">Proses...</span>
                                    <em class="icon ni ni-reload-alt mr-2" id="reloadIcon"></em>
                                    <span id="syncText">Sinkronisasi</span>
                                </a>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner">
                            <table class="table table-striped" style="width:100%" id="groups-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Grup</th>
                                        <th>Diperbarui</th>
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

    @push('scripts')
        <script>
            var $r = jQuery.noConflict();
            $r(document).ready(function() {
                $r('#groups-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('groups.datatable') }}",
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
            });

            $r(document).ready(function() {
                $r('#syncBtn').click(function() {
                    console.log('Tombol diklik!'); // Debugging

                    // Tampilkan spinner & sembunyikan ikon reload
                    $r('#spinner, #processText').show();
                    $r('#reloadIcon, #syncText').hide();

                    // Kirim request AJAX
                    $r.ajax({
                        url: '{{ route('groups.sync') }}',
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Response:', response); // Debugging

                            // Tampilkan alert sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Sinkronisasi berhasil.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            console.error('AJAX Error:', xhr); // Debugging
                            let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                                'Terjadi kesalahan.';

                            // Tampilkan alert error
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                timer: 4000,
                                showConfirmButton: false
                            });
                        },
                        complete: function() {
                            // Sembunyikan spinner & tampilkan ikon reload
                            $r('#spinner, #processText').hide();
                            $r('#reloadIcon, #syncText').show();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
