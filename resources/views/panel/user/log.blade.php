<x-user :user="$user" :title="$title" :description="$description">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head nk-block-head-lg">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Informasi Aktivitas</h4>
                    <div class="nk-block-des">
                        <p>Info dasar, seperti <span class="font-semibold">aktivitas
                                pengguna</span>,
                            yang Anda gunakan pada
                            {{ config('app.name', 'Satu Peta Purwakarta') }}
                            <br>Anda memiliki <strong>total {{ $count }}</strong>
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
        <div class="nk-block">
            <div class="card card-bordered card-preview">
                <div class="card-inner">
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
            </div><!-- .card -->
        </div><!-- .nk-block -->
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#user-log-table').DataTable({
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
                    ]
                });
            });
        </script>
    @endpush
</x-user>
