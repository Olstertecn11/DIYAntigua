<?php

namespace App\Http\Controllers\Private;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Reservacion;
use App\Services\Affiliates\ReferralTracker;
use Inertia\Inertia;

class SocioController extends BaseController
{
    public function login()
    {
        return Inertia::render('Auth/Login', [
            'mode' => 'socio',
            'title' => 'Socios',
            'kicker' => 'Acceso de socios',
            'copy' => 'Consulta tus reservas referidas, comisiones y enlace de afiliado.',
            'sideTitle' => 'Socios DYANTIGUA',
            'sideCopy' => 'Tu panel de comisiones y referidos.',
            'showRegister' => false,
            'urls' => [
                'login' => route('login'),
                'home' => route('welcome'),
                'passwordRequest' => route('password.request'),
            ],
        ]);
    }



    public function dashboard(ReferralTracker $referrals)
    {
        $user = auth()->user();

        $info = $user->afiliadoInfo;

        $referralLink = route('welcome', ['ref' => $referrals->referralCodeFor($user)]);
        $reservas = Reservacion::with(['ruta.origen', 'ruta.destino'])
            ->where('socio_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        $summary = Reservacion::where('socio_id', $user->id)
            ->selectRaw('count(*) as referidos_count')
            ->selectRaw("sum(case when estado_pago = 'pagado' and estado_viaje <> 'cancelado' then comision_socio else 0 end) as ganancias")
            ->selectRaw("sum(case when estado_pago = 'pagado' and estado_viaje <> 'cancelado' then precio_total else 0 end) as ventas")
            ->first();

        $stats = [
            'ganancias' => (float) ($summary->ganancias ?? 0),
            'comision' => $info->comision_porcentaje ?? '0.00',
            'referidos_count' => (int) ($summary->referidos_count ?? 0),
            'ventas' => (float) ($summary->ventas ?? 0),
        ];

        return Inertia::render('Socios/Dashboard', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'info' => $info,
            'referralLink' => $referralLink,
            'stats' => $stats,
            'reservas' => $reservas->map(fn (Reservacion $reserva) => [
                'id' => $reserva->id,
                'codigo_reserva' => $reserva->codigo_reserva,
                'nombre_cliente' => $reserva->nombre_cliente,
                'ruta' => trim(($reserva->ruta?->origen?->nombre ?? 'Origen') . ' -> ' . ($reserva->ruta?->destino?->nombre ?? 'Destino')),
                'estado_pago' => $reserva->estado_pago,
                'estado_viaje' => $reserva->estado_viaje,
                'precio_total' => (float) $reserva->precio_total,
                'comision_socio' => (float) $reserva->comision_socio,
            ])->values()->all(),
            'urls' => [
                'profile' => route('socios.profile.edit'),
            ],
        ]);
    }
}
