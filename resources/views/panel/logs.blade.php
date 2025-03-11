<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title text-primary">Aktivitas Pengguna</h3>
                <div class="nk-block-des text-soft">
                    <p>Terdapat total <strong class="text-primary">{{ $count }} aktivitas pengguna</strong>
                        .
                    </p>
                </div>
            </div><!-- .nk-block-head-content -->
            <div class="nk-block-head-content">
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="card card-bordered card-preview">
        <div class="card-inner">
            <div class="table-responsive">
                <table class="table table-striped" style="width:100%" id="logs-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Log</th>
                            <th>Deskripsi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- .card -->
    @push('scripts')
        <script>
            var $r = jQuery.noConflict();
            $r(document).ready(function() {
                $r('#logs-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('logs.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'log_name',
                            name: 'log_name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
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
        </script>
    @endpush
</x-app-layout>
