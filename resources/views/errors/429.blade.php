@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_buffer_dsav.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Terlalu Banyak Permintaan</h3>
        <p class="nk-error-text">Anda telah melakukan terlalu banyak permintaan dalam waktu singkat. Silakan coba lagi nanti.
        </p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
