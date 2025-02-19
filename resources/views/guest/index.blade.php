<x-guest-layout>
    @section('title', $title) <!-- Mengatur judul halaman -->
    @section('description', $description) <!-- Mengatur deskripsi halaman -->
    <div class="welcome-area hero6 bg-white">
        <div class="welcome4-slide-wrap">
            <!-- Slide Item -->
            <div class="welcome4-slide-item" style="background-image: url('./images/carrow1 (1).JPG')">
            </div>
            <!-- Slide Item -->
            <div class="welcome4-slide-item" style="background-image: url('./images/carrow1 (1).JPG')">
            </div>
        </div>
    </div>

    <!-- Floating Search Container -->
    <div class="search-area">
        <div class="container">
            <div class="row">
                <div class="row mb-4 justify-content-center align-items-center">
                    <h1 id="app-name" class="text-center text-light">{{ $app->name }}</h1>
                    <strong class="text-center text-light">{{ $app->about }}</strong>
                    <hr />
                    <strong class="text-center text-light">{{ __('Temukan dataset dengan mudah!') }}</strong>
                </div>
                <div class="rounded p-0 m-0 shadow-lg bg-text-gray border-0" id="searchCard"
                    style="background: rgba(255, 255, 255, 0.5) !important;">
                    <div class="card-body">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="d-flex align-items-center">
                                <!-- Gear Icon (Trigger Dropdown) -->
                                <button type="button" class="btn btn-light border me-2" id="dropdownTrigger">
                                    <i class="bi bi-gear text-dark"></i>
                                </button>

                                <div class="d-flex w-100">
                                    <!-- Input Pencarian -->
                                    <input id="input-search" name="search" type="text" class="form-control"
                                        placeholder="Masukkan kata kunci...">

                                    <!-- Pilihan Dataset & Instansi (Hidden by Default) -->
                                    <div id="extraOptions" class="d-flex w-100 d-none">
                                        <select name="sector" class="form-select select2 form-control">
                                            <option value="" selected disabled>Semua Kategori</option>
                                            @foreach ($categories as $sector)
                                                <option value="{{ $sector->name }}">{{ $sector->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="regional_agencies" class="form-select select2 form-control">
                                            <option value="" disabled selected>Semua Instansi</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Tombol Cari -->
                                <button class="btn btn-warning ms-2">Cari</button>
                            </div>
                        </form>

                        <!-- Dropdown untuk Peta (Sekarang di dalam card-body) -->
                        <div id="dropdownMenu" class="d-none bg-text-gray border-0 mt-3">
                            <x-map-container mapId="searchMapId" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Area-->
    <div class="saasbox-portfolio-area pt-120 pb-120">
        <div class="container">
            <div class="row align-items-end justify-content-between">
                <div class="col-12 col-sm-8 col-lg-7 col-xxl-6">
                    <div class="section-heading mb-0">
                        <h6>Dataset</h6>
                        <h2>Jelajahi Peta Terbaru Kami</h2>
                        <p>Temukan berbagai dataset peta terbaru yang telah diperbarui dengan informasi terkini dan
                            detail yang akurat untuk kebutuhan analisis dan visualisasi Anda.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-5">
                    <div class="text-sm-end mt-5 mt-sm-0">
                        <a class="btn btn-warning" href="{{ route('search') }}">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-block mb-80"></div>
        <div class="container">
            <div class="row g-3">
                @foreach ($maps as $map)
                    <x-map-card :id="$map->id" :card_class="'col-12 col-md-6 col-lg-3 my-4'" :card_id="$map->id" :card_title="$map->name"
                        :card_opd="$map->regional_agency->name" :card_filename="$map->documents->first()->name ?? 'No file'" :geojson_path="$map->documents->first() ? Storage::url($map->documents->first()->path) : ''" :regional_agency="$map->regional_agency->name" :sector="$map->sector->name" />
                @endforeach
            </div>
        </div>
    </div>

  <!-- Category Area -->
<div class="partner-area py-5 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-left">
                <h2>
                    <i class="bi bi-slack"></i>
                    Dataset
                </h2>
            </div>
            <div class="col-12">
                <div id="chartdiv"></div>
            </div>
        </div>
    </div>
</div>


    <!-- Groups Area-->
<div class="partner-area py-5 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-left mb-5">
                <h2><i class="bi bi-buildings-fill me-2"></i>Instansi --</h2> <!-- Tambahkan judul di sini -->
            </div>
            <div class="col-12">
                <div id="partnerCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($groups->chunk(6) as $index => $opdChunk)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($opdChunk as $partner)
                                        <div class="col-2 text-center">
                                            <div class="partner-logo">
                                                <img src="{{ asset('assets/images/logo.png') }}" alt=""
                                                    class="img-fluid" style="width:80px;height:85px;">
                                                <p class="mt-2">{{ $partner->name }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tombol Navigasi -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#partnerCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#partnerCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    @section('modal')
        <x-modal id="detailMapModal" :data="['title' => 'Detail Peta', 'footer' => '']" :size="'xl'" :cancelButtonText="'Tutup'">
            <x-slot name="body">
                <x-map-container geoJsonPath="" mapId="detailMap" />
                <table class="table">
                    <tr>
                        <th>Nama</th>
                        <td id="map-name"></td>
                    </tr>
                    <tr>
                        <th>Regional Agency</th>
                        <td id="map-regional-agency"></td>
                    </tr>
                    <tr>
                        <th>Sector</th>
                        <td id="map-sector"></td>
                    </tr>
                </table>
            </x-slot>
        </x-modal>
    @endsection
    <div class="mb-120 d-block"></div>
    <div class="saasbox-news-area news2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-9 col-lg-7 col-xxl-6">
                    <div class="section-heading text-center">
                        <h6>Artikel Terbaru</h6>
                        <h2>Berita Terbaru Kami</h2>
                        <p>Website ini menyediakan informasi dan artikel terkait kepentingan publik.
                            {{ config('app.name') }} mendukung pelayanan publik yang transparan dan akuntabel.</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center g-4 g-md-5 g-lg-4 g-xl-5">
                <!-- Blog Card -->
                @forelse ($news as $value)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="card rounded-1 border shadow-sm overflow-hidden h-100">
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
                                        <a class="btn btn-outline-success btn-sm rounded-2 mt-auto align-self-start px-2 py-1"
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
        </div>
    </div>
    <div class="mb-120 d-block"></div>

    <!-- Group Area-->


    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        @vite('resources/css/home.css')
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
        <!-- Resources -->
        <script>
            var categories = @json($categories);
        </script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
        @vite(['resources/js/search.js','resources/js/home.js'])
    @endpush

</x-guest-layout>
