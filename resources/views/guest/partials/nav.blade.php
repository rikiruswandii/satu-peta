<nav class="navbar navbar-expand-lg bg-waring bg-gradient">
    <div class="container">
        <!-- Navbar Brand --><a class="navbar-brand" href="{{ route('/') }}">
            <img class="img-fluid" src="{{ asset('assets/images/logo.png') }}" alt=""
                style="width: 38px;height:40px;"> <strong class="text-warning">{{ $app->name }}</strong></a>
        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#saasboxNav"
            aria-controls="saasboxNav" aria-expanded="false" aria-label="Toggle navigation"><i
                class="bi bi-grid"></i></button>
        <!-- Navbar Nav -->
        <div class="collapse navbar-collapse" id="saasboxNav">
            <ul class="navbar-nav navbar-nav-scroll">
                <li class="ms-2"><a class="link-success fw-bold" href="{{ route('/') }}">Beranda</a></li>
                <li class="ms-2"><a class="link-success fw-bold" href="{{ route('explorer') }}">Jelajah</a></li>
                <li class="ms-2"><a class="link-success fw-bold" href="{{ route('search') }}">Pencarian</a></li>
                <li class="ms-2"><a class="link-success fw-bold" href="{{ route('article.list') }}">Artikel</a></li>
            </ul>
            <!-- Login Button -->
            <a class="btn-login ms-auto mb-3 mb-lg-0 fw-bold" href="{{ route('login') }}">Log In</a>
        </div>
    </div>
</nav>
