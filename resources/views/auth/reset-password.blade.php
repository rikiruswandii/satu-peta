<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>

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
                            <h5 class="nk-block-title">Atur Ulang Sandi</h5>
                            <div class="nk-block-des">
                            </div>
                        </div>
                    </div><!-- .nk-block-head -->
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="default-01">Email</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="default-01" placeholder="masukkan alamat email.." value="{{ old('email', $request->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="default-02">Kata Sandi</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                id="default-02" placeholder="masukkan kata sandi.." autocomplete="new-password" required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="default-03">Konfirmasi Sandi</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="password_confirmation"
                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                    id="default-03" placeholder="masukkan ulang sandi.." autocomplete="new-password" required>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-primary btn-block">Atur Ulang Sandi</button>
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
