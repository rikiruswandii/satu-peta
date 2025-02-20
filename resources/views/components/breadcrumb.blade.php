@props(['title', 'images'])

<div class="breadcrumb-wrapper position-relative">
    <!-- Carousel sebagai Background -->
    <div id="breadcrumbCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($images as $index => $image)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ asset($image) }}" class="d-block w-100" style="height: 400px; object-fit: cover;"
                        alt="Breadcrumb Image">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Breadcrumb Content -->
    <div class="position-absolute top-0 start-0 w-100 d-flex align-items-center justify-content-center text-white"
        style="background: rgba(0, 0, 0, 0.2); height: 400px; top: 0;">
        <div class="container text-center">
            <h2 class="breadcrumb-title">{{ $title }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-white">Home</a></li>
                    {{ $body }}
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="mb-120 d-block"></div>
