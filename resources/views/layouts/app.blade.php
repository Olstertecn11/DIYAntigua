<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true">
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/global.css'])

    <style>
        /* --- Estilos Base --- */
        body { font-family: 'Nunito', sans-serif; background-color: #000; }

        /* --- Estilos de Errores (Toasts) --- */
        .toast-container { position: fixed; top: 25px; right: 25px; z-index: 9999; width: 300px; }
        .toast-pill {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 50px; padding: 10px 15px;
            display: flex; align-items: center; gap: 12px; margin-bottom: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); animation: toastIn 0.4s forwards;
        }
        .toast-icon { background: #ff4757; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; flex-shrink: 0; }
        .toast-content { color: white; font-size: 12px; font-weight: 500; flex-grow: 1; }
        .toast-close { background: none; border: none; color: rgba(255, 255, 255, 0.6); cursor: pointer; font-size: 12px; }
        @keyframes toastIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .toast-out { opacity: 0; transform: translateX(30px); transition: all 0.5s ease; }

        /* --- Estilos Navbar --- */
        .nav-link-custom {
            position: relative;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }
        .nav-link-custom:hover { color: #feca00 !important; }

        /* Efecto Underline Amarillo */
        .nav-link-custom::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #feca00;
            transition: width 0.3s ease;
        }
        .nav-link-custom:hover::after { width: 100%; }

        .btn-login {
            border-radius: 50px;
            color: #efc643 !important;
            background: rgba(171, 140, 50, 0.1);
            border: 1px solid rgba(239, 198, 67, 0.3);
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }
        .btn-login:hover { background: rgba(254, 202, 0, 0.2); border-color: #feca00; color: #feca00 !important; }

        .btn-register {
            border-radius: 50px;
            background: white;
            color: black !important;
            font-weight: 800;
            border: 1px solid white;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }
        .btn-register:hover { background: transparent !important; color: white !important; }

        /* Dropdown Estilo */
        .dropdown-custom {
            background: #0a0a0a;
            border: 1px solid #262626;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        @media (max-width: 768px) {
            #nav-content.show { display: block !important; }
            .nav-link-custom::after { display: none; } /* Quitamos underline en móvil por espacio */
        }
    </style>
</head>

<body>
    <div id="app">
        @if (!isset($navbar) || $navbar !== false)
            <nav class="w-full z-50 py-4 border-b border-[#1a1a1a]" style="background: #000;">
                <div class="container mx-auto px-4 flex items-center justify-between">

                    <div class="flex-shrink-0">
                        <a class="flex items-center gap-3 no-underline" href="{{ url('/') }}">
                            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true"
                                 class="w-10 h-10 md:w-12 md:h-12 object-contain" alt="Logo">
                            <span class="text-white font-extrabold text-xl tracking-tighter uppercase hidden lg:block">
                                {{ config('app.name', 'DiyAntigua') }}
                            </span>
                        </a>
                    </div>

                    <button id="mobile-menu-button" class="md:hidden text-white p-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>

                    <div id="nav-content" class="hidden md:flex flex-grow items-center justify-between ml-10">

                        <ul class="flex flex-row list-none p-0 m-0 mx-auto gap-8 text-[12px] lg:text-[13px] uppercase tracking-widest font-bold">
                            <li><a href="{{ url('/reservar-traslado') }}" class="nav-link-custom block">Reservar Traslado</a></li>
                            <li><a href="{{ url('/destinos') }}" class="nav-link-custom block">Destinos</a></li>
                            <li><a href="{{ url('/informacion-del-servicio') }}" class="nav-link-custom block">Información</a></li>
                            <li><a href="{{ url('/ayuda') }}" class="nav-link-custom block">Ayuda</a></li>
                        </ul>

                        <div class="flex flex-row gap-3 items-center flex-shrink-0">
                            @guest
                                <a href="{{ route('login') }}" class="btn-login px-5 py-2 text-sm">Iniciar Sesión</a>
                                <a href="{{ route('register') }}" class="btn-register px-5 py-2 text-sm">Registrarse</a>
                            @else
                                <div class="relative group">
                                    <button class="text-white/90 hover:text-white flex items-center gap-2 font-bold text-sm outline-none bg-[#111] px-4 py-2 rounded-full border border-[#262626]">
                                        <i class="fas fa-user-circle text-lg text-[#feca00]"></i>
                                        {{ Auth::user()->name }}
                                        <i class="fas fa-chevron-down text-[10px] opacity-50 group-hover:rotate-180 transition-transform"></i>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 dropdown-custom hidden group-hover:block z-[100]">
                                        <a class="block px-4 py-3 text-sm text-white hover:bg-[#1a1a1a] no-underline" href="{{ route('logout') }}"
                                                                                                                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2 text-red-500"></i> Cerrar Sesión
                                        </a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>

                <div id="mobile-menu" class="hidden md:hidden px-4 pt-4 pb-6 border-t border-[#1a1a1a] bg-black">
                    <ul class="flex flex-col gap-4 text-center">
                        <li><a href="{{ url('/reservar-traslado') }}" class="text-white text-sm uppercase font-bold">Reservar Traslado</a></li>
                        <li><a href="{{ url('/destinos') }}" class="text-white text-sm uppercase font-bold">Destinos</a></li>
                        <li><a href="{{ url('/informacion-del-servicio') }}" class="text-white text-sm uppercase font-bold">Información</a></li>
                        <li><a href="{{ url('/ayuda') }}" class="text-white text-sm uppercase font-bold">Ayuda</a></li>
                        <hr class="border-[#1a1a1a]">
                        @guest
                            <a href="{{ route('login') }}" class="btn-login py-2">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn-register py-2">Registrarse</a>
                        @endguest
                    </ul>
                </div>
            </nav>
        @endif

        {{-- Notificaciones Toast --}}
        @if ($errors->any())
            <div id="toast-wrapper" class="toast-container">
                @foreach ($errors->all() as $error)
                    <div class="toast-pill">
                        <div class="toast-icon"><i class="fas fa-exclamation"></i></div>
                        <div class="toast-content">{{ $error }}</div>
                        <button onclick="this.parentElement.remove()" class="toast-close"><i class="fas fa-times"></i></button>
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
            // Toasts auto-hide
            const container = document.getElementById('toast-wrapper');
            if (container) {
                setTimeout(() => {
                    container.classList.add('toast-out');
                    setTimeout(() => container.remove(), 500);
                }, 5000);
            }

            // Mobile Menu Toggle
            const menuButton = document.getElementById('mobile-menu-button');
            const navContent = document.getElementById('nav-content');

            if (menuButton && navContent) {
                menuButton.addEventListener('click', () => {
                    navContent.classList.toggle('hidden');
                    navContent.classList.toggle('show');
                    // Cambiar icono al abrir
                    const icon = menuButton.querySelector('i');
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                });
            }
        });
    </script>
</body>
</html>
