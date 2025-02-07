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
            top: 16px;
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

        .toggle-btn.closed {
            transform: translateX(-250px);
        }

        .b-plus {
            font-size: 20px;
            width: 28px;
            height: 28px;
            border-radius: 5px;
            padding: 5px;
            border: none;
            background: #007052;
            color: #ffffff;
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
            background: rgba(7, 169, 88, 0.785);
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
    </style>
@endpush

<div class="sidebar-wrapper">
    <!-- Sidebar -->
    <div class="sidebar shadow-lg" id="sidebar">
        <div class="d-flex justify-content-between align-items-center aside-head">
            <strong class="text-light">Daftar Layer</strong>
            <div class="d-flex gap-1">
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#openModalDataset" class="b-plus d-flex justify-content-center align-items-center"><i class="bi bi-plus"></i></a>

                <a href="#" id="anotherToggle" class="b-arrow-bar-left d-flex justify-content-center align-items-center link-light"><i class="bi bi-arrow-bar-left"></i></a>
            </div>
        </div>
        <div class="p-3">
            <ul class="nav flex-column aside-content mt-3">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#"><i class="bi bi-house"></i> </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Toggle Sidebar Button -->
    <div class="toggle-btn rounded shadow-lg border-0 p-2 text-success cursor-pointer d-flex jutify-content-center align-items-center"
        id="toggleSidebar">
        <i class="bi bi-list" style="cursor: pointer;"></i>
        <form action="" method="get">
            <div class="d-flex jutify-content-center align-items-center">
                <input type="text" class="ms-2 border-0 p-1" name="search-from-aside" id="search-from-aside" placeholder="cari..">
                <button class="border-0 p-1" type="submit"><i class="bi bi-search text-success ms-1"></i></button>
            </div>
        </form>
    </div>
</div>
