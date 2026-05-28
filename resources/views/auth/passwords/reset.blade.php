@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-[#f7f6f1] py-12">
    <div class="mx-auto grid min-h-[70vh] max-w-6xl items-center gap-8 px-4 lg:grid-cols-[0.9fr_1.1fr]">
        <section class="hidden lg:block">
            <div class="rounded-[2rem] bg-[#111111] p-8 text-white shadow-2xl">
                <img src="{{ asset('images/logo.png') }}" alt="DYANTIGUA" class="h-16 w-auto">
                <h1 class="mt-10 text-4xl font-black leading-tight">Crea una contraseña nueva y segura.</h1>
                <p class="mt-4 text-sm leading-6 text-white/60">Tu cuenta protege reservas, comprobantes, historial de viajes y paneles operativos.</p>
            </div>
        </section>

        <section class="rounded-[2rem] border border-black/10 bg-white p-6 shadow-sm md:p-10">
            <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#fcca00]">Cuenta</p>
            <h2 class="mt-3 text-3xl font-black text-[#111111]">Nueva contraseña</h2>
            <p class="mt-2 text-sm text-[#666666]">Usa al menos 8 caracteres e incluye letras y números.</p>

            <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Correo</label>
                    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                    @error('email')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                    @error('password')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password-confirm" class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Confirmar contraseña</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                </div>

                <button class="flex w-full items-center justify-center gap-2 rounded-full bg-[#fcca00] px-6 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00]">
                    <i class="fas fa-lock"></i> Guardar contraseña
                </button>
            </form>
        </section>
    </div>
</main>
@endsection
