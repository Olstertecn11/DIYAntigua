@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-[#f7f6f1] py-12">
    <div class="mx-auto grid min-h-[70vh] max-w-6xl items-center gap-8 px-4 lg:grid-cols-[0.9fr_1.1fr]">
        <section class="hidden lg:block">
            <div class="rounded-[2rem] bg-[#111111] p-8 text-white shadow-2xl">
                <img src="{{ asset('images/logo.png') }}" alt="DYANTIGUA" class="h-16 w-auto">
                <h1 class="mt-10 text-4xl font-black leading-tight">Recupera el acceso sin perder el ritmo del viaje.</h1>
                <p class="mt-4 text-sm leading-6 text-white/60">Te enviaremos un enlace seguro para restablecer tu contraseña. Funciona para clientes, administradores y socios autorizados.</p>
                <div class="mt-8 rounded-2xl border border-[#fcca00]/30 bg-[#fcca00]/10 p-5 text-sm font-semibold text-[#fcca00]">
                    El enlace expira automáticamente para proteger tu cuenta.
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-black/10 bg-white p-6 shadow-sm md:p-10">
            <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#fcca00]">Seguridad</p>
            <h2 class="mt-3 text-3xl font-black text-[#111111]">Restablecer contraseña</h2>
            <p class="mt-2 text-sm text-[#666666]">Ingresa el correo asociado a tu cuenta y revisa tu bandeja de entrada.</p>

            @if (session('status'))
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                    @error('email')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                </div>

                <button class="flex w-full items-center justify-center gap-2 rounded-full bg-[#fcca00] px-6 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00]">
                    <i class="fas fa-paper-plane"></i> Enviar enlace seguro
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-black text-[#363636] hover:text-black">Volver a iniciar sesión</a>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection
