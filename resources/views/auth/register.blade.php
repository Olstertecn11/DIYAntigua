@extends('layouts.app', ['navbar' => false])

@section('content')
    <div class="min-h-screen relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.16),_transparent_22%),linear-gradient(135deg,#f8fafc_0%,#eef2ff_50%,#f8fafc_100%)]">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-yellow-300/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-blue-300/20 blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-10 lg:py-16">
            <div class="min-h-[80vh] flex items-center justify-center">
                <div class="w-full max-w-6xl rounded-[2rem] overflow-hidden border border-white/60 bg-white/70 backdrop-blur-xl shadow-[0_30px_100px_rgba(15,23,42,0.18)]">
                    <div class="grid lg:grid-cols-2">

                        <div class="relative px-6 py-8 sm:px-10 lg:px-12 flex flex-col justify-between overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.20),_transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.18),_transparent_26%),linear-gradient(135deg,#eff6ff_0%,#dbeafe_35%,#f8fafc_100%)]">
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="absolute -top-16 right-0 h-40 w-40 rounded-full bg-yellow-300/20 blur-3xl"></div>
                                <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-blue-400/20 blur-3xl"></div>
                            </div>

                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-10">
                                    <div class="flex items-center gap-3">
                                        <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-slate-950 to-slate-800 flex items-center justify-center text-white shadow-lg overflow-hidden">
                                            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true"
                                                alt="DIY Antigua"
                                                class="h-9 w-9 object-contain">
                                        </div>

                                        <div>
                                            <p class="text-sm font-black text-slate-950 mb-0 leading-tight">
                                                DIY Antigua
                                            </p>
                                            <p class="text-xs text-slate-600 mb-0">
                                                Private Transfers
                                            </p>
                                        </div>
                                    </div>

                                    <span class="hidden sm:inline-flex items-center rounded-full bg-white/70 border border-white/60 px-3 py-1 text-[11px] font-black uppercase tracking-[0.16em] text-blue-900 shadow-sm">
                                        Crear cuenta
                                    </span>
                                </div>

                                <div class="mb-8">
                                    <h1 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mb-2">
                                        Crear cuenta
                                    </h1>

                                    <p class="text-slate-600 leading-relaxed mb-0">
                                        Regístrate para guardar tus datos, consultar tus reservas y continuar tus traslados de forma más rápida.
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                                    @csrf

                                    <div>
                                        <label for="name" class="block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700 mb-2">
                                            Nombre completo
                                        </label>

                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i class="fas fa-user"></i>
                                            </span>

                                            <input id="name"
                                                type="text"
                                                name="name"
                                                value="{{ old('name') }}"
                                                required
                                                autocomplete="name"
                                                autofocus
                                                placeholder="Ej. Juan Pérez"
                                                class="w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-4 text-sm font-bold text-slate-900 outline-none transition shadow-sm
                                                @error('name') border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 @else border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100 @enderror">
                                        </div>

                                        @error('name')
                                            <p class="mt-2 text-sm font-bold text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700 mb-2">
                                            Correo electrónico
                                        </label>

                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i class="fas fa-envelope"></i>
                                            </span>

                                            <input id="email"
                                                type="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                required
                                                autocomplete="email"
                                                placeholder="usuario@gmail.com"
                                                class="w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-4 text-sm font-bold text-slate-900 outline-none transition shadow-sm
                                                @error('email') border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 @else border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100 @enderror">
                                        </div>

                                        @error('email')
                                            <p class="mt-2 text-sm font-bold text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <x-phone-input
                                        label="Teléfono"
                                        country-name="telefono_country_code"
                                        number-name="telefono_national"
                                        :value="old('telefono')" />

                                    <div>
                                        <label for="password" class="block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700 mb-2">
                                            Contraseña
                                        </label>

                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i class="fas fa-lock"></i>
                                            </span>

                                            <input id="password"
                                                type="password"
                                                name="password"
                                                required
                                                autocomplete="new-password"
                                                placeholder="Crea una contraseña"
                                                class="w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-12 text-sm font-bold text-slate-900 outline-none transition shadow-sm
                                                @error('password') border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 @else border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100 @enderror">

                                            <button type="button"
                                                id="toggle-password"
                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-blue-800 transition">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>

                                        @error('password')
                                            <p class="mt-2 text-sm font-bold text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password-confirm" class="block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700 mb-2">
                                            Confirmar contraseña
                                        </label>

                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i class="fas fa-shield-halved"></i>
                                            </span>

                                            <input id="password-confirm"
                                                type="password"
                                                name="password_confirmation"
                                                required
                                                autocomplete="new-password"
                                                placeholder="Repite tu contraseña"
                                                class="w-full rounded-full border border-blue-100 bg-white/90 py-3.5 pl-12 pr-12 text-sm font-bold text-slate-900 outline-none transition shadow-sm focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100">

                                            <button type="button"
                                                id="toggle-password-confirm"
                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-blue-800 transition">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="w-full rounded-full bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 px-6 py-3.5 text-sm font-black uppercase tracking-[0.14em] text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(15,23,42,0.28)]">
                                        Crear cuenta
                                    </button>
                                </form>
                            </div>

                            <div class="relative z-10 pt-8">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-blue-100 pt-6">
                                    <p class="text-sm text-slate-600 mb-0">
                                        ¿Ya tienes una cuenta?
                                    </p>

                                    <a href="{{ route('login') }}"
                                        class="inline-flex items-center justify-center rounded-full bg-yellow-400 px-5 py-2.5 text-sm font-black text-slate-950 no-underline shadow-lg shadow-yellow-500/20 hover:bg-yellow-300 transition">
                                        Iniciar sesión
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="relative hidden lg:flex min-h-[720px] bg-slate-950 text-white overflow-hidden">
                            <div class="absolute inset-0">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(250,204,21,0.22),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(59,130,246,0.18),_transparent_24%),linear-gradient(135deg,#020617_0%,#0f172a_35%,#020617_100%)]"></div>

                                <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-yellow-400/20 blur-3xl"></div>
                                <div class="absolute left-0 bottom-0 h-64 w-64 rounded-full bg-blue-500/20 blur-3xl"></div>

                                <div class="absolute inset-0 opacity-95">
                                    <div class="absolute top-[8%] left-[18%] h-[86%] w-24 rotate-[22deg] rounded-full bg-gradient-to-b from-yellow-300/0 via-yellow-400/80 to-yellow-300/0 blur-xl"></div>
                                    <div class="absolute top-[0%] left-[32%] h-[100%] w-20 rotate-[20deg] rounded-full bg-gradient-to-b from-white/0 via-white/25 to-white/0 blur-2xl"></div>
                                    <div class="absolute top-[10%] left-[45%] h-[82%] w-20 rotate-[18deg] rounded-full bg-gradient-to-b from-slate-400/0 via-slate-300/35 to-slate-400/0 blur-2xl"></div>
                                </div>
                            </div>

                            <div class="relative z-10 flex min-h-full w-full flex-col justify-between p-8 xl:p-10">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-black uppercase tracking-[0.18em] text-[#facc15]">
                                        DIY Antigua
                                    </div>

                                    <div class="flex items-center gap-6 text-[11px] font-bold uppercase tracking-[0.16em] text-white/60">
                                        <span>Cuenta</span>
                                        <span>Reservas</span>
                                        <span>Seguridad</span>
                                    </div>
                                </div>

                                <div class="max-w-md ml-auto text-right">
                                    <p class="inline-flex rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-black uppercase tracking-[0.16em] text-yellow-300 mb-5">
                                        Nuevo acceso
                                    </p>

                                    <h2 class="text-5xl xl:text-6xl font-black tracking-tight leading-none mb-4">
                                        Join us<span class="text-yellow-300">.</span>
                                    </h2>

                                    <p class="text-base text-white/70 leading-relaxed">
                                        Crea tu cuenta y disfruta una experiencia más rápida para gestionar tus traslados privados en Guatemala.
                                    </p>
                                </div>

                                <div class="grid grid-cols-3 gap-3 max-w-md ml-auto">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="text-yellow-300 mb-2">
                                            <i class="fas fa-route"></i>
                                        </div>
                                        <p class="text-sm font-black mb-1">Reservas</p>
                                        <p class="text-xs text-white/60 mb-0">Guarda tus viajes.</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="text-blue-300 mb-2">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <p class="text-sm font-black mb-1">Seguridad</p>
                                        <p class="text-xs text-white/60 mb-0">Cuenta protegida.</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="text-white mb-2">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <p class="text-sm font-black mb-1">Rapidez</p>
                                        <p class="text-xs text-white/60 mb-0">Menos datos manuales.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function togglePassword(buttonId, inputId) {
                const button = document.getElementById(buttonId);
                const input = document.getElementById(inputId);

                if (!button || !input) return;

                button.addEventListener('click', function () {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';

                    const icon = this.querySelector('i');

                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }

            togglePassword('toggle-password', 'password');
            togglePassword('toggle-password-confirm', 'password-confirm');
        });
    </script>
@endsection
