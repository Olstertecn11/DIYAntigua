@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-black p-6 md:p-8 text-white">
    <div class="mb-8">
        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-[#fcca00]">Cuenta</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight">Configuración de perfil</h1>
        <p class="mt-2 text-sm text-[#a1a1a1]">Gestiona tus datos de acceso, contacto y configuración operativa.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="mb-6 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm font-semibold text-red-300">
            Revisa los campos marcados antes de continuar.
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-2xl border border-white/10 bg-[#0a0a0a] p-6">
            <h2 class="text-lg font-black">Datos principales</h2>
            <form method="POST" action="{{ Auth::user()->role_id == config('constantes.idAdmin') ? route('admin.profile.update') : route('socios.profile.update') }}" class="mt-6 grid gap-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Nombre</label>
                        <input name="name" value="{{ old('name', $user->name) }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Correo</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Teléfono</label>
                        <input name="telefono" value="{{ old('telefono', $user->telefono) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Dirección</label>
                        <input name="direccion" value="{{ old('direccion', $user->direccion) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                    </div>
                </div>

                @if($user->role_id == config('constantes.idAffiliate') && $user->afiliadoInfo)
                    <div class="mt-2 border-t border-white/10 pt-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-white">Datos del socio Airbnb</h3>
                        <div class="mt-5 grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Nombre comercial</label>
                                <input name="nombre_comercial" value="{{ old('nombre_comercial', $user->afiliadoInfo->nombre_comercial) }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">NIT</label>
                                <input name="nit" value="{{ old('nit', $user->afiliadoInfo->nit) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Teléfono negocio</label>
                                <input name="telefono_negocio" value="{{ old('telefono_negocio', $user->afiliadoInfo->telefono_negocio) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Dirección negocio</label>
                                <input name="direccion_negocio" value="{{ old('direccion_negocio', $user->afiliadoInfo->direccion) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Método de pago</label>
                                <input name="metodo_pago" value="{{ old('metodo_pago', $user->afiliadoInfo->metodo_pago) }}" placeholder="Transferencia, cheque, efectivo" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Titular</label>
                                <input name="titular_pago" value="{{ old('titular_pago', $user->afiliadoInfo->titular_pago) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Cuenta o instrucciones de pago</label>
                                <input name="cuenta_pago" value="{{ old('cuenta_pago', $user->afiliadoInfo->cuenta_pago) }}" class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                            </div>
                        </div>
                    </div>
                @endif

                <button class="mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-[#fcca00] px-5 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00]">
                    <i class="fas fa-check"></i> Guardar perfil
                </button>
            </form>
        </section>

        <section class="rounded-2xl border border-white/10 bg-[#0a0a0a] p-6">
            <h2 class="text-lg font-black">Seguridad</h2>
            <p class="mt-2 text-sm text-[#737373]">Usa una contraseña de mínimo 8 caracteres con letras y números.</p>
            <form method="POST" action="{{ Auth::user()->role_id == config('constantes.idAdmin') ? route('admin.profile.password') : route('socios.profile.password') }}" class="mt-6 grid gap-4">
                @csrf
                @method('PUT')
                <input type="password" name="current_password" required placeholder="Contraseña actual" class="rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                <input type="password" name="password" required placeholder="Nueva contraseña" class="rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                <input type="password" name="password_confirmation" required placeholder="Confirmar nueva contraseña" class="rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">
                <button class="rounded-xl border border-[#fcca00]/70 px-5 py-3 text-sm font-black text-[#fcca00] transition hover:bg-[#fcca00] hover:text-black">Cambiar contraseña</button>
            </form>
        </section>
    </div>
</div>
@endsection
