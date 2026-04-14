<?php

namespace App\Http\Controllers\Private;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\AfiliadoInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Reservacion;


class AdminController extends Controller
{
    // Muestra el formulario
    public function login() {
        return view('admin.login');
    }

    // Procesa el login
    public function postLogin(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended('admin/dashboard');
            }

            // Si no es admin, lo sacamos
            Auth::logout();
            return back()->withErrors(['email' => 'No tienes permisos de administrador.']);
        }

        return back()->withErrors(['email' => 'Las credenciales no coinciden.']);
    }








    public function storeAfiliado(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nombre_comercial' => 'required|string',
            'comision' => 'required|numeric|between:0,100',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear el usuario con role_id de Afiliado
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2, // Asegúrate de que 2 sea el ID de Afiliado en tu tabla roles
            ]);

            // 2. Crear la información extra
            AfiliadoInfo::create([
                'user_id' => $user->id,
                'nombre_comercial' => $request->nombre_comercial,
                'comision_porcentaje' => $request->comision,
                'nit' => $request->nit,
                'telefono_negocio' => $request->telefono,
            ]);

            DB::commit();
            return back()->with('success', 'Socio creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el socio: ' . $e->getMessage());
        }
    }






    // Vista del Dashboard (Protegida)
    public function dashboard()
    {
        // Obtenemos todos los usuarios que tengan el rol de afiliado (ID 2 en tu caso)
        // Usamos with('afiliadoInfo') para traer los datos comerciales de una vez
        $afiliados = AfiliadoInfo::with('user')->get();

        return view('admin.dashboard', compact('afiliados'));
    }

    // Añade esto dentro de la clase AdminController
    public function indexAfiliados()
    {
        // Obtenemos la información de los socios
        $afiliados = AfiliadoInfo::with('user')->get();

        // Puedes crear una vista específica o reutilizar la lógica del dashboard
        return view('admin.dashboard', compact('afiliados'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }


    public function reservas()
    {
        $reservas = Reservacion::with(['ruta.origen', 'ruta.destino'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reservas.index', compact('reservas'));
    }



}
