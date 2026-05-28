@extends('layouts.app', ['navbar' => false])

@section('content')
<main class="min-h-screen bg-[#f7f6f1]">
    <div class="grid min-h-screen lg:grid-cols-[0.9fr_1.1fr]">
        <section class="flex items-center justify-center bg-white px-8 py-12">
            <div class="max-w-md">
                <img src="{{ asset('images/logo.png') }}" alt="DYANTIGUA" class="h-20 w-auto">
                <p class="mt-10 text-[11px] font-black uppercase tracking-[0.22em] text-[#fcca00]">Socios Airbnb</p>
                <h1 class="mt-3 text-4xl font-black leading-tight text-[#111111]">Tus referidos, reservas y ganancias en un solo lugar.</h1>
                <p class="mt-4 text-sm leading-6 text-[#666666]">Comparte tu enlace con huéspedes y revisa el rendimiento de tus recomendaciones.</p>
            </div>
        </section>

        <section class="relative flex items-center justify-center overflow-hidden bg-[#111111] px-6 py-12 text-white">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#fcca00]"></div>
            <div class="w-full max-w-md rounded-[2rem] border border-white/10 bg-white/[0.04] p-8 shadow-2xl">
                <h2 class="text-2xl font-black">Iniciar sesión</h2>
                <p class="mt-2 text-sm text-white/60">Acceso para socios afiliados.</p>

                @if(isset($errors) && $errors->any())
                    <div class="mt-5 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm font-semibold text-red-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf
                    <input type="email" name="email" placeholder="Correo" required autocomplete="email" autofocus class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password" class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">

                    <button class="flex w-full items-center justify-center gap-2 rounded-full bg-[#fcca00] px-6 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00]">
                        <i class="fas fa-link"></i> Entrar
                    </button>

                    <a href="{{ route('password.request') }}" class="block text-center text-sm font-bold text-white/70 hover:text-[#fcca00]">Olvidé mi contraseña</a>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection
