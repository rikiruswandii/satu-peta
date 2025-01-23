<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="nk-block">
        <div class="row g-3">
            <!-- Konten pertama -->
            <div class="col-xxl-3 col-sm-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h5 fw-bold mb-4 text-primary">Selamat Datang!</h3>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex">
                                <span class="fw-bold w-25">Nama</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Username</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ Auth::user()->username ? Auth::user()->username : '-' }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Email</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Terdaftar</span>
                                <span class="ms-2">:</span>
                                <span
                                    class="ms-2">{{ \Carbon\Carbon::parse(Auth::user()->created_at)->isoFormat('D MMMM YYYY, HH:mm:ss') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konten kedua -->
            <div class="col-xxl-3 col-sm-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h5 fw-bold mb-4 text-primary">Informasi Dasar</h3>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex">
                                <span class="fw-bold w-25">Kota</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ ' - ' }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Provinsi</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ ' - ' }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Negara</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ ' - ' }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Alamat IP</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ $data['ip'] }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">OS</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ $data['os'] }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="fw-bold w-25">Peramban</span>
                                <span class="ms-2">:</span>
                                <span class="ms-2">{{ $data['browser'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konten ketiga -->
            <div class="col-xxl-3 col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-center align-items-center">
                            @if (!empty(Auth::user()->avatar))
                                <img src="{{ Auth::user()->avatar ? Storage::url('uploads/' . Auth::user()->avatar) : '' }}"
                                    class="rounded-circle img-fluid" style="height: 9rem; width: 9rem;"
                                    alt="Avatar Default">
                            @else
                                <img src="{{ asset('assets/images/default.png') }}" class="rounded-circle img-fluid"
                                    style="height: 9rem; width: 9rem;" alt="Avatar Default">
                            @endif
                        </div>
                        <h6 class="card-title text-primary mb-1">{{ Auth::user()->name }}</h6>
                        <p class="fw-semibold text-muted">{{ Auth::user()->role['name'] }}</p>
                    </div>
                    <a href="{{ route('user.detail', ['id' => Crypt::encrypt(Auth::user()->id)]) }}" class="btn btn-primary w-100 rounded-0 text-white">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
