@push('css')
    <style>
        .sidebar-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: transform 0.3s ease;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .content.shifted {
            margin-left: 0;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }
    </style>
@endpush

<div class="sidebar-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-3">
            <h5>My Sidebar</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#"><i class="bi bi-house"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#"><i class="bi bi-person"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#"><i class="bi bi-gear"></i> Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Toggle Sidebar Button -->
    <button class="btn btn-dark toggle-btn" id="toggleSidebar">
        <i class="bi bi-list"></i> Toggle Sidebar
    </button>
</div>
@push('scripts')
    <script>
        var $m = jQuery.noConflict();
        $m(document).ready(function() {
            const sidebar = $m("#sidebar");
            const content = $m("#main-content");
            const toggleSidebarBtn = $m("#toggleSidebar");

            toggleSidebarBtn.on("click", function() {
                sidebar.classList.toggle("closed");
                content.classList.toggle("shifted");
            });
        });
    </script>
@endpush
