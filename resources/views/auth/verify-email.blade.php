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
                            <h5 class="nk-block-title">Verifikasi Email</h5>
                            <div class="nk-block-des">
                                <p>Terima kasih telah mendaftar! Sebelum memulai, dapatkah Anda memverifikasi alamat
                                    email Anda dengan mengeklik tautan yang baru saja kami kirimkan kepada Anda? Jika
                                    Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan email
                                    lain kepada Anda.</p>
                            </div>
                            @if (session('status') == 'verification-link-sent')
                                <p class="mb-2">
                                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                                </p>
                            @endif
                        </div>
                    </div><!-- .nk-block-head -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div class="form-group">
                            <button class="btn btn-lg btn-primary btn-block">Kirim Ulang Email Verifikasi</button>
                        </div>
                    </form><!-- form -->
                    <div class="form-note-s2 pt-5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="link-primary">
                                {{ __('Log Out') }}
                            </button>
                        </form>
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
