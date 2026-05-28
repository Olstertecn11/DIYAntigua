@extends('layouts.admin')

@section('content')
<div x-data="{ openModal: false }" class="p-8 max-w-7xl mx-auto">
    <header class="mb-10 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Lugares y Hoteles</h1>
            <p class="text-sm text-[#a1a1a1]">Registra los puntos de origen y destino del sistema.</p>
        </div>
        <button @click="openModal = true" class="bg-white hover:bg-gray-200 text-black font-bold py-2 px-6 rounded-md text-xs transition-all">
            <i class="fas fa-plus mr-2"></i> Nuevo Lugar
        </button>
    </header>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm text-white">
            <thead class="bg-[#050505] text-[#737373] text-[10px] uppercase tracking-widest border-b border-[#262626]">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nombre del Lugar / Hotel</th>
                    <th class="px-6 py-4">Ciudad / Depto</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626]">
                @foreach($lugares as $lugar)
                <tr class="hover:bg-[#111] transition-colors">
                    <td class="px-6 py-4 text-[#737373]">{{ strtoupper(substr($lugar->nombre, 0, 3)) }}</td>
                    <td class="px-6 py-4 font-bold">{{ $lugar->nombre }}</td>
                    <td class="px-6 py-4 text-[#a1a1a1]">{{ $lugar->ciudad ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.lugares.destroy', $lugar) }}" method="POST" onsubmit="return confirm('¿Eliminar lugar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500/50 hover:text-red-500 transition-colors text-xs font-bold uppercase">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm" x-cloak>
        <div class="bg-[#0a0a0a] rounded-xl border border-[#262626] w-full max-w-md p-8" @click.away="openModal = false">
            <h2 class="text-xl font-bold text-white mb-6">Agregar Nuevo Punto</h2>
            <form action="{{ route('admin.lugares.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold uppercase text-[#737373] block mb-1">Nombre (Ej: Aeropuerto La Aurora)</label>
                        <input type="text" name="nombre" required class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-[#737373] block mb-1">Ciudad o Departamento</label>
                        <input type="text" name="ciudad" placeholder="Ej: Guatemala" class="w-full bg-black border border-[#262626] rounded-md px-3 py-2 text-sm text-white focus:border-white transition-all">
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" @click="openModal = false" class="flex-1 px-4 py-2 border border-[#262626] rounded-md text-xs font-bold text-[#a1a1a1]">Cancelar</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-white text-black rounded-md text-xs font-bold hover:bg-gray-200">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
