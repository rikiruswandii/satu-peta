@push('css')
    <style>
        .sidebar-wrapper {
            display: flex;
            justify-content: flex-start;
            align-items: start;
            position: relative;
        }

        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.5);
            /* Warna dengan transparansi */
            backdrop-filter: blur(3px);
            /* Efek blur */
            height: 91vh;
            position: absolute;
            z-index: 999;
            left: 0;
            top: 0;
            transition: transform 0.3s ease;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .content {
            padding: 0;
            width: 100%;
        }

        .content.shifted {
            margin-left: 0;
        }

        .toggle-btn {
            position: absolute;
            top: 10px;
            left: 290px;
            z-index: 999;
            width: 270px;
            max-width: 270px;
            transition: transform 0.3s ease;
            background: rgba(255, 255, 255, 0.5);
            /* Warna dengan transparansi */
            backdrop-filter: blur(3px);
            /* Efek blur */
        }

        .result-area {
            position: absolute;
            top: 0;
            left: 260px;
            z-index: 998;
            width: 330px;
            max-width: 330px;
            height: 91vh;
            transition: transform 0.3s ease;
            background: rgba(255, 255, 255, 0.5);
            /* Warna dengan transparansi */
            backdrop-filter: blur(3px);
            /* Efek blur */
        }

        .result-area-heading {
            width: 100%;
            background: rgba(5, 168, 65, 0.2);
            /* Warna dengan transparansi */
            backdrop-filter: blur(3px);
            /* Efek blur */
            height: 83px;
        }

        .toggle-btn.closed {
            transform: translateX(-280px);
        }

        .result-area.closed {
            transform: translateX(-260px);
        }

        .b-plus {
            font-size: 20px;
            width: 28px;
            height: 28px;
            border-radius: 5px;
            padding: 5px;
            border: none;
            background: #ffffff;
            color: #007052;
        }

        .b-plus:hover {
            background: #ebebeb;
        }

        .b-arrow-bar-left {
            font-size: 20px;
            width: 28px;
            height: 28px;
            border-radius: 5px;
            padding: 5px;
            border: 2px;
            border-color: #ffffff;
        }

        .aside-head {
            background: #0fac81;
            padding: 10px;
            margin: 0;
            border-radius: 3px;
        }

        .aside-content {
            list-style: none;
            margin: 0;
            background: rgba(255, 255, 255, 0.5);
            padding: 5px;
            border-radius: 3px;
        }

        input[type=checkbox] {
            accent-color: #0fac81;
        }
    </style>
@endpush

<div class="sidebar-wrapper">
    <!-- Sidebar -->
    <div class="sidebar shadow-lg" id="sidebar">
        <div class="d-flex justify-content-between align-items-center aside-head">
            <strong class="text-light">Daftar Layer</strong>
            <div class="d-flex gap-1">
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#openModalDataset" title="Tambah Layer"
                    class="b-plus d-flex justify-content-center align-items-center"><i class="bi bi-plus"></i></a>

                <a href="#" id="anotherToggle" title="Tutup Sidebar"
                    class="b-arrow-bar-left d-flex justify-content-center align-items-center link-light"><i
                        class="bi bi-arrow-bar-left"></i></a>
            </div>
        </div>
        <ul id="layerList" class="list-group list-group-flush"></ul>
    </div>

    <!-- Toggle Sidebar Button -->
    <div
        class="toggle-btn rounded shadow-lg border-0 p-2 text-success cursor-pointer d-flex jutify-content-center align-items-center">
        <i id="toggleSidebar" class="bi bi-list" style="cursor: pointer;"></i>
        <form action="" method="get">
            <div class="d-flex jutify-content-center align-items-center">
                <input type="text" class="ms-2 border-0 p-1 rounded-start" name="search-from-aside"
                    id="search-from-aside" placeholder="cari..">
                <button id="search-btn" class="border-0 p-1 link-success" type="button"><i
                        class="bi bi-search ms-1"></i></button>
                <button id="close-result-btn" class="border-0 p-1 d-none link-danger rounded-end" type="button"><i
                        class="bi bi-x-circle ms-1 me-1"></i></button>{{-- secara default disembunyikan, dimuncukan ketika result area dimunculkan --}}
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
