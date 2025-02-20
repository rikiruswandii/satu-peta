@extends('errors::minimal')

@section('title', __('Payment Required'))
@section('content')
    <img class="nk-error-gfx" src="{{ asset('images/undraw_warning_qn4r.svg') }}" alt="">
    <div class="wide-xs mx-auto">
        <h3 class="nk-error-title">Pembayaran Diperlukan</h3>
        <p class="nk-error-text">Halaman ini memerlukan pembayaran sebelum dapat diakses.</p>
        <a href="{{ route('/') }}" class="btn btn-lg btn-primary mt-2">Kembali ke Beranda</a>
    </div>
@endsection
