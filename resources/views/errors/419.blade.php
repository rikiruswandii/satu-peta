@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_cat_lqdj.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Sesi Berakhir</h3>
        <p class="nk-error-text">Sesi Anda telah berakhir. Silakan segarkan halaman dan coba lagi.</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
