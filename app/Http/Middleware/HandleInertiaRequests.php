<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template loaded on the first Inertia page visit.
     *
     * @var string
     */
    protected $rootView = 'inertia';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $roles = $user ? $user->loadMissing('roles')->roles->pluck('slug')->all() : [];

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles,
                    'is_admin' => in_array('admin', $roles, true),
                    'is_affiliate' => in_array('afiliado', $roles, true),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'routes' => [
                'admin' => [
                    'dashboard' => route('admin.dashboard'),
                    'reservas' => route('admin.reservas.index'),
                    'rutas' => route('admin.rutas.index'),
                    'lugares' => route('admin.lugares.index'),
                    'vehiculos' => route('admin.vehiculos.index'),
                    'conductores' => route('admin.conductores.index'),
                    'afiliados' => route('admin.afiliados.index'),
                    'pagos' => route('admin.pagos.index'),
                    'profile' => route('admin.profile.edit'),
                    'logout' => route('admin.logout'),
                ],
                'socios' => [
                    'dashboard' => route('socios.dashboard'),
                    'profile' => route('socios.profile.edit'),
                ],
                'logout' => route('logout'),
                'public' => [
                    'home' => route('welcome'),
                    'login' => route('login'),
                    'register' => route('register'),
                    'profile' => route('profile.edit'),
                    'reservasMine' => route('reservas.mine.index'),
                ],
            ],
        ];
    }
}
