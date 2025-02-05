<!-- sidebar @s -->
<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{ route('/') }}" class="logo-link nk-sidebar-logo">
                <div class="d-flex justify-content-center align-items-center w-100" style="height: 100vh;">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="logo-light logo-img img-fluid me-3" src="{{ asset('assets/images/logo.png') }}"
                            srcset="{{ asset('assets/images/logo.png') }} 2x" alt="logo">
                        <img class="logo-dark logo-img img-fluid me-3" src="{{ asset('assets/images/logo.png') }}"
                            srcset="{{ asset('assets/images/logo.png') }} 2x" alt="logo-dark">
                        <img class="logo-small logo-img logo-img-small img-fluid me-3"
                            src="{{ asset('assets/images/logo.png') }}"
                            srcset="{{ asset('assets/images/logo.png') }} 2x" alt="logo-small">
                        <h6 class="text-primary me-3">{{ config('app.name', 'Satu Peta') }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="nk-menu-trigger me-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em
                    class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    @foreach ($menus as $label => $mn)
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">{{ $label }}</h6>
                        </li><!-- .nk-menu-item -->
                        @foreach ($mn as $menu)
                            @if (isset($menu['submenu']))
                                <li class="nk-menu-item has-sub">
                                    <a href="#" class="nk-menu-link nk-menu-toggle">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni {{ $menu['icon'] }}"></em>
                                        </span>
                                        <span class="nk-menu-text">{{ $menu['text'] }}</span>
                                    </a>
                                    <ul class="nk-menu-sub">
                                        @foreach ($menu['submenu'] as $submenu)
                                            <li class="nk-menu-item {{ isset($submenu['child']) ? 'has-sub' : '' }}">
                                                @if (isset($submenu['route']) && $submenu['route'])
                                                    <a href="{{ route($submenu['route']) }}"
                                                        class="nk-menu-link {{ $submenu['is_active'] ? 'active' : '' }}">
                                                        <span class="nk-menu-icon">
                                                            <em class="icon ni {{ $submenu['iconchild'] }}"></em>
                                                        </span>
                                                        <span class="nk-menu-text">{{ $submenu['text'] }}</span>
                                                    </a>
                                                @else
                                                    <a href="#" class="nk-menu-link nk-menu-toggle">
                                                        <span class="nk-menu-icon">
                                                            <em class="icon ni {{ $submenu['iconchild'] }}"></em>
                                                        </span>
                                                        <span class="nk-menu-text">{{ $submenu['text'] }}</span>
                                                    </a>
                                                    <ul class="nk-menu-sub">
                                                        @foreach ($submenu['child'] as $child)
                                                            <li
                                                                class="nk-menu-item {{ isset($child['subchild']) ? 'has-sub' : '' }}">

                                                                <a href="{{ route($child['route']) }}"
                                                                    class="nk-menu-link {{ $child['is_active'] ? 'active' : '' }}">
                                                                    <span class="nk-menu-icon">
                                                                        <em
                                                                            class="icon ni {{ $child['iconchilds'] ?? '' }}"></em>
                                                                    </span>
                                                                    <span
                                                                        class="nk-menu-text">{{ $child['text'] ?? '' }}</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nk-menu-item">
                                    @if (isset($menu['route']) && $menu['route'])
                                        <a href="{{ route($menu['route']) }}"
                                            class="nk-menu-link {{ $menu['is_active'] ? 'active' : '' }}">
                                            <span class="nk-menu-icon">
                                                <em class="icon ni {{ $menu['icon'] }}"></em>
                                            </span>
                                            <span class="nk-menu-text">{{ $menu['text'] }}</span>
                                        </a>
                                    @else
                                        <span class="nk-menu-link">
                                            <span class="nk-menu-icon">
                                                <em class="icon ni {{ $menu['icon'] }}"></em>
                                            </span>
                                            <span class="nk-menu-text">{{ $menu['text'] }}</span>
                                        </span>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
