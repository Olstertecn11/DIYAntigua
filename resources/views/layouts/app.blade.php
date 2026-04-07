<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/global.css'])

    <style>
        /* --- Estilos de Errores (Toasts) --- */
        .toast-container {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 9999;
            width: 300px;
        }

        .toast-pill {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: toastIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .toast-icon {
            background: #ff4757;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            flex-shrink: 0;
            box-shadow: 0 0 10px rgba(255, 71, 87, 0.4);
        }

        .toast-content {
            color: white;
            font-size: 12px;
            font-weight: 500;
            flex-grow: 1;
            font-family: 'Nunito', sans-serif;
        }

        .toast-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            font-size: 12px;
            transition: 0.3s;
        }

        .toast-close:hover {
            color: white;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .toast-out {
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.5s ease;
        }

        /* --- Estilos Navbar --- */
        .navbar-nav>.btn-register {
            border-radius: 75rem;
            background: white;
            font-weight: bold;
            width: 8rem;
            text-align: center;
            border: 1px solid transparent;
            transition: 0.8s;
        }

        .navbar-nav>.btn-register:hover {
            background: transparent;
            border: 1px solid white;
            color: white !important;
        }

        .navbar-nav>.btn-login {
            border-radius: 75rem;
            width: 8rem;
            border: 1px solid transparent;
            color: #efc643;
            background: #ab8c3287;
            text-align: center;
        }

        .navbar-nav>.btn-login:hover {
            background: #feca002e;
            color: #feca00;
            border: 1px solid #feca00;
        }

        .navbar-logo {
            width: 3.5em;
        }

        .navbar {
            background: black;
        }

        .navbar-brand.title {
            font-weight: bold;
            font-size: 1.5em;
        }

        .navbar-nav {
            gap: 1rem;
        }

        .navbar-nav.links>.nav-item>.nav-link:hover {
            color: #feca00 !important;
            text-decoration: underline;
            text-underline-offset: 0.5em;
            text-decoration-thickness: 2px;
            text-decoration-color: #feca00;
        }
    </style>
</head>

<body>
    <div id="app">
        @if (!isset($navbar) || $navbar !== false)
            <nav class="navbar navbar-expand-md">
                <div class="container">
                    <a class="navbar-brand text-white title" href="{{ url('/') }}">
                        <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true"
                            class="navbar-logo" alt="">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto links">
                            <li class="nav-item"><a class="nav-link text-white"
                                    href="{{ url('/reservar-traslado') }}">Reservar Traslado</a></li>
                            <li class="nav-item"><a class="nav-link text-white"
                                    href="{{ url('/destinos') }}">Destinos</a></li>
                            <li class="nav-item"><a class="nav-link text-white"
                                    href="{{ url('/informacion-del-servicio') }}">Información</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ url('/ayuda') }}">Ayuda</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto">
                            @guest
                                <li class="nav-item"><a class="nav-link btn-login" href="{{ route('login') }}">Iniciar
                                        Sesión</a></li>
                                <li class="nav-item"><a class="nav-link btn-register"
                                        href="{{ route('register') }}">Registrarse</a></li>
                            @else
                                <li class="nav-item dropdown text-white">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" style="color: #aaa"
                                        href="#" role="button"
                                        data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">@csrf</form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        {{-- CONTENEDOR DE ERRORES CORREGIDO --}}
        @if ($errors->any())
            <div id="toast-wrapper" class="toast-container">
                @foreach ($errors->all() as $error)
                    <div class="toast-pill">
                        <div class="toast-icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="toast-content">
                            {{ $error }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="toast-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <main>
            @yield('styles')
            @yield('content')
            @yield('scripts')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('toast-wrapper');
            if (container) {
                // Se desvanece después de 4 segundos
                setTimeout(() => {
                    container.classList.add('toast-out');
                    setTimeout(() => container.remove(), 500);
                }, 4000);
            }
        });
    </script>
</body>

</html>
