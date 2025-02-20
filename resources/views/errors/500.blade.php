@extends('errors::minimal')

@section('title', __('Server Error'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_server-down_lxs9.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Kesalahan Server Internal</h3>
        <p class="nk-error-text">Kami mengalami masalah pada server. Silakan coba lagi nanti.</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
