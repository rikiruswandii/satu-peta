@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_server-down_lxs9.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Layanan Tidak Tersedia</h3>
        <p class="nk-error-text">Layanan sedang dalam pemeliharaan atau mengalami gangguan. Silakan coba lagi nanti.</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
