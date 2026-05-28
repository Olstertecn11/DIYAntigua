@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-[#f7f6f1] py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#fcca00]/40 bg-white px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-[#363636]">
                <i class="fas fa-user-gear text-[#fcca00]"></i> Mi cuenta
            </span>
            <h1 class="mt-4 text-3xl md:text-4xl font-black text-[#111111]">Configuración de perfil</h1>
            <p class="mt-2 text-sm text-[#666666]">Mantén tus datos listos para reservar más rápido y recibir tus comprobantes sin errores.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                Revisa los campos marcados antes de continuar.
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="rounded-[1.75rem] border border-black/10 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-black text-[#111111]">Datos personales</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 grid gap-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Nombre completo</label>
                        <input name="name" value="{{ old('name', $user->name) }}" required class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                        @error('name')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Correo</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                            @error('email')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-phone-input
                                label="Teléfono"
                                country-name="telefono_country_code"
                                number-name="telefono_national"
                                :value="$user->telefono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Dirección frecuente</label>
                        <input name="direccion" value="{{ old('direccion', $user->direccion) }}" class="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20">
                        @error('direccion')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button class="inline-flex items-center justify-center gap-2 rounded-full bg-[#fcca00] px-6 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00]">
                        <i class="fas fa-check"></i> Guardar cambios
                    </button>
                </form>
            </section>

            <section class="rounded-[1.75rem] border border-black/10 bg-[#111111] p-6 text-white shadow-sm">
                <h2 class="text-xl font-black">Seguridad</h2>
                <p class="mt-2 text-sm text-white/60">Cambia tu contraseña periódicamente y usa una combinación segura.</p>

                <form method="POST" action="{{ route('profile.password') }}" class="mt-6 grid gap-4">
                    @csrf
                    @method('PUT')

                    <input type="password" name="current_password" placeholder="Contraseña actual" required class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    @error('current_password')<p class="text-xs font-semibold text-red-300">{{ $message }}</p>@enderror

                    <input type="password" name="password" placeholder="Nueva contraseña" required class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    @error('password')<p class="text-xs font-semibold text-red-300">{{ $message }}</p>@enderror

                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">

                    <button class="rounded-full border border-[#fcca00]/70 px-6 py-3 text-sm font-black text-[#fcca00] transition hover:bg-[#fcca00] hover:text-black">
                        Actualizar contraseña
                    </button>
                </form>
            </section>
        </div>
    </div>
</main>
@endsection
