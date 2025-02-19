<x-guest-layout>
    @section('title', $title)
    @section('description', $description)
    <x-breadcrumb :title="$title" :images="['images/carrow1 (1).JPG', 'images/carrow1 (1).JPG', 'images/carrow1 (1).JPG']">
        <x-slot name="body">
            <li class="breadcrumb-item"><a href="{{ route('article.list') }}" class="text-white">Berita</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
        </x-slot>
    </x-breadcrumb>
    <div class="saasbox-blog-area">
        <div class="container">
            <div class="row justify-content-center justify-content-md-between">
                <div class="col-12 col-md-7"><img class="rounded-3 mb-4 mb-lg-5"
                        src="{{ Storage::url($article->documents->first()->path) }}" alt="">
                    <div class="post-date mb-2">{{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}</div>
                    <h1 class="mb-3">{{ $article->title }}</h1>
                    {!! $article->content !!}
                    <!-- Post Tag & Share Button-->
                    <div class="post-tag-share-button d-sm-flex align-items-center justify-content-between my-5">
                        <!-- Post Tags-->
                        <div class="post-tag pt-3">
                            <ul class="d-flex align-items-center ps-0 list-unstyled mb-0">
                                <li><a class="btn btn-info btn-sm me-2 rounded-pill"
                                        href="#">{{ $article->category->name }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-5 col-lg-4 mt-120 mt-md-0">
                    <div class="blog-sidebar-area">
                        <!-- Widget -->
                        <div class="single-widget-area mb-4 mb-lg-5">
                            <!-- Search Form-->
                            <form class="widget-form" action="{{ route('article.list') }}" method="get">
                                <input class="form-control" type="search" placeholder="Type your keyword" name="search">
                                <button type="submit" class="btn bg-success"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                        <!-- Widget -->
                        <div class="single-widget-area mb-4 mb-lg-5">
                            <h4 class="widget-title mb-30">Recent Posts</h4>
                            @foreach ($latest_article as $value)
                                <div class="single-recent-post d-flex align-items-center">
                                    <div class="post-thumb">
                                        <a class="rounded-3" href="#" style="height: 30px; object-fit: cover;">
                                            <img class="rounded-3" src="{{ Storage::url($value->documents->first()->path) }}" alt="">
                                        </a>
                                    </div>
                                    <div class="post-content">
                                        <a class="post-title" href="{{ route('article.show', $value->slug) }}">
                                            {{ Str::limit($value->title, 60, '...') }}
                                        </a>
                                        <p class="post-date">{{ \Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</p>
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
