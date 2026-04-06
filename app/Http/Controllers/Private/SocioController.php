<?php

namespace App\Http\Controllers\Private;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Hashids\Hashids;

class SocioController extends BaseController
{
    public function login()
    {
        return view('socios.login');
    }



    public function dashboard()
    {
        $user = auth()->user();

        $info = $user->afiliadoInfo;

        $hashids = new Hashids('antigua-secret-salt', 8); // Salt único y longitud mínima de 8
        $idEncriptado = $hashids->encode($user->id); // Resultado: "v5pQ8zLR"

        $referralLink = route('welcome') . "?ref=" . $idEncriptado;

        $stats = [
            'ganancias' => 0.00,
            'comision' => $info->comision_porcentaje ?? '0.00',
            'referidos_count' => 0,
        ];

        return view('socios.dashboard', compact('user', 'info', 'referralLink', 'stats'));
    }
}
