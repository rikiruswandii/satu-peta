@props(['user', 'title', 'description'])

<x-app-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="card">
                        <div class="card-aside-wrap">
                            @if (request()->routeIs('user.detail'))
                                {{ $slot }}
                            @elseif (request()->routeIs('user.log'))
                                {{ $slot }}
                            @endif
                            <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg"
                                data-toggle-body="true" data-content="userAside" data-toggle-screen="lg"
                                data-toggle-overlay="true">
                                <div class="card-inner-group" data-simplebar>
                                    <div class="card-inner">
                                        <div class="user-card">
                                            <div class="user-avatar bg-primary">
                                                @if ($user->image)
                                                    <img src="{{ $user->image ? Storage::url('uploads/' . $user->image) : '' }}"
                                                        alt="Avatar Pengguna">
                                                @else
                                                    <img src="{{ asset('assets/images/default.png') }}"
                                                        alt="Avatar Default">
                                                @endif
                                            </div>
                                            <div class="user-info">
                                                <span class="lead-text">{{ $user->name }}</span>
                                                <span class="sub-text">{{ $user->email }}</span>
                                            </div>
                                            <div class="user-action">
                                                <div class="dropdown">
                                                    <a class="btn btn-icon btn-trigger me-n2" data-bs-toggle="dropdown"
                                                        href="#"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#editPhotoModal">
                                                                    <em class="icon ni ni-camera-fill"></em><span>Change
                                                                        Photo</span></a></li>
                                                            <li><a href="javascript:void(0);" data-bs-toggle="modal"
                                                                    data-bs-target="#editProfileModal"><em
                                                                        class="icon ni ni-edit-fill"></em><span>Update
                                                                        Profile</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .user-card -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner">
                                        <div class="user-account-info py-0">
                                            <h6 class="overline-title-alt">Pengaturan</h6>
                                        </div><!-- .card-inner -->
                                        <ul class="link-list-menu">
                                            <li>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#changePassModal"><em
                                                        class="icon ni ni-lock-fill"></em><span>Ganti Kata Sandi</span></a>
                                            </li>
                                            <li><a class="{{ request()->routeIs('user.detail') ? 'active' : '' }} hover:!text-color-secondary"
                                                    href="{{ route('user.detail', ['id' => Crypt::encrypt($user->id)]) }}"><em
                                                        class="icon ni ni-user-fill-c"></em><span>Informasi Pengguna</span></a></li>
                                            <li><a href="{{ route('user.log', ['id' => Crypt::encrypt($user->id)]) }}"
                                                    class="{{ request()->routeIs('user.log') ? 'active' : '' }} hover:!text-color-secondary"><em
                                                        class="icon ni ni-activity-round-fill"></em><span>Aktivitas Pengguna</span></a></li>
                                        </ul>
                                    </div>
                                </div><!-- .card-inner-group -->
                            </div><!-- card-aside -->
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
    @php
        $modalPhoto = [
            'title' => 'Ganti Photo',
            'footer' =>
                '<button type="submit" class="btn btn-primary" form="changePhotoForm">Simpan</button>',
        ];
        $modalDelete = [
            'title' => 'Hapus Pengguna',
            'footer' =>
                '<button type="submit" class="btn btn-danger" form="deleteUserForm">Simpan</button>',
        ];
        $modalData = [
            'title' => 'Sunting Pengguna',
            'footer' =>
                '<button type="submit" class="btn btn-primary" form="editProfileForm">Simpan</button>',
        ];
        $changepass = [
            'title' => 'Ganti Kata Sandi',
            'footer' =>
                '<button type="submit" class="btn btn-primary" form="changePassForm">Simpan</button>',
        ];

        $photo = 'user.photo';
        $update = 'user.update';
        $password = 'user.change';
        $destroy = 'user.destroy';

    @endphp
    @section('modal')
        <x-modal :id="'editPhotoModal'" :data="$modalPhoto">
            <x-slot name="body">
                <div class="card">
                    <form id="changePhotoForm" method="POST"
                        action="{{ route($photo, ['id' => Crypt::encrypt($user->id)]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <input type="file" name="avatar" class="dropify !text-xs" data-show-remove="false"
                                data-allowed-file-extensions="png jpg jpeg gif"
                                data-default-file="{{ $user->image ? Storage::url('uploads/' . $user->image) : '' }}"
                                value="{{ $user->image }}">
                        </div>
                    </form>
                </div>
            </x-slot>
        </x-modal>
        <x-modal :id="'editProfileModal'" :data="$modalData">
            <x-slot name="body">
                <form id="editProfileForm" method="POST"
                    action="{{ route($update, ['id' => Crypt::encrypt($user->id)]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="masukkan nama.." value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="masukkan email.." value="{{ old('email', $user->email) }}" required>
                    </div>
                </form>
            </x-slot>
        </x-modal>
        <x-modal :id="'deleteUserModal'" :data="$modalDelete">
            <x-slot name="body">
                <form id="deleteUserForm" method="POST"
                    action="{{ route($destroy, ['id' => Crypt::encrypt($user->id)]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('delete')

                    <div class="row">
                        <h6>{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</h6>
                        <p>{{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}</p>
                        <div class="form-group">
                            <label for="name" class="form-label">Kata Sandi</label>
                            <input id="password"
                        name="password"
                        type="password" class="form-control"
                                placeholder="masukkan kata sandi.." required>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal :id="'changePassModal'" :data="$changepass">
            <x-slot name="body">
                <form method="POST" action="{{ route($password, ['id' => Crypt::encrypt($user->id)]) }}"
                    enctype="multipart/form-data" id="changePassForm">
                    @csrf
                    @method('POST')

                    <!-- Hidden username field for accessibility with a unique id -->
                    <input type="text" name="hidden_username" id="hidden_username" autocomplete="username"
                        class="d-none">

                    <div class="form-group row">
                        <label for="current_password"
                            class="col-md-4 col-form-label text-md-right">{{ __('Current Password') }}</label>
                        <div class="col-md-6">
                            <input id="current_password" type="password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                name="current_password" required autofocus autocomplete="current-password">
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password"
                            class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>
                        <div class="col-md-6 position-relative">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="alert alert-secondary mt-2 mb-0" role="alert" id="message">
                                <p style="font-weight: bold;"> Kata Sandi harus terdiri dari: </p>
                                <p id="length" class="invalid"> Minimal <b> 8 karakter </b> </p>
                                <p id="letter" class="invalid"> Huruf <b> kecil (a-z)</b> </p>
                                <p id="capital" class="invalid"> Huruf <b> KAPITAL (A-Z)</b></p>
                                <p id="number" class="invalid"> <b>Angka</b>(0-9) </p>
                                <p id="symbol" class="invalid"> <b>Symbol</b>(!$#%@)</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm"
                            class="col-md-4 col-form-label text-md-right">{{ __('Confirm New Password') }}</label>
                        <div class="col-md-6 position-relative">
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                            <span id="password-match-icon"
                                class="position-absolute top-50 end-0 translate-middle-y me-3"></span>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('password-confirm');
                const passwordMatchIcon = document.getElementById('password-match-icon');

                confirmPasswordInput.addEventListener('input', function() {
                    if (confirmPasswordInput.value === '') {
                        confirmPasswordInput.classList.remove('is-invalid', 'is-valid');
                        passwordMatchIcon.classList.remove('text-danger', 'text-success');
                        return;
                    }

                    if (confirmPasswordInput.value === passwordInput.value) {
                        confirmPasswordInput.classList.add('is-valid');
                        confirmPasswordInput.classList.remove('is-invalid');
                        passwordMatchIcon.classList.add('text-success');
                        passwordMatchIcon.classList.remove('text-danger');
                    } else {
                        confirmPasswordInput.classList.add('is-invalid');
                        confirmPasswordInput.classList.remove('is-valid');
                        passwordMatchIcon.classList.add('text-danger');
                        passwordMatchIcon.classList.remove('text-success');
                    }
                });
            });

            var myInput = document.getElementById("password");
            var retype = document.getElementById("password_confirmation");
            var letter = document.getElementById("letter");
            var capital = document.getElementById("capital");
            var number = document.getElementById("number");
            var symbol = document.getElementById("symbol");
            var length = document.getElementById("length");

            if (myInput) {
                myInput.onfocus = function() {
                    document.getElementById("message").style.display = "block";
                }

                myInput.onblur = function() {
                    document.getElementById("message").style.display = "none";
                }

                myInput.onkeyup = function() {
                    var lowerCaseLetters = /[a-z]/g;
                    var upperCaseLetters = /[A-Z]/g;
                    var numbers = /[0-9]/g;
                    var symbols = /[!$#%@]/g;

                    if (myInput.value.match(lowerCaseLetters)) {
                        letter.classList.remove("invalid");
                        letter.classList.add("valid");
                    } else {
                        letter.classList.remove("valid");
                        letter.classList.add("invalid");
                    }

                    if (myInput.value.match(upperCaseLetters)) {
                        capital.classList.remove("invalid");
                        capital.classList.add("valid");
                    } else {
                        capital.classList.remove("valid");
                        capital.classList.add("invalid");
                    }

                    if (myInput.value.match(numbers)) {
                        number.classList.remove("invalid");
                        number.classList.add("valid");
                    } else {
                        number.classList.remove("valid");
                        number.classList.add("invalid");
                    }

                    if (myInput.value.length >= 8) {
                        length.classList.remove("invalid");
                        length.classList.add("valid");
                    } else {
                        length.classList.remove("valid");
                        length.classList.add("invalid");
                    }

                    if (myInput.value.match(symbols)) {
                        symbol.classList.remove("invalid");
                        symbol.classList.add("valid");
                    } else {
                        symbol.classList.remove("valid");
                        symbol.classList.add("invalid");
                    }
                }
            }

            if (retype) {
                retype.onfocus = function() {
                    document.getElementById("feedback").style.display = "block";
                }

                retype.onblur = function() {
                    document.getElementById("feedback").style.display = "none";
                }
            }
        </script>
    @endpush
</x-app-layout>
