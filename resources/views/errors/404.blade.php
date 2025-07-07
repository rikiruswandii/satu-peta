@extends('errors::minimal')

@section('title', __('Not Found'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_page-not-found_6wni.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Oops! Kenapa Anda berada di sini?</h3>
        <p class="nk-error-text">Kami sangat meminta maaf atas ketidaknyamanannya. Sepertinya
            Anda mencoba mengakses halaman yang telah dihapus atau tidak pernah ada.</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
