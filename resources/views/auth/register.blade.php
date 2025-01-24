<x-auth-layout>
    <!-- content @s -->
    <div class="nk-content ">
        <div class="nk-split nk-split-page nk-split-md">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
                <div class="nk-block nk-block-middle nk-auth-body">
                    <div class="brand-logo pb-5">
                        <a href="html/index.html" class="logo-link">
                            {{-- <img class="logo-light logo-img logo-img-lg" src="./images/logo.png"
                                srcset="./images/logo2x.png 2x" alt="logo">
                            <img class="logo-dark logo-img logo-img-lg" src="./images/logo-dark.png"
                                srcset="./images/logo-dark2x.png 2x" alt="logo-dark"> --}}
                            <h1>{{ config('app.name', 'Satu Peta Purwakarta') }}</h1>
                        </a>
                    </div>
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Register</h5>
                            <div class="nk-block-des">
                                <p>Buat akun baru {{ config('app.name', 'Satu Peta Purwakarta') }}</p>
                            </div>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                        </div>
                    </div><!-- .nk-block-head -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">Nama</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" id="name"
                                    value="{{ old('name') }}" required autofocus autocomplete="name"
                                    placeholder="Masukkan nama Anda..">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email"
                                    value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="Masukkan alamat email Anda..">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"
                                    id="password" required autocomplete="new-password"
                                    placeholder="Masukkan kata sandi Anda..">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Konfimasi Kata Sandi</label>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                    id="password_confirmation" required autocomplete="new-password"
                                    placeholder="Konfirmasi kata sandi Anda..">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Registrasi</button>
                        </div>
                    </form><!-- form -->
                    <div class="form-note-s2 pt-4"> Sudah punya akun ? <a href="{{ route('login') }}"><strong>Klik
                                untuk masuk!</strong></a>
                    </div>
                </div><!-- .nk-block -->
                <div class="nk-block nk-auth-footer">
                    <div class="mt-3">
                        <p>&copy; {{ Date('Y') }} {{ config('app.name', 'Satu Peta Purwakarta') }}. All Rights
                            Reserved.</p>
                    </div>
                </div><!-- nk-block -->
            </div><!-- nk-split-content -->
            <div class="nk-split-content nk-split-stretch bg-abstract"></div><!-- nk-split-content -->
        </div><!-- nk-split -->
    </div>
</x-auth-layout>
