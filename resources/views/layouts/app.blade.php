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
                        <ul class="navbar-nav mx-auto">
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
                                        <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link btn-register" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
