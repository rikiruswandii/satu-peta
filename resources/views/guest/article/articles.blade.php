<x-guest-layout>
    @section('title', $title)
    @section('description', $description)

    @push('css')
        <style>
            .single-widget-area .widget-form button {
                position: absolute !important;
                -webkit-transition-duration: 500ms !important;
                transition-duration: 500ms !important;
                width: 80px !important;
                height: 50px !important;
                background-color: #0fac81 !important;
                color: #ffffff !important;
                border: none !important;
                top: 0 !important;
                right: 0 !important;
                z-index: 10 !important;
                cursor: pointer !important;
                border-radius: 0 6px 6px 0 !important;
            }

            .saasbox-pagination-area .page-item.active .page-link {
                background-color: #0fac81 !important;
                border-color: #0fac81 !important;
            }

            .btn-login {
                background: #0fac81;
                color: #FFC107;
                padding: 10px;
                margin: 0;
                border-radius: 3px;
                height: 30px;
                display: flex;
                align-items: center;
            }

            .btn-login:hover {
                background: none;
                color: #FFC107;
                border: 5px;
                border-radius: 3px;
                border-color: #FFC107;
                box-shadow: 5px 5px 10px rgba(145, 160, 7, 0.5);
            }
        </style>
    @endpush

    <x-breadcrumb :title="$title" :images="['images/carrow1 (1).JPG', 'images/carrow1 (1).JPG', 'images/carrow1 (1).JPG']">
        <x-slot name="body">
            <li class="breadcrumb-item active" aria-current="page">Berita</li>
        </x-slot>
    </x-breadcrumb>

    <div class="saasbox-blog-area">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between g-md-5">
                <div class="col-12 col-md-7">
                    <div class="row g-4 g-lg-5 justify-content-center">
                        @forelse ($articles as $value)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-6">
                                <div class="card border-0 shadow rounded-3 overflow-hidden h-100">
                                    <div class="image-wrap position-relative">
                                        <a href="{{ route('article.show', $value->slug) }}" class="d-block">
                                            <img src="{{ Storage::url($value->documents->first()->path) }}"
                                                class="card-img-top img-fluid transition-img" alt="{{ $value->title }}"
                                                style="height: 150px; object-fit: cover;">
                                        </a>
                                        <div
                                            class="position-absolute top-0 start-0 bg-success text-white px-2 py-1 small rounded-bottom-end">
                                            {{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column p-2">
                                        <a class="post-title fw-bold text-success text-decoration-none small mb-1 text-truncate"
                                            href="{{ route('article.show', $value->slug) }}"
                                            title="{{ $value->title }}">
                                            {{ Str::limit($value->title, 40, '...') }}
                                        </a>
                                        <p class="text-muted small flex-grow-1">
                                            {!! Str::limit(strip_tags($value->content), 60, '...') !!}
                                        </p>
                                        <a class="btn btn-outline-success btn-sm rounded-pill mt-auto align-self-start px-2 py-1"
                                            href="{{ route('article.show', $value->slug) }}">
                                            Baca <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- SVG image -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/undraw_friends_xscy.svg') }}" alt=""
                                    class="mx-auto d-block  w-25 h-auto">
                                <h1 class="mb-3">Oops! Data Tidak Tersedia.</h1>
                                <p class="lead">Data yang Anda cari saat ini tidak tersedia
                                    atau belum ditambahkan. Silakan coba lagi nanti.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination  -->
                    <div class="saasbox-pagination-area my-5 mb-lg-0">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center mb-0">
                                @if ($articles->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Prev</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link link-success" href="{{ $articles->previousPageUrl() }}"
                                            rel="prev">Prev</a>
                                    </li>
                                @endif

                                <!-- Menampilkan halaman secara dinamis -->
                                @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $articles->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($articles->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link link-success" href="{{ $articles->nextPageUrl() }}"
                                            rel="next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-5 col-lg-4">
                    <div class="blog-sidebar-area">
                        <!-- Widget -->
                        <div class="single-widget-area mb-4 mb-lg-5">
                            <!-- Search Form-->
                            <form class="widget-form" action="{{ route('article.list') }}" method="get">
                                <!-- Label dan Input Pencarian -->
                                <div class="input-group mb-3">
                                    <input type="search" name="search" class="form-control"
                                        placeholder="Masukkan kata kunci.." value="{{ request('search') }}"
                                        aria-label="Search articles">
                                    <button type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>

                        </div>
                        <!-- Widget -->
                        <div class="single-widget-area mb-4 mb-lg-5">
                            <h5 class="widget-title mb-30">Kategori</h5>
                            <ul class="catagories-list ps-0 list-unstyled">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('article.category', $category->slug) }}">
                                            <i class="bi bi-caret-right"></i>{{ $category->name }}
                                            <span class="text-warning ms-2">({{ $category->artikel->count() }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Widget untuk artikel terbaru -->
                        <div class="single-widget-area mb-4 mb-lg-5">
                            <h4 class="widget-title mb-30">Terbaru</h4>
                            @foreach ($latest_article as $article)
                                <div class="single-recent-post d-flex align-items-center">
                                    <div class="post-thumb">
                                        <!-- Menggunakan dokumen terkait sebagai gambar thumbnail -->
                                        @foreach ($article->documents as $document)
                                            @if ($document->type == 'thumbnails')
                                                <!-- Memastikan jenis dokumen adalah gambar -->
                                                <a class="rounded-3"
                                                    href="{{ route('article.show', $article->slug) }}">
                                                    <img class="rounded-3" src="{{ Storage::url($document->path) }}"
                                                        alt="{{ $article->title }}">
                                                </a>
                                                @break

                                                <!-- Hanya mengambil satu gambar pertama yang ditemukan -->
                                            @endif
                                        @endforeach

                                        <!-- Fallback jika tidak ada gambar -->
                                        @if ($article->documents->isEmpty() || !isset($document))
                                            <a class="rounded-3" href="{{ route('article.show', $article->slug) }}">
                                                <img class="rounded-3" src="{{ asset('img/bg-img/sb1.jpg') }}"
                                                    alt="">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="post-content">
                                        <a class="post-title" href="{{ route('article.show', $article->slug) }}">
                                            {{ $article->title }}
                                        </a>
                                        <p class="post-date">{{ $article->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-120 d-block"></div>
</x-guest-layout>
