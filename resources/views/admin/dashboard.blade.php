@extends('layouts.admin')

@section('content')
<div x-data="{ openModal: false }" class="p-8 max-w-7xl mx-auto">
    <header class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Panel de Administración</h1>
            <p class="text-sm text-[#a1a1a1]">Gestión de socios, reservas y logística de traslados.</p>
        </div>
        <div class="flex gap-3">
            <button @click="openModal = true"
                    class="bg-white hover:bg-gray-200 text-black font-bold py-2 px-6 rounded-md text-xs transition-all flex items-center shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                <i class="fas fa-plus mr-2"></i> Nuevo Socio
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-[#0a0a0a] border border-[#262626] p-6 rounded-xl">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#737373] mb-1">Total Afiliados</p>
            <h3 class="text-2xl font-bold text-white">{{ count($afiliados) }}</h3>
        </div>
        <div class="bg-[#0a0a0a] border border-[#262626] p-6 rounded-xl">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#737373] mb-1">Reservas Totales</p>
            <h3 class="text-2xl font-bold text-white">{{ $reservasStats['total'] ?? 0 }}</h3>
        </div>
        <div class="bg-[#0a0a0a] border border-[#262626] p-6 rounded-xl">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#737373] mb-1">Viajes Hoy</p>
            <h3 class="text-2xl font-bold text-[#FCCA00]">{{ $reservasStats['hoy'] ?? 0 }}</h3>
        </div>
        <div class="bg-[#0a0a0a] border border-[#262626] p-6 rounded-xl">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#737373] mb-1">Reembolsos Pendientes</p>
            <h3 class="text-2xl font-bold text-cyan-400">{{ $reservasStats['reembolsos'] ?? 0 }}</h3>
        </div>
        <div class="bg-[#0a0a0a] border border-[#262626] p-6 rounded-xl">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#737373] mb-1">Comisiones</p>
            <h3 class="text-2xl font-bold text-[#FCCA00]">Q{{ number_format((float) ($reservasStats['comisiones'] ?? 0), 2) }}</h3>
        </div>
    </div>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl mb-8">
        <div class="p-6 border-b border-[#262626] bg-[#050505]/50 flex items-center justify-between">
            <h5 class="text-sm font-bold uppercase tracking-widest text-white">Próximas Reservas</h5>
            <a href="{{ route('admin.reservas.index') }}" class="text-[#FCCA00] text-xs font-black uppercase">Ver todas</a>
        </div>
        <div class="divide-y divide-[#171717]">
            @forelse (($proximasReservas ?? []) as $reserva)
                <a href="{{ route('admin.reservas.show', $reserva) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 p-4 hover:bg-[#101010] transition no-underline">
                    <span class="text-[#FCCA00] font-black text-sm">{{ $reserva->codigo_reserva }}</span>
                    <span class="text-white text-sm font-bold">{{ $reserva->nombre_cliente }}</span>
                    <span class="text-[#a3a3a3] text-sm">{{ $reserva->ruta?->origen?->nombre }} → {{ $reserva->ruta?->destino?->nombre }}</span>
                    <span class="text-right text-white text-sm font-black">{{ $reserva->travelDateTime()->format('d/m H:i') }}</span>
                </a>
            @empty
                <div class="p-6 text-[#737373] text-sm font-bold">No hay reservas próximas.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-[#262626] bg-[#050505]/50">
            <h5 class="text-sm font-bold uppercase tracking-widest text-white">Gestión de Afiliados Activos</h5>
        </div>

        <div class="p-0"> {{-- Quitamos padding extra para que la tabla llegue a los bordes --}}
            @include('admin.modules.afiliados_table', ['afiliados' => $afiliados])
        </div>
    </div>

    <div x-show="openModal"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-cloak>

        <div class="bg-[#0a0a0a] rounded-xl border border-[#262626] w-full max-w-md overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.5)]"
             @click.away="openModal = false">

            <div class="p-8">
                <h2 class="text-xl font-bold text-white mb-1">Registrar Nuevo Socio</h2>
                <p class="text-xs text-[#a1a1a1] mb-6">El socio recibirá sus credenciales por correo.</p>

                <form action="{{ route('admin.afiliados.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Nombre</label>
                            <input type="text" name="name" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white focus:ring-0 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Email</label>
                            <input type="email" name="email" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Comercial</label>
                                <input type="text" name="nombre_comercial" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white focus:ring-0 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Comisión %</label>
                                <input type="number" name="comision" value="10" class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white focus:ring-0 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Contraseña</label>
                            <input type="password" name="password" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white focus:ring-0 transition-all">
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 px-4 py-2 border border-[#262626] rounded-md text-xs font-bold text-[#a1a1a1] hover:bg-[#111]">Cancelar</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-white text-black rounded-md text-xs font-bold hover:bg-gray-200 transition-all">Guardar Socio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
