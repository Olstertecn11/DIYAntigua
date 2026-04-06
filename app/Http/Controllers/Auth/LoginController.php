<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Importante añadir esto

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Este método se ejecuta automáticamente tras un login exitoso.
     */
    protected function authenticated(Request $request, $user)
    {
        // 1 = Administrador (Antigua Transfers Admin)
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        }

        // 2 = Socio / Afiliado (Dueño de Airbnb)
        if ($user->role_id == 2) {
            return redirect()->route('socios.dashboard');
        }

        // Si no tiene rol definido, al home por defecto
        return redirect('/home');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
