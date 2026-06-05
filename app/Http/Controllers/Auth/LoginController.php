<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Importante añadir esto
use Inertia\Inertia;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return Inertia::render('Auth/Login', [
            'mode' => 'client',
            'title' => 'Iniciar sesion',
            'kicker' => 'Acceso privado',
            'copy' => 'Accede a tu cuenta para gestionar tus reservas y datos de viaje.',
            'sideTitle' => 'DIY Antigua',
            'sideCopy' => 'Gestiona reservas privadas de forma rapida, segura y clara.',
            'showRegister' => true,
            'urls' => [
                'login' => route('login'),
                'register' => route('register'),
                'passwordRequest' => route('password.request'),
                'home' => route('welcome'),
            ],
        ]);
    }

    /**
     * Este método se ejecuta automáticamente tras un login exitoso.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isAffiliate()) {
            return redirect()->route('socios.dashboard');
        }

        // Si no tiene rol definido, al home por defecto
        return redirect()->route('welcome');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
