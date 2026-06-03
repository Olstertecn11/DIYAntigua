<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DIY Antigua') }}</title>

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Montserrat:400,500,600,700,800,900" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Tailwind CDN: úsalo si todavía no tienes Tailwind compilado en Vite --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Assets Laravel --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/global.css'])

    {{-- Estilos propios de cada vista --}}
    @yield('styles')

    <style>
        :root {
            --brand-yellow: #FCCA00;
            --brand-gray: #363636;
            --brand-black: #000000;
            --brand-white: #ffffff;
            --brand-soft: #f6f6f3;
            --brand-border: rgba(54, 54, 54, .14);
            --gold: var(--brand-yellow);
            --gold-dark: #d6aa00;
            --orange: var(--brand-yellow);
            --dark: var(--brand-black);
            --dark-soft: #181818;
            --border-dark: rgba(255, 255, 255, .12);
        }

        html {
            scroll-behavior: smooth;
            scrollbar-color: rgba(252, 202, 0, .72) rgba(0, 0, 0, .12);
            scrollbar-width: thin;
        }

        *::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        *::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, .08);
            border-radius: 999px;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(252, 202, 0, .95), rgba(54, 54, 54, .55));
            border: 2px solid rgba(255, 255, 255, .18);
            border-radius: 999px;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: var(--brand-yellow);
        }

        body {
            font-family: 'Avant Garde Gothic', 'ITC Avant Garde Gothic', 'Montserrat', 'Century Gothic', Arial, sans-serif;
            background: var(--brand-black);
            color: var(--brand-gray);
            min-height: 100vh;
        }

        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .app-navbar {
            position: sticky;
            top: 0;
            z-index: 80;
            background: rgba(0, 0, 0, .92);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border-dark);
        }

        .app-navbar::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, var(--brand-yellow) 0 10px, transparent 10px);
            pointer-events: none;
        }

        .nav-link-custom {
            position: relative;
            color: rgba(255, 255, 255, .78) !important;
            transition: all .25s ease;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .nav-link-custom:hover {
            color: var(--brand-yellow) !important;
        }

        .nav-link-custom::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -8px;
            left: 0;
            border-radius: 999px;
            background: var(--brand-yellow);
            transition: width .25s ease;
        }

        .nav-link-custom:hover::after {
            width: 100%;
        }

        .btn-login {
            border-radius: 999px;
            color: var(--brand-yellow) !important;
            background: rgba(252, 202, 0, .10);
            border: 1px solid rgba(252, 202, 0, .34);
            font-weight: 800;
            transition: all .25s ease;
            text-decoration: none !important;
        }

        .btn-login:hover {
            background: rgba(252, 202, 0, .18);
            border-color: rgba(252, 202, 0, .72);
            color: var(--brand-yellow) !important;
            transform: translateY(-1px);
        }

        .btn-register {
            border-radius: 999px;
            background: var(--brand-yellow);
            color: var(--brand-black) !important;
            font-weight: 900;
            border: 1px solid rgba(252, 202, 0, .72);
            transition: all .25s ease;
            text-decoration: none !important;
            box-shadow: 0 16px 35px rgba(252, 202, 0, .18);
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 45px rgba(252, 202, 0, .25);
            color: var(--brand-black) !important;
        }

        .dropdown-custom {
            background: rgba(2, 6, 23, .96);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .45);
        }

        .mobile-menu-panel {
            background:
                linear-gradient(135deg, rgba(0, 0, 0, .98), rgba(54, 54, 54, .96));
            border-top: 1px solid rgba(255, 255, 255, .10);
        }

        .mobile-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .65rem;
            width: 100%;
            color: rgba(255, 255, 255, .86);
            font-size: .82rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .12em;
            padding: .9rem 1rem;
            border-radius: 1rem;
            text-decoration: none !important;
            transition: all .25s ease;
        }

        .mobile-link:hover {
            background: rgba(255, 255, 255, .07);
            color: var(--brand-yellow);
        }

        .toast-container {
            position: fixed;
            top: 92px;
            right: 24px;
            z-index: 9999;
            width: min(360px, calc(100vw - 32px));
        }

        .toast-pill {
            background: rgba(0, 0, 0, .88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 1.25rem;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .35);
            animation: toastIn .35s ease forwards;
        }

        .toast-icon {
            background: var(--brand-gray);
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            flex-shrink: 0;
        }

        .toast-content {
            color: white;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
            flex-grow: 1;
        }

        .toast-close {
            background: rgba(255, 255, 255, .08);
            border: none;
            color: rgba(255, 255, 255, .72);
            cursor: pointer;
            font-size: 12px;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            transition: all .2s ease;
        }

        .toast-close:hover {
            background: rgba(255, 255, 255, .15);
            color: white;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(-16px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .toast-out {
            opacity: 0;
            transform: translateX(24px);
            transition: all .45s ease;
        }

        @media (max-width: 768px) {
            .nav-link-custom::after {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div id="app">

        @if (!isset($navbar) || $navbar !== false)
            <nav class="app-navbar w-full">
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6">
                    <div class="flex items-center justify-between py-4">

                        {{-- Brand --}}
                        <div class="flex-shrink-0">
                            <a class="flex items-center gap-3 no-underline group" href="{{ url('/') }}">
                                <div
                                    class="relative h-12 w-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden group-hover:border-yellow-300/50 transition">
                                    <img src="{{ asset('images/logo.png') }}"
                                        class="h-10 w-10 object-contain" alt="Logo">

                                </div>

                                <div class="hidden lg:block leading-tight">
                                    <span class="block text-white font-black text-xl tracking-tight uppercase">
                                        {{ config('app.name', 'DIY Antigua') }}
                                    </span>
                                    <span
                                        class="block text-[11px] text-yellow-300 font-black uppercase tracking-[0.22em]">
                                        Private Transfers
                                    </span>
                                </div>
                            </a>
                        </div>

                        {{-- Desktop nav --}}
                        <div class="hidden md:flex flex-1 items-center justify-between ml-10">
                            <ul
                                class="flex flex-row items-center list-none p-0 m-0 mx-auto gap-8 text-[12px] lg:text-[13px] uppercase tracking-widest font-black">
                                <li>
                                    <a href="{{ url('/') }}#booking" class="nav-link-custom">
                                        <i class="fa-solid fa-route text-[11px] text-yellow-300/80"></i>
                                        Reservar Traslado
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/') }}#destinos" class="nav-link-custom">
                                        <i class="fa-solid fa-map-location-dot text-[11px] text-yellow-300/80"></i>
                                        Destinos
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/') }}#como-funciona" class="nav-link-custom">
                                        <i class="fa-solid fa-circle-info text-[11px] text-yellow-300/80"></i>
                                        Nosotros
                                    </a>
                                </li>

                                @auth
                                    <li>
                                        <a href="{{ route('reservas.mine.index') }}" class="nav-link-custom">
                                            <i class="fa-solid fa-ticket text-[11px] text-yellow-300/80"></i>
                                            Mis Reservas
                                        </a>
                                    </li>
                                @endauth

                            </ul>

                            <div class="flex flex-row gap-3 items-center flex-shrink-0">
                                @guest
                                    <a href="{{ route('login') }}" class="btn-login px-5 py-2.5 text-sm">
                                        Iniciar Sesión
                                    </a>

                                    <a href="{{ route('register') }}" class="btn-register px-5 py-2.5 text-sm">
                                        Registrarse
                                    </a>
                                @else
                                    <div class="relative group">
                                        <button type="button"
                                            class="text-white/90 hover:text-white flex items-center gap-3 font-black text-sm outline-none bg-white/5 px-4 py-2.5 rounded-full border border-white/10 hover:border-yellow-300/40 transition">
                                            <span
                                                class="h-8 w-8 rounded-full bg-yellow-300/10 border border-yellow-300/20 flex items-center justify-center">
                                                <i class="fas fa-user text-sm text-yellow-300"></i>
                                            </span>

                                            <span class="max-w-[150px] truncate">
                                                {{ Auth::user()->name }}
                                            </span>

                                            <i
                                                class="fas fa-chevron-down text-[10px] opacity-60 group-hover:rotate-180 transition-transform"></i>
                                        </button>

                                        <div
                                            class="absolute right-0 mt-3 w-56 dropdown-custom hidden group-hover:block z-[100]">
                                            <div class="px-4 py-3 border-b border-white/10">
                                                <p class="text-xs text-slate-400 mb-0">Sesión iniciada como</p>
                                                <p class="text-sm text-white font-black mb-0 truncate">
                                                    {{ Auth::user()->name }}
                                                </p>
                                            </div>

                                            <a class="block px-4 py-3 text-sm text-white hover:bg-white/5 no-underline"
                                                href="{{ route('profile.edit') }}">
                                                <i class="fas fa-user-gear me-2 text-yellow-300"></i>
                                                Mi Perfil
                                            </a>

                                            <a class="block px-4 py-3 text-sm text-white hover:bg-white/5 no-underline"
                                                href="{{ route('reservas.mine.index') }}">
                                                <i class="fas fa-ticket me-2 text-yellow-300"></i>
                                                Mis Reservas
                                            </a>

                                            <a class="block px-4 py-3 text-sm text-white hover:bg-white/5 no-underline"
                                                href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt me-2 text-red-400"></i>
                                                Cerrar Sesión
                                            </a>
                                        </div>
                                    </div>
                                @endguest
                            </div>
                        </div>
                        <h2
                            class="block md:hidden absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[#FCCA00] font-black text-2xl pointer-events-none select-none whitespace-nowrap">
                            DYANTIGUA
                        </h2>

                        {{-- Mobile button --}}
                        <button id="mobile-menu-button"
                            class="md:hidden h-11 w-11 rounded-2xl bg-white/5 border border-white/10 text-white flex items-center justify-center hover:border-yellow-300/50 transition"
                            type="button" aria-label="Abrir menú">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Mobile menu --}}
                <div id="mobile-menu" class="hidden md:hidden mobile-menu-panel px-4 pt-4 pb-6">
                    <ul class="flex flex-col gap-2 list-none p-0 m-0">
                        <li>
                            <a href="{{ url('/') }}#booking" class="mobile-link">
                                <i class="fa-solid fa-route text-yellow-300"></i>
                                Reservar Traslado
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/') }}#destinos" class="mobile-link">
                                <i class="fa-solid fa-map-location-dot text-yellow-300"></i>
                                Destinos
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/') }}#como-funciona" class="mobile-link">
                                <i class="fa-solid fa-circle-info text-yellow-300"></i>
                                Nosotros
                            </a>
                        </li>

                        @auth
                            <li>
                                <a href="{{ route('reservas.mine.index') }}" class="mobile-link">
                                    <i class="fa-solid fa-ticket text-yellow-300"></i>
                                    Mis Reservas
                                </a>
                            </li>
                        @endauth

                        <li>
                            <hr class="border-white/10 my-3">
                        </li>

                        @guest
                            <li>
                                <a href="{{ route('login') }}" class="btn-login block text-center py-3">
                                    Iniciar Sesión
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('register') }}" class="btn-register block text-center py-3">
                                    Registrarse
                                </a>
                            </li>
                        @else
                            <li>
                                <div class="rounded-2xl bg-white/5 border border-white/10 p-4 text-center">
                                    <p class="text-xs text-slate-400 mb-1">Sesión iniciada como</p>
                                    <p class="text-white font-black mb-3">
                                        {{ Auth::user()->name }}
                                    </p>

                                    <a class="mb-2 inline-flex items-center justify-center gap-2 rounded-full bg-white/10 border border-white/15 px-5 py-2.5 text-sm font-black text-white no-underline"
                                        href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-gear"></i>
                                        Mi Perfil
                                    </a>

                                    <a class="inline-flex items-center justify-center gap-2 rounded-full bg-red-500/10 border border-red-500/30 px-5 py-2.5 text-sm font-black text-red-300 no-underline"
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Cerrar Sesión
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        @endif

        {{-- Form logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        {{-- Notificaciones Toast --}}
        @if (isset($errors) && $errors->any())
            <div id="toast-wrapper" class="toast-container">
                @foreach ($errors->all() as $error)
                    <div class="toast-pill">
                        <div class="toast-icon">
                            <i class="fas fa-exclamation"></i>
                        </div>

                        <div class="toast-content">
                            {{ $error }}
                        </div>

                        <button onclick="this.parentElement.remove()" class="toast-close" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Flash messages opcionales --}}
        @if (session('success'))
            <div id="toast-wrapper-success" class="toast-container">
                <div class="toast-pill">
                    <div class="toast-icon" style="background: linear-gradient(135deg, #22c55e, #84cc16);">
                        <i class="fas fa-check"></i>
                    </div>

                    <div class="toast-content">
                        {{ session('success') }}
                    </div>

                    <button onclick="this.parentElement.remove()" class="toast-close" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <main>
            @yield('content')
        </main>

        @include('components.whatsapp-button', [
            'phone' => '50235977809',
            'message' => 'Hola, quiero información sobre un traslado privado.',
        ])
        @include('components.language-translator')
        @include('components.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorToast = document.getElementById('toast-wrapper');
            const successToast = document.getElementById('toast-wrapper-success');

            [errorToast, successToast].forEach((container) => {
                if (container) {
                    setTimeout(() => {
                        container.classList.add('toast-out');
                        setTimeout(() => container.remove(), 500);
                    }, 5000);
                }
            });

            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');

                    const icon = menuButton.querySelector('i');

                    if (icon) {
                        icon.classList.toggle('fa-bars');
                        icon.classList.toggle('fa-times');
                    }
                });
            }
        });
    </script>

    @yield('scripts')

</body>

</html>
