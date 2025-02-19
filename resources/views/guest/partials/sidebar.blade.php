@push('css')
    @vite('resources/css/guestAside.css')
@endpush

<div class="sidebar-wrapper">
    <!-- Sidebar -->
    <div class="sidebar shadow-lg" id="sidebar">
        <div class="d-flex justify-content-between align-items-center aside-head">
            <strong class="text-warning">Daftar Layer</strong>
            <div class="d-flex gap-1">
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#openModalDataset"
                    class="b-plus d-flex justify-content-center align-items-center"><i  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tambah Layer" class="bi bi-plus"></i></a>

                <a href="#" id="anotherToggle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tutup Sidebar"
                    class="b-arrow-bar-left d-flex justify-content-center align-items-center link-warning"><i
                        class="bi bi-arrow-bar-left"></i></a>
            </div>
        </div>
        <ul id="layerList" class="list-group list-group-flush"></ul>
    </div>

    <!-- Toggle Sidebar Button -->
    <div
        class="toggle-btn rounded shadow-lg border-0 p-2 text-success cursor-pointer d-flex jutify-content-center align-items-center">
        <i id="toggleSidebar" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle Sidebar" class="bi bi-list" style="cursor: pointer;"></i>
        <form action="" method="get">
            <div class="d-flex jutify-content-center align-items-center">
                <input type="text" class="ms-2 border-0 p-1 rounded-start" name="search-from-aside"
                    id="search-from-aside" placeholder="cari..">
                <button id="search-btn" class="border-0 p-1 link-success" type="button"><i
                        class="bi bi-search ms-1"></i></button>
                <button id="close-result-btn" class="border-0 p-1 d-none link-danger rounded-end" type="button"><i
                        class="bi bi-x-circle ms-1 me-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cari Lokasi"></i></button>{{-- secara default disembunyikan, dimuncukan ketika result area dimunculkan --}}
            </div>
        </form>
    </div>

    <!-- Result Area -->
    <div class="result-area" id="result-area" style="display: none;">
        <div class="result-area-heading"></div>
        <!-- Result -->
        <div id="search-results" class="list-group list-group-flush">
        </div>
        <!--... -->
    </div>
</div>
