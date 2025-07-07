@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_access-denied_krem.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Akses Terlarang</h3>
        <p class="nk-error-text">{{ __($exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.') }}</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
