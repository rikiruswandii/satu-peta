<footer class="footer-area footer-2 pt-120 pb-120">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <!-- Footer Widget Area -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="footer-widget-area"><a class="d-lg-flex align-items-center justify-content-center mb-4 gap-2"
                        href="{{ route('/') }}"><img class="w-25" src="{{ asset('assets/images/logo.png') }}"
                            alt="">
                        <H3 class="text-warning w-50">{{ $app->name }}</H3>
                    </a>
                    <p class="text-white">{{ $app->about }}</p>
                    <!-- Newsletter Form -->
                    {{-- <div class="newsletter-form mb-4">
                        <form class="d-flex align-items-stretch" action="#">
                            <input class="form-control rounded-0 rounded-start" type="email"
                                placeholder="Enter email">
                            <button class="btn btn-warning rounded-0 rounded-end px-3" type="submit"><i
                                    class="bi bi-arrow-right"></i></button>
                        </form>
                    </div> --}}
                </div>
            </div>

            <!-- Footer Widget Area -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="footer-widget-area">
                    <h5 class="mb-4 text-white">Hubungi Kami</h5>
                    <p class="lh-base mb-3 text-white">{{ $app->address }}</p>
                    <p class="mb-0 text-white">Call: <a href="tel:+{{ $app->phone }}"></a>+{{ $app->phone }} <br> Email: <a href="mailto:{{ $app->email }}"></a>{{ $app->email }}</p>
                    <!-- Footer Social Icon -->
                    <div class="footer-social-icon d-flex align-items-center mt-3"><a href="#"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Facbook"><i
                                class="bi bi-facebook"></i></a><a href="#" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Twitter"><i class="bi bi-twitter"></i></a><a href="#"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram"><i
                                class="bi bi-instagram"></i></a><a href="#" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Linkedin"><i class="bi bi-linkedin"></i></a><a href="#"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Youtube"><i
                                class="bi bi-youtube"></i></a></div>
                </div>
            </div>

            <!-- Footer Widget Area-->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="footer-widget-area">
                    <h5 class="mb-4 text-white">Tautan Terkait</h5>
                    <ul class="list-unstyled">
                        @forelse ($links as $link)
                            <li><a href="{{ $link->url }}" target="_blank"><i
                                        class="bi bi-caret-right"></i>{{ $link->title }}</a></li>
                        @empty
                            <!-- SVG image -->
                            <div class="text-left mb-4 d-flex align-items-start">
    <img src="{{ asset('images/undraw_link-shortener_9ro5 (1).svg') }}" alt=""
        class="w-25 h-auto me-3"> <!-- me-3 untuk memberi margin kanan -->
</div>

                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div
            class="copywrite-wrapper bg-white mt-5 rounded-3 d-lg-flex align-items-lg-center justify-content-lg-center">
            <!-- Copywrite Text -->
            <div class="copywrite-text text-center text-lg-start mb-3 mb-lg-0 me-lg-4">
                <p class="mb-0"> &copy; {{ Date('Y') }} <strong class="text-warning">{{ $app->name }}</strong>
                    All rights reserved. </p>
            </div>

        </div>
    </div>
</footer>
