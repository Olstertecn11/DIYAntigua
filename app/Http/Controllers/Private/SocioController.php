<?php

namespace App\Http\Controllers\Private;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SocioController extends BaseController
{
    public function login(){
        return view('socios.login');
    }


    public function register(){

    }


    use AuthorizesRequests, ValidatesRequests;
}
