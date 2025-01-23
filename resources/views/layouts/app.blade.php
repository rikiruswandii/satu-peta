<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name')) - {{ config('app.name') }}
    </title>
    <meta name="description" content="@yield('description', 'Deskripsi default')">

    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">

    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skins/theme-green.css') }}">

    @stack('css')
</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            @include('panel.partials.sidebar')
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                @include('panel.partials.header')
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="position-absolute top-0 end-0 p-3" style="z-index: 1000">
                        @if (session('success'))
                            <div class="alert alert-fill alert-success alert-icon" id="success-alert">
                                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-fill alert-danger alert-icon" id="error-alert">
                                <em class="icon ni ni-cross-circle"></em>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif


                        @if (session('info'))
                            <div class="alert alert-fill alert-info alert-icon" id="info-alert">
                                <em class="icon ni ni-alert-circle"></em> {{ session('info') }}
                            </div>
                        @endif
                    </div>
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                @include('panel.partials.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- modal -->
    @yield('modal')
    <!-- .modal -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/charts/chart-ecommerce.js') }}"></script>
    @stack('scripts')
</body>

</html>
