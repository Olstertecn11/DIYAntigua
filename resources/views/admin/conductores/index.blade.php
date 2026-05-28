@extends('layouts.admin')

@section('content')
<div x-data="{ openModal: false }" class="p-8 max-w-7xl mx-auto">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-500 text-sm flex items-center">
            <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
        </div>
    @endif

    <header class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Conductores</h1>
            <p class="text-sm text-[#a1a1a1]">Gestión de flota y personal operativo.</p>
        </div>
        <button @click="openModal = true" class="bg-white hover:bg-gray-200 text-black font-bold py-2 px-6 rounded-md text-xs transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)]">
            <i class="fas fa-plus mr-2"></i> Nuevo Conductor
        </button>
    </header>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#050505] text-[#737373] text-[10px] uppercase tracking-widest border-b border-[#262626]">
                <tr>
                    <th class="px-6 py-4">Conductor</th>
                    <th class="px-6 py-4">Vehículo / Placa</th>
                    <th class="px-6 py-4">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626]">
                @foreach($conductores as $conductor)
                <tr class="hover:bg-[#111] transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-white font-medium">{{ $conductor->nombre }}</div>
                        <div class="text-[10px] text-[#737373]">{{ $conductor->telefono }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[#a1a1a1]">{{ $conductor->vehiculo_modelo }}</div>
                        <div class="text-[10px] text-yellow-500 font-bold uppercase tracking-tighter">{{ $conductor->placa }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $conductor->estado == 'activo' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                            {{ $conductor->estado ?? 'activo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.conductores.destroy', $conductor) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar conductor?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500/50 hover:text-red-500 transition-colors text-xs uppercase font-bold">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm" x-cloak>
        <div class="bg-[#0a0a0a] rounded-xl border border-[#262626] w-full max-w-md overflow-hidden" @click.away="openModal = false">
            <div class="p-8">
                <h2 class="text-xl font-bold text-white mb-6">Registrar Conductor</h2>
                <form action="{{ route('admin.conductores.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Nombre Completo</label>
                            <input type="text" name="nombre" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Teléfono / WhatsApp</label>
                            <input type="text" name="telefono" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Modelo Vehículo</label>
                                <input type="text" name="vehiculo_modelo" placeholder="Ej. Toyota Fortuner" class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-widest text-[#737373] block mb-1">Placa</label>
                                <input type="text" name="placa" required placeholder="P-000XXX" class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white transition-all">
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 px-4 py-2 border border-[#262626] rounded-md text-xs font-bold text-[#a1a1a1] hover:bg-[#111]">Cancelar</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-white text-black rounded-md text-xs font-bold hover:bg-gray-200 transition-all">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
