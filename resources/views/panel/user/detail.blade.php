<x-user :user="$user" :title="$title" :description="$description">
    <div class="card-inner card-inner-lg">
        <div class="nk-block-head nk-block-head-lg">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title text-primary">Informasi Pengguna</h4>
                    <div class="nk-block-des">
                        <p>Info dasar, seperti nama dan alamat, yang Anda gunakan pada
                            <strong class="text-primary">{{ config('app.name', 'Satu Peta Purwakarta') }}</strong>
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
            <div class="nk-data data-list">
                <div class="data-head">
                    <h6 class="overline-title">Dasar</h6>
                </div>
                <div class="data-item" data-bs-toggle="modal" data-bs-target="#profile-edit">
                    <div class="data-col">
                        <span class="data-label">Nama</span>
                        <span class="data-value">{{ $user->name }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more"><em
                                class="icon ni ni-forward-ios"></em></span></div>
                </div><!-- data-item -->
                <div class="data-item">
                    <div class="data-col">
                        <span class="data-label">Email</span>
                        <span class="data-value">{{ $user->email ?? 'belum ada' }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more disable"><em
                                class="icon ni ni-lock-alt"></em></span></div>
                </div><!-- data-item -->
            </div><!-- data-list -->
            <div class="nk-data data-list">
                <div class="data-head">
                    <h6 class="overline-title">Lanjutan</h6>
                </div>
                <div class="card-inner">
                    <div class="between-center flex-wrap flex-md-nowrap g-3">
                        <div class="nk-block-text">
                            <h6 class="text-primary">Hapus Pengguna</h6>
                            <p>{{ __('Setelah aksi ini, pengguna akan dihapus kemudian secara otomatis dikeluarkan.') }}</p>
                        </div>
                        <div class="nk-block-actions">
                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal" class="btn btn-danger">Hapus</a>
                        </div>
                    </div>
                </div><!-- .card-inner -->
            </div><!-- data-list -->
        </div><!-- .nk-block -->
    </div>

</x-user>
