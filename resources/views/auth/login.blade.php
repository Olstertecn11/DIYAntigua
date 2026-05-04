@extends('layouts.app', ['navbar' => false])

@section('content')
    <div class="min-h-screen relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.14),_transparent_20%),linear-gradient(135deg,#f8fafc_0%,#eef4ff_45%,#f8fafc_100%)]">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-yellow-300/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-blue-300/20 blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-10 lg:py-16">
            <div class="min-h-[80vh] flex items-center justify-center">
                <div class="w-full max-w-6xl rounded-[2rem] overflow-hidden border border-white/60 bg-white/70 backdrop-blur-xl shadow-[0_30px_100px_rgba(15,23,42,0.18)]">
                    <div class="grid lg:grid-cols-2">

                        <div class="relative px-6 py-8 sm:px-10 lg:px-12 flex flex-col justify-between overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.18),_transparent_22%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.18),_transparent_25%),linear-gradient(135deg,#eff6ff_0%,#dbeafe_35%,#f8fafc_100%)]">
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="absolute -top-16 right-0 h-40 w-40 rounded-full bg-yellow-300/20 blur-3xl"></div>
                                <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-blue-400/20 blur-3xl"></div>
                            </div>

                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-10">
                                    <div class="flex items-center gap-3">
                                        <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-slate-950 to-slate-800 flex items-center justify-center text-white shadow-lg">
                                            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true" alt="">
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

                                    <span class="inline-flex items-center rounded-full bg-white/70 border border-white/60 px-3 py-1 text-[11px] font-black uppercase tracking-[0.16em] text-blue-900 shadow-sm">
                                        Acceso privado
                                    </span>
                                </div>

                                <div class="mb-8">
                                    <h1 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mb-2">
                                        Iniciar sesión
                                    </h1>

                                    <p class="text-slate-600 leading-relaxed mb-0">
                                        Accede a tu cuenta para gestionar tus reservas, continuar tu proceso y guardar tus datos de viaje.
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                    @csrf

                                    <div>
                                        <label for="email" class="block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700 mb-2">
                                            Correo electrónico
                                        </label>

                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i class="fas fa-user"></i>
                                            </span>

                                            <input id="email"
                                                type="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                required
                                                autocomplete="email"
                                                autofocus
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
                                                autocomplete="current-password"
                                                placeholder="Ingresa tu contraseña"
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

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <label for="remember" class="inline-flex items-center gap-3 cursor-pointer">
                                            <input id="remember"
                                                type="checkbox"
                                                name="remember"
                                                {{ old('remember') ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-slate-300 text-yellow-500 focus:ring-yellow-400">

                                            <span class="text-sm font-semibold text-slate-700">
                                                Recordarme
                                            </span>
                                        </label>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}"
                                                class="text-sm font-bold text-blue-700 hover:text-blue-900 no-underline">
                                                ¿Olvidaste tu contraseña?
                                            </a>
                                        @endif
                                    </div>

                                    <button type="submit"
                                        class="w-full rounded-full bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 px-6 py-3.5 text-sm font-black uppercase tracking-[0.14em] text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(15,23,42,0.28)]">
                                        Login
                                    </button>
                                </form>
                            </div>

                            <div class="relative z-10 pt-8">
                                @if (Route::has('register'))
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-blue-100 pt-6">
                                        <p class="text-sm text-slate-600 mb-0">
                                            ¿Aún no tienes una cuenta?
                                        </p>

                                        <a href="{{ route('register') }}"
                                            class="inline-flex items-center justify-center rounded-full bg-yellow-400 px-5 py-2.5 text-sm font-black text-slate-950 no-underline shadow-lg shadow-yellow-500/20 hover:bg-yellow-300 transition">
                                            Crear cuenta
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="relative hidden lg:flex min-h-[680px] bg-slate-950 text-white overflow-hidden">
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
                                        <span>Reservas</span>
                                        <span>Seguridad</span>
                                        <span>Acceso</span>
                                    </div>
                                </div>

                                <div class="max-w-md ml-auto text-right">
                                    <p class="inline-flex rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-black uppercase tracking-[0.16em] text-yellow-300 mb-5">
                                        Premium Access
                                    </p>

                                    <h2 class="text-5xl xl:text-6xl font-black tracking-tight leading-none mb-4">
                                        Welcome<span class="text-yellow-300">.</span>
                                    </h2>

                                    <p class="text-base text-white/70 leading-relaxed">
                                        Accede a tu cuenta y continúa con tu experiencia de reservas privadas de forma rápida, segura y elegante.
                                    </p>
                                </div>

                                <div class="grid grid-cols-3 gap-3 max-w-md ml-auto">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="text-yellow-300 mb-2">
                                            <i class="fas fa-route"></i>
                                        </div>
                                        <p class="text-sm font-black mb-1">Reservas</p>
                                        <p class="text-xs text-white/60 mb-0">Gestiona tus viajes.</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="text-blue-300 mb-2">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <p class="text-sm font-black mb-1">Seguridad</p>
                                        <p class="text-xs text-white/60 mb-0">Acceso protegido.</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="text-white mb-2">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <p class="text-sm font-black mb-1">Rapidez</p>
                                        <p class="text-xs text-white/60 mb-0">Continúa tu flujo.</p>
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
            const togglePassword = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';

                    const icon = this.querySelector('i');

                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
        });
    </script>
@endsection
