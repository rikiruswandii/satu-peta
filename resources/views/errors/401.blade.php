@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_access-denied_krem.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Akses Ditolak</h3>
        <p class="nk-error-text">Anda tidak memiliki izin untuk mengakses halaman ini. Silakan login terlebih dahulu.</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
