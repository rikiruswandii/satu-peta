<x-auth-layout>
    <!-- content @s -->
    <div class="nk-content ">
        <div class="nk-split nk-split-page nk-split-md">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
                <div class="nk-block nk-block-middle nk-auth-body">
                    <div class="brand-logo pb-5">
                        <a href="{{ route('/') }}" class="logo-link">
                            <h1>{{ config('app.name', 'Satu Peta Purwakarta') }}</h1>
                        </a>
                    </div>
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Lupa Sandi</h5>
                            <div class="nk-block-des">
                                <p>Jika Anda lupa kata sandi, kami akan mengirimkan petunjuk untuk mengatur ulang kata
                                    sandi Anda melalui email.</p>
                            </div>
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                        </div>
                    </div><!-- .nk-block-head -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="default-01">Email</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="default-01" placeholder="masukkan alamat email.." value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-primary btn-block">Kirim Link Reset</button>
                        </div>
                    </form><!-- form -->
                    <div class="form-note-s2 pt-5">
                        <a href="{{ route('login') }}">Kembali ke login</a>
                    </div>
                </div><!-- .nk-block -->
                <div class="nk-block nk-auth-footer">
                    <div class="mt-3">
                        <p>&copy; {{ Date('Y') }} {{ $app->name }}. All Rights
                            Reserved.</p>
                    </div>
                </div><!-- .nk-block -->
            </div><!-- .nk-split-content -->
            <div class="nk-split-content nk-split-stretch bg-abstract"></div><!-- .nk-split-content -->
        </div><!-- .nk-split -->
    </div>
</x-auth-layout>
