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
use App\Services\Affiliates\ReferralTracker;
use Inertia\Inertia;


class AdminController extends Controller
{
    // Muestra el formulario
    public function login() {
        return Inertia::render('Auth/Login', [
            'mode' => 'admin',
            'title' => 'Admin',
            'kicker' => 'Panel administrativo',
            'copy' => 'Acceso para gestionar reservas, rutas, socios y pagos.',
            'sideTitle' => 'Operaciones',
            'sideCopy' => 'Control interno para DIY Antigua.',
            'showRegister' => false,
            'urls' => [
                'login' => route('admin.login.post'),
                'home' => route('welcome'),
                'passwordRequest' => route('password.request'),
            ],
        ]);
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








    public function storeAfiliado(Request $request, ReferralTracker $referrals)
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

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole('afiliado');

            // 2. Crear la información extra
            AfiliadoInfo::create([
                'user_id' => $user->id,
                'codigo_referido' => null,
                'nombre_comercial' => $request->nombre_comercial,
                'comision_porcentaje' => $request->comision,
                'nit' => $request->nit,
                'telefono_negocio' => $request->telefono,
            ]);

            $referrals->referralCodeFor($user->fresh('afiliadoInfo'));

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
        [$afiliados, $reservasStats, $proximasReservas] = $this->dashboardData();

        return Inertia::render('Admin/Dashboard', [
            'afiliados' => $this->formatAfiliados($afiliados),
            'reservasStats' => $reservasStats,
            'proximasReservas' => $this->formatProximasReservas($proximasReservas),
            'urls' => [
                'storeAfiliado' => route('admin.afiliados.store'),
                'reservas' => route('admin.reservas.index'),
            ],
        ]);
    }

    // Añade esto dentro de la clase AdminController
    public function indexAfiliados()
    {
        [$afiliados, $reservasStats, $proximasReservas] = $this->dashboardData();

        return Inertia::render('Admin/Dashboard', [
            'afiliados' => $this->formatAfiliados($afiliados),
            'reservasStats' => $reservasStats,
            'proximasReservas' => $this->formatProximasReservas($proximasReservas),
            'urls' => [
                'storeAfiliado' => route('admin.afiliados.store'),
                'reservas' => route('admin.reservas.index'),
            ],
        ]);
    }

    public function pagos()
    {
        $afiliados = AfiliadoInfo::with('user')
            ->get()
            ->map(function (AfiliadoInfo $afiliado) {
                $reservas = Reservacion::where('socio_id', $afiliado->user_id)
                    ->where('estado_pago', 'pagado')
                    ->where('estado_viaje', '!=', 'cancelado');

                $afiliado->reservas_pagadas_count = (clone $reservas)->count();
                $afiliado->ventas_referidas_total = (float) (clone $reservas)->sum('precio_total');
                $afiliado->comisiones_total = (float) (clone $reservas)->sum('comision_socio');

                return $afiliado;
            });

        $reservasReferidas = Reservacion::with(['socio.afiliadoInfo', 'ruta.origen', 'ruta.destino'])
            ->whereNotNull('socio_id')
            ->latest()
            ->paginate(15);

        $totales = [
            'ventas' => $afiliados->sum('ventas_referidas_total'),
            'comisiones' => $afiliados->sum('comisiones_total'),
            'reservas' => $afiliados->sum('reservas_pagadas_count'),
        ];

        return Inertia::render('Admin/Pagos/Index', [
            'afiliados' => $afiliados,
            'reservasReferidas' => $reservasReferidas->through(fn (Reservacion $reserva) => [
                'id' => $reserva->id,
                'codigo_reserva' => $reserva->codigo_reserva,
                'nombre_cliente' => $reserva->nombre_cliente,
                'socio' => $reserva->socio?->afiliadoInfo?->nombre_comercial ?? $reserva->socio?->name,
                'ruta' => trim(($reserva->ruta?->origen?->nombre ?? 'Origen') . ' -> ' . ($reserva->ruta?->destino?->nombre ?? 'Destino')),
                'estado_pago' => $reserva->estado_pago,
                'estado_viaje' => $reserva->estado_viaje,
                'precio_total' => (float) $reserva->precio_total,
                'comision_socio' => (float) $reserva->comision_socio,
                'urls' => [
                    'show' => route('admin.reservas.show', $reserva),
                ],
            ]),
            'totales' => $totales,
        ]);
    }

    private function dashboardData(): array
    {
        $afiliados = AfiliadoInfo::with('user')
            ->get()
            ->map(function (AfiliadoInfo $afiliado) {
                $stats = Reservacion::where('socio_id', $afiliado->user_id)
                    ->selectRaw('count(*) as reservas_total')
                    ->selectRaw("sum(case when estado_pago = 'pagado' and estado_viaje <> 'cancelado' then comision_socio else 0 end) as comisiones_total")
                    ->first();

                $afiliado->reservas_referidas_count = (int) ($stats->reservas_total ?? 0);
                $afiliado->comisiones_total = (float) ($stats->comisiones_total ?? 0);

                return $afiliado;
            });

        $reservasStats = [
            'total' => Reservacion::count(),
            'hoy' => Reservacion::whereDate('fecha_viaje', today())->count(),
            'pagadas' => Reservacion::where('estado_pago', 'pagado')->count(),
            'reembolsos' => Reservacion::where('reembolso_estado', 'pendiente')->count(),
            'comisiones' => Reservacion::where('estado_pago', 'pagado')
                ->where('estado_viaje', '!=', 'cancelado')
                ->sum('comision_socio'),
        ];

        $proximasReservas = Reservacion::with(['ruta.origen', 'ruta.destino'])
            ->where('estado_viaje', 'programado')
            ->whereDate('fecha_viaje', '>=', today())
            ->orderBy('fecha_viaje')
            ->orderBy('hora_viaje')
            ->limit(6)
            ->get();

        return [$afiliados, $reservasStats, $proximasReservas];
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

        return redirect()->route('admin.reservas.index');
    }

    private function formatAfiliados($afiliados): array
    {
        return $afiliados->map(fn (AfiliadoInfo $afiliado) => [
            'id' => $afiliado->id,
            'nombre_comercial' => $afiliado->nombre_comercial,
            'codigo_referido' => $afiliado->codigo_referido,
            'comision_porcentaje' => $afiliado->comision_porcentaje,
            'reservas_referidas_count' => $afiliado->reservas_referidas_count ?? 0,
            'comisiones_total' => (float) ($afiliado->comisiones_total ?? 0),
            'activo' => (bool) ($afiliado->activo ?? true),
            'user' => $afiliado->user ? [
                'name' => $afiliado->user->name,
                'email' => $afiliado->user->email,
            ] : null,
        ])->values()->all();
    }

    private function formatProximasReservas($reservas): array
    {
        return $reservas->map(fn (Reservacion $reserva) => [
            'id' => $reserva->id,
            'codigo_reserva' => $reserva->codigo_reserva,
            'nombre_cliente' => $reserva->nombre_cliente,
            'ruta' => trim(($reserva->ruta?->origen?->nombre ?? 'Origen') . ' -> ' . ($reserva->ruta?->destino?->nombre ?? 'Destino')),
            'fecha' => $reserva->travelDateTime()->format('d/m/Y H:i'),
            'urls' => [
                'show' => route('admin.reservas.show', $reserva),
            ],
        ])->values()->all();
    }

}
