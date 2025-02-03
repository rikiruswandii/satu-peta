<?php

namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ViewShare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //settings
        View::share('app', app(GeneralSettings::class));

        //menu guest
        $guest = $this->guestMenus();
        View::share('guest', $guest);

        //menu panel
        $menus = $this->getMenus();
        View::composer('*', function ($view) use ($menus) {
            if (Auth::check()) {
                $userRole = Auth::user()->role['name'];
                if ($userRole !== 'admin') {
                    foreach ($menus as $label => &$items) {
                        $items = array_filter($items, function ($menu) use ($userRole) {
                            // Jika tidak ada role, menu boleh diakses
                            if (empty($menu['role'])) {
                                return true;
                            }

                            // Jika ada role, pisahkan menjadi array
                            $roles = explode(',', $menu['role']);

                            // Periksa apakah userRole ada dalam role yang diizinkan
                            return in_array($userRole, $roles);
                        });

                        // Hapus label jika tidak ada item di dalamnya
                        if (empty($items)) {
                            unset($menus[$label]);
                        }
                    }

                    unset($items);
                }
            }

            $view->with('menus', $menus);
        });

        return $next($request);
    }

    private function getMenus()
    {
        $menus = [
            'Ikhtisar' => [
                [
                    'route' => '/',
                    'icon' => 'ni ni-home-fill',
                    'text' => 'Dashboard',
                    'is_active' => request()->routeIs('/'),
                ],
            ],
            'Master' => [
                [
                    'route' => 'maps',
                    'icon' => 'ni ni-map',
                    'text' => 'Peta',
                    'is_active' => request()->routeIs('maps'),
                ],
                [
                    'route' => 'groups',
                    'icon' => 'ni ni-link-group',
                    'text' => 'Grup',
                    'is_active' => request()->routeIs('groups'),
                ],
                [
                    'route' => 'datasets',
                    'icon' => 'ni ni-grid-fill',
                    'text' => 'Kategori',
                    'is_active' => request()->routeIs('datasets'),
                ],
            ],
            'Informasi' => [
                [
                    'route' => 'articles',
                    'icon' => 'ni ni-article',
                    'text' => 'Artikel',
                    'is_active' => request()->routeIs('articles'),
                ],
            ],
            'Lainnya' => [
                [
                    'text' => 'Pengguna',
                    'icon' => 'ni ni-users-fill',
                    'role' => 'admin',
                    'submenu' => [
                        [
                            'route' => 'users',
                            'text' => 'Data Pengguna',
                            'iconchild' => 'ni ni-user-list-fill',
                            'is_active' => request()->routeIs('users'),
                        ],
                        [
                            'route' => 'logs',
                            'text' => 'Aktivitas Pengguna',
                            'iconchild' => 'ni ni-todo-fill',
                            'is_active' => request()->routeIs('logs'),
                        ],
                    ],
                ],
                [
                    'route' => 'settings',
                    'icon' => 'ni ni-setting-fill',
                    'text' => 'Pengaturan',
                    'is_active' => request()->routeIs('settings'),
                ],
            ],
        ];

        return $menus;
    }

    private function guestMenus()
    {
        $guest = [
        ];

        return $guest;
    }
}