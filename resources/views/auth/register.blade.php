<x-auth-layout>
    <!-- content @s -->
    <div class="nk-content ">
        <div class="nk-split nk-split-page nk-split-md">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
                <div class="nk-block nk-block-middle nk-auth-body">
                    <div class="brand-logo pb-5">
                        <a href="{{ route('/') }}" class="logo-link">
                            <h1>{{ $app->name }}</h1>
                        </a>
                    </div>
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Register</h5>
                            <div class="nk-block-des">
                                <p>Buat akun baru {{ $app->name }}</p>
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
                                <input type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{ old('name') }}" required autofocus
                                    autocomplete="name" placeholder="Masukkan nama Anda..">
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
                                <input type="text"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    name="email" id="email" value="{{ old('email') }}" required
                                    autocomplete="email" placeholder="Masukkan alamat email Anda..">
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
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" id="password" required autocomplete="new-password"
                                    placeholder="Masukkan kata sandi Anda..">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="alert alert-secondary mt-2 mb-0" role="alert" id="message">
                                <p style="font-weight: bold;"> Kata Sandi harus terdiri dari: </p>
                                <p id="length" class="invalid"> Minimal <b> 8 karakter </b> </p>
                                <p id="letter" class="invalid"> Huruf <b> kecil (a-z)</b> </p>
                                <p id="capital" class="invalid"> Huruf <b> KAPITAL (A-Z)</b></p>
                                <p id="number" class="invalid"> <b>Angka</b>(0-9) </p>
                                <p id="symbol" class="invalid"> <b>Symbol</b>(!$#%@)</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Konfimasi Kata Sandi</label>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password_confirmation">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password"
                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" id="password_confirmation" required
                                    autocomplete="new-password" placeholder="Konfirmasi kata sandi Anda..">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- reCAPTCHA -->
                        <div class="form-group d-flex justify-content-center align-items-center">
                            {!! htmlFormSnippet() !!}
                        </div>

                        @if ($errors->has('g-recaptcha-response'))
                            <div class="alert alert-danger mt-2">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </div>
                        @endif
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
                        <p>&copy; {{ Date('Y') }} {{ $app->name }}. All Rights
                            Reserved.</p>
                    </div>
                </div><!-- nk-block -->
            </div><!-- nk-split-content -->
            <div class="nk-split-content nk-split-stretch bg-abstract"></div><!-- nk-split-content -->
        </div><!-- nk-split -->
    </div>
    @push('scripts')
        @vite('resources/js/register.js')
    @endpush
</x-auth-layout>
