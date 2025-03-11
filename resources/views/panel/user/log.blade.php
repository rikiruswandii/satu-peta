<x-user :user="$user" :title="$title" :description="$description">
    <div class="card">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head nk-block-head-lg">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title text-primary">Informasi Aktivitas</h4>
                        <div class="nk-block-des">
                            <p>Info dasar, seperti <span class="font-semibold text-primary">aktivitas
                                    pengguna</span>,
                                yang Anda gunakan pada
                                {{ config('app.name', 'Satu Peta Purwakarta') }}
                                <br>Anda memiliki <strong class="text-primary">total {{ $count }}</strong>
                                aktivitas.
                            </p>
                        </div>
                    </div>
                    <div class="nk-block-head-content align-self-start d-lg-none">
                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em
                                class="icon ni ni-menu-alt-r"></em></a>
                    </div>
                </div>
            </div><!-- .nk-block-head -->
            <div class="card-bordered">
                <div class="table-responsive">
                    <table class="table table-striped" style="width:100%" id="user-log-table">
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
        </div><!-- .nk-block -->
    </div>
    @push('scripts')
        <script>
            var $r = jQuery.noConflict();
            $r(document).ready(function() {
                $r('#user-log-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('user.datatable', ['id' => $encrypt]) }}",
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
</x-user>
