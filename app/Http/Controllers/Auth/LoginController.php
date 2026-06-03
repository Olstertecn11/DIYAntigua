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
        if ($user->role_id == config('constantes.idAdmin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role_id == config('constantes.idAffiliate')) {
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
