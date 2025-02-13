<x-guest-layout>
    @section('title', $title)
    @section('description', $description)
    <x-breadcrumb :title="$title">
        <x-slot name="body">
            <form action="{{ route('article.list') }}" method="get" id="searchForm">
                <div class="input-group m-3" style="width: 90%;">
                    <input type="text" class="form-control" placeholder="Kata Kunci" aria-label="Kata Kunci"
                        aria-describedby="basic-addon2" name="search" value="{{ request('search') }}"
                        style="height: 20px;">
                    <button type="submit" class="input-group-text bg-success text-white" id="basic-addon2"
                        style="height: 32px">Cari</button>
                </div>
            </form>
        </x-slot>
    </x-breadcrumb>
    <div class="saasbox-blog-area blog-card-page">
        <div class="container">
            <div class="row g-4 g-md-5 justify-content-center">
                @foreach ($articles as $value)
                    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                        <div class="card blog-card border-0">
                            <div class="image-wrap">
                                <a class="d-block" href="{{ route('article.show', $value->slug) }}" style="height: 200px; object-fit:cover;">
                                    <img src="{{ Storage::url($value->documents->first()->path) }}" alt="">
                                </a>
                            </div>
                            <div class="card-body p-4 pb-2">
                                <div class="post-meta d-flex align-items-center justify-content-between mb-3">
                                    <span class="fz-14">
                                        <i class="me-1 bi bi-calendar"></i>
                                        {{ formatIndonesianDate($value->created_at) }}
                                    </span>
                                    <span class="fz-14">
                                        <i class="me-1 bi bi-clock"></i>
                                    </span>
                                </div>
                                <a class="post-title d-block mb-3" href="blog-details-1.html">{{ Str::limit($value->title, 40, '...') }}</a>
                                <p>{!! Str::limit($value->content, 100, '...') !!}</p><a
                                    class="btn btn-primary btn-minimal" href="blog-details-1.html">Selengkapnya...</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
    <div class="mb-120 d-block"></div>
</x-guest-layout>
