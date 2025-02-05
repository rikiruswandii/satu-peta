<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/all-css-libraries.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    

    <!-- Scripts -->
    @stack('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Preloader-->
    <div class="preloader" id="preloader">
        <div class="spinner-grow text-light" role="status"><span class="visually-hidden">Loading...</span></div>
    </div>
    <!-- Header Area-->
    @include('guest.partials.header')
    {{ $slot }}
</body>
<!-- Footer Area-->
@include('guest.partials.footer')
<!-- Scroll To Top -->
<div id="scrollTopButton"><i class="bi bi-arrow-up-short"></i></div>
<!-- All JavaScript Files-->
<script src="{{ asset('js/all-js-libraries.js') }}"></script>
<script src="{{ asset('js/active.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
@stack('scripts')
</body>

</html>
