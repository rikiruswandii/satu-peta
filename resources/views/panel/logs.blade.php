<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title !text-color-primary">Aktivitas Pengguna</h3>
                <div class="nk-block-des text-soft">
                    <p class="text-color-primary">Terdapat total <strong>{{ $count }} aktivitas pengguna</strong> .
                    </p>
                </div>
            </div><!-- .nk-block-head-content -->
            <div class="nk-block-head-content">
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="card card-bordered card-preview">
        <div class="card-inner">
            <table class="datatable-init table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Log</th>
                        <th>Deskripsi</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $d->log_name }}</td>
                            <td>{{ $d->description }}</td>
                            <td>{{ $d->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div><!-- .card -->
</x-app-layout>
