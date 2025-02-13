<x-guest-layout>
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
                            <h5 class="nk-block-title">Konfirmasi Sandi</h5>
                            <div class="nk-block-des">
                                <p>Ini adalah area aman dari aplikasi. Harap konfirmasikan kata sandi Anda sebelum melanjutkan.</p>
                            </div>
                        </div>
                    </div><!-- .nk-block-head -->
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="default-01">Kata Sandi</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    id="default-01" placeholder="masukkan kata sandi.." required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-primary btn-block">Mengonfirmasi</button>
                        </div>
                    </form><!-- form -->
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
</x-guest-layout>
