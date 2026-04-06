
@section('styles')
    <style>
.login-container {
    min-height: 100vh;
    background-color: #fff;
}

.login-aside {
    {{-- background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); --}}
    background: linear-gradient(135deg, #973737d1 0%, #222222 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    overflow: hidden;
}

/* Efecto de curva blanca a la izquierda */
.login-aside::before {
    content: "";
    position: absolute;
    top: 0;
    left: -1px;
    width: 100px;
    height: 100%;
    background: white;
    clip-path: ellipse(50% 50% at 0% 50%);
}

.login-card {
    max-width: 400px;
    width: 100%;
    z-index: 2;
}

.form-control-login {
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    border: 1px solid #dee2e6;
    background: transparent;
    color: white;
}

.form-control-login::placeholder {
    color: #ffffff99;
}

.form-control-login:focus {
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
    background: transparent;
    border: 1px solid #000;
}

.btn-login {
    border-radius: 50px;
    padding: 0.75rem;
    background: #2a2525;
    border: none;
    font-weight: bold;
    color: white;
}

.btn-login:hover {
    background-color: #3d3636;
}

.logo{
    width: 28rem;
}
    </style>
@endsection

@extends('layouts.app', ['navbar' => false])

@section('content')
<div class="container-fluid login-container p-0">
    <div class="row g-0 min-vh-100">

        <div class="col-lg-5 d-flex flex-column justify-content-center px-5 bg-white">
            <div class="mb-5">
                <div class="d-flex flex-column align-items-center gap-2">
                    <img class="logo" src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/slogan.png?raw=true" alt="Logo" width="40">
                </div>
            </div>

            <div class="py-5">
                <h2 class="fw-light display-6" style="color: #9c5555">BIEVENIDO DE NUEVO !</h2>
                <p class="text-muted lead">Ingresa tu corero y contraseña para continuar</p>
            </div>
        </div>

        <div class="col-lg-7 login-aside">
            <div class="login-card text-center p-4">
                <h3 class="text-white fw-bold mb-1">Iniciar Sesión</h3>
                <p class="text-white-50 mb-5 text-uppercase small">PORTAL ADMINISTRADOR</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3 position-relative">
                        <input type="email" name="email" class="form-control form-control-login shadow-sm" placeholder="Correo" required>
                    </div>

                    <div class="mb-4 position-relative">
                        <input type="password" name="password" class="form-control form-control-login shadow-sm" placeholder="Contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-login w-100 shadow-sm mb-3">
                        Login
                    </button>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('password.request') }}" class="text-black fw-bold text-decoration-none small">Olvidé mi contraseña</a>
                    </div>
                </form>
            </div>

            <div class="position-absolute bottom-0 mb-3 text-white" style="font-size: 0.7rem;">
                Copyright © 2026 DIY Antigua. All rights reserved.
            </div>
        </div>

    </div>
</div>
@endsection
