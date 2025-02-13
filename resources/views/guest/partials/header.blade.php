<header class="header-area header-2">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Navbar Brand --><a class="navbar-brand" href="{{ route('/') }}">
                <img class="img-fluid" src="{{ asset('assets/images/logo.png') }}" alt="" style="width: 38px;height:40px;"> <strong class="text-warning">{{ $app->name }}</strong></a>
            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#saasboxNav"
                aria-controls="saasboxNav" aria-expanded="false" aria-label="Toggle navigation"><i
                    class="bi bi-grid"></i></button>
            <!-- Navbar Nav -->
            <div class="collapse navbar-collapse" id="saasboxNav">
                <ul class="navbar-nav navbar-nav-scroll">
                    <li><a class="link-warning" href="{{ route('/') }}">Beranda</a></li>
                    <li><a class="link-warning" href="{{ route('explorer') }}">Jelajah</a></li>
                    <li><a class="link-warning" href="{{ route('search') }}">Pencarian</a></li>
                    <li><a class="link-warning" href="{{ route('article.list') }}">Artikel</a></li>
                </ul>
                <!-- Login Button -->
                <a class="btn btn-warning btn-sm ms-auto mb-3 mb-lg-0" href="{{ route('login') }}">Log In</a>
            </div>
        </div>
    </nav>
</header>

@push('css')
<style>
</style>
@endpush
