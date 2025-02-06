<x-auth-layout>
    <!-- content @s -->
    <div class="nk-content ">
        <div class="nk-split nk-split-page nk-split-md">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
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
                            <h5 class="nk-block-title">Masuk</h5>
                            <div class="nk-block-des">
                                <p>Akses panel {{ config('app.name', 'Satu Peta Purwakarta') }} menggunakan email dan
                                    kata sandi Anda.</p>
                            </div>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                        </div>
                    </div><!-- .nk-block-head -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="email">Email</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email"
                                    value="{{ old('email') }}" required autofocus autocomplete="email"
                                    placeholder="Masukkan alamat email terdaftar..">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a class="link link-primary link-sm" tabindex="-1"
                                        href="{{ route('password.request') }}">Lupa sandi?</a>
                                @endif
                            </div>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"
                                    id="password" required autocomplete="current-password"
                                    placeholder="Masukkan kata sandi..">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div><!-- .form-group -->

                        <!-- Remember Me -->
                        <div class="form-group">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" value="" id="remember_me"
                                    name="remember">
                                <label class="form-check-label" for="remember_me">
                                    {{ __('Ingat saya') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Masuk</button>
                        </div>
                    </form><!-- form -->
                    <div class="form-note-s2 pt-4"> Belum punya akun? <a href="{{ route('register') }}">Klik untuk
                            registrasi!</a>
                    </div>

                </div><!-- .nk-block -->
                <div class="nk-block nk-auth-footer">
                    <div class="mt-3">
                        <p>&copy; {{ Date('Y') }} {{ config('app.name', 'Satu Peta Purwakarta') }}. All Rights
                            Reserved.</p>
                    </div>
                </div><!-- .nk-block -->
            </div><!-- .nk-split-content -->
            <div class="nk-split-content nk-split-stretch bg-abstract"></div><!-- .nk-split-content -->
        </div><!-- .nk-split -->
    </div>
</x-auth-layout>
