<?php

namespace App\Http\Controllers\Private;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Reservacion;
use App\Services\Affiliates\ReferralTracker;

class SocioController extends BaseController
{
    public function login()
    {
        return view('socios.login');
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

        return view('socios.dashboard', compact('user', 'info', 'referralLink', 'stats', 'reservas'));
    }
}
