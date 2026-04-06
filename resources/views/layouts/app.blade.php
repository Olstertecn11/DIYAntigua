<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/global.css'])

    <style>

.navbar-nav >.btn-register {
    border-radius: 75rem;
    background: white;
    font-weight: bold;
    width: 8rem;
    text-align: center;
    border: 1px solid transparent;
    transition: 0.8s;
}

.navbar-nav >.btn-register:hover {
    background: transparent;
    border: 1px solid white;
    color: white !important;
    transition: 0.6s;
}

.navbar-nav > .btn-login {
    border-radius: 75rem;
    width: 8rem;
    border: 1px solid transparent;
    color: #efc643;
    background: #ab8c3287;
    text-align: center;
}


.navbar-nav > .btn-login:hover {
    border-radius: 75rem;
    background: #feca002e;
    color: #feca00;
    border: 1px solid #feca00;
}






/* navbar */

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
        @if(!isset($navbar) || $navbar !== false)
            <nav class="navbar navbar-expand-md">
                <div class="container">
                    <a class="navbar-brand text-white title" href="{{ url('/') }}">
                        <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true"  class="navbar-logo" alt="">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse " id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mx-auto links">
                            {{-- add the next link: home, reservar traslado, confirmar reserva, consultar reserva, destinos, informacion del servicio, ayuda --}}
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/reservar-traslado') }}">{{ __('Reservar Traslado') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/destinos') }}">{{ __('Destinos') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/informacion-del-servicio') }}">{{ __('Información del Servicio') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/ayuda') }}">{{ __('Ayuda') }}</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link  btn-login" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link btn-register" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" style="color: #aaa" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                                                 onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <main class="">
            @yield('styles')
            @yield('content')
            @yield('scripts')
        </main>
    </div>
</body>
</html>
