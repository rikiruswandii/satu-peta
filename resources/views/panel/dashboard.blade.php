<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="nk-block">
        <div class="row g-3 align-items-stretch">
            <!-- Konten pertama -->
            <div class="col-xxl-3 col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h5 fw-bold mb-4 text-primary">Selamat Datang!</h3>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-25">Nama</td>
                                    <td class="w-1">:</td>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Username</td>
                                    <td class="w-1">:</td>
                                    <td>{{ Auth::user()->username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Email</td>
                                    <td class="w-1">:</td>
                                    <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Terdaftar</td>
                                    <td class="w-1">:</td>
                                    <td>{{ \Carbon\Carbon::parse(Auth::user()->created_at)->isoFormat('D MMMM YYYY, HH:mm:ss') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Konten kedua -->
            <div class="col-xxl-3 col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h5 fw-bold mb-4 text-primary">Informasi Dasar</h3>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-25">Kota</td>
                                    <td class="w-1">:</td>
                                    <td>{{ ' - ' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Provinsi</td>
                                    <td class="w-1">:</td>
                                    <td>{{ ' - ' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Negara</td>
                                    <td class="w-1">:</td>
                                    <td>{{ ' - ' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Alamat IP</td>
                                    <td class="w-1">:</td>
                                    <td>{{ $data['ip'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">OS</td>
                                    <td class="w-1">:</td>
                                    <td>{{ $data['os'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-25">Peramban</td>
                                    <td class="w-1">:</td>
                                    <td>{{ $data['browser'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Konten ketiga -->
            <div class="col-xxl-3 col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-center align-items-center">
                            @if (!empty(Auth::user()->documents->isNotEmpty()))
                                <img src="{{ Storage::url(Auth::user()->documents()->where('documentable_id', Auth::user()->id)->where('type', 'avatar')->first()->path) }}"
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
                    <a href="{{ route('user.detail', ['id' => Crypt::encrypt(Auth::user()->id)]) }}"
                        class="btn btn-primary w-100 rounded-0 text-white">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
