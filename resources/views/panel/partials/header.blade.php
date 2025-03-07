<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                        class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="{{ route('/') }}" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('assets/images/logo.png') }}"
                        srcset="{{ asset('assets/images/logo.png') }} 2x" alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('assets/images/logo.png') }}"
                        srcset="{{ asset('assets/images/logo.png') }} 2x" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <form class="nk-header-search ms-3 ms-xl-0" action="{{ route('maps') }}" method="GET">
                <em class="icon ni ni-search"></em>
                <input type="text" class="form-control border-transparent form-focus-none" id="search-maps"
                    placeholder="Cari data.." name="search" value="{{ request('search') }}">
            </form>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notifications</span>
                                <a href="#">Mark All as Read</a>
                            </div>
                            <div class="dropdown-body">
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="#">View All</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle me-n1" data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    @if (Auth::user()->documents->isNotEmpty())
                                        <img src="{{ Storage::url(Auth::user()->documents()->where('documentable_id', Auth::user()->id)->where('type', 'avatar')->first()->path) }}"
                                            alt="Avatar Pengguna">
                                    @else
                                        <img src="{{ asset('assets/images/default.png') }}" alt="Avatar Default">
                                    @endif
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div
                                        class="user-status user-status-{{ Auth::user()->email_verified_at ? 'verified' : 'unverified' }}">
                                        {{ Auth::user()->email_verified_at ? 'Terverifikasi' : 'Tak Dikenal' }}</div>
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        @if (Auth::user()->documents->isNotEmpty())
                                            <img src="{{ Storage::url(Auth::user()->documents()->where('documentable_id', Auth::user()->id)->where('type', 'avatar')->first()->path) }}"
                                                alt="Avatar Pengguna">
                                        @else
                                            <img src="{{ asset('assets/images/default.png') }}" alt="Avatar Default">
                                        @endif
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="/" target="_blank"><em class="icon ni ni-eye"></em><span>Lihat
                                                Website</span></a></li>
                                    <li><a
                                            href="{{ route('user.detail', ['id' => Crypt::encrypt(Auth::user()->id)]) }}"><em
                                                class="icon ni ni-user-alt"></em><span>View
                                                Profile</span></a></li>
                                    <li><a href="{{ route('user.log', ['id' => Crypt::encrypt(Auth::user()->id)]) }}"><em
                                                class="icon ni ni-activity-alt"></em><span>Login
                                                Activity</span></a></li>
                                    <li><a class="dark-switch" href="#"><em
                                                class="icon ni ni-moon"></em><span>Dark Mode</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a onclick="document.getElementById('logoutAction').submit();"
                                            class="px-1 cursor-pointer"><em class="icon ni ni-signout"></em><span>Sign
                                                out</span></a></li>
                                    <li>
                                        <form id="logoutAction" method="post" action="{{ route('logout') }}">@csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
