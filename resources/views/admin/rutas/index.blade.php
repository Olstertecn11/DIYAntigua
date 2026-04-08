@extends('layouts.admin')

@section('content')
<div x-data="{ openModal: false }" class="p-8 max-w-7xl mx-auto text-white">
    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">Tarifario Operativo</h1>
            <p class="text-sm text-[#a1a1a1]">Define los precios entre los puntos registrados.</p>
        </div>
        <button @click="openModal = true" class="bg-white text-black px-6 py-2 rounded-md font-bold text-xs hover:bg-gray-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i> NUEVA TARIFA
        </button>
    </header>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#050505] text-[10px] uppercase text-[#737373] tracking-widest border-b border-[#262626]">
                <tr>
                    <th class="px-6 py-4 text-center">Ruta (Origen a Destino)</th>
                    <th class="px-6 py-4">Sedán</th>
                    <th class="px-6 py-4">SUV</th>
                    <th class="px-6 py-4">Bus</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626]">
                @foreach($rutas as $ruta)
                <tr class="hover:bg-[#111] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-3">
                            <span class="font-bold text-white">{{ $ruta->origen->nombre }}</span>
                            <i class="fas fa-arrow-right text-yellow-500 text-[10px]"></i>
                            <span class="font-bold text-white">{{ $ruta->destino->nombre }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-green-500">Q{{ number_format($ruta->precio_sedan, 2) }}</td>
                    <td class="px-6 py-4 font-mono text-blue-400">Q{{ number_format($ruta->precio_suv, 2) }}</td>
                    <td class="px-6 py-4 font-mono text-purple-400">Q{{ number_format($ruta->precio_bus, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.rutas.destroy', $ruta->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-500/40 hover:text-red-500 transition-colors"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4" x-cloak>
        <div class="bg-[#0a0a0a] border border-[#262626] w-full max-w-2xl rounded-2xl p-8" @click.away="openModal = false">
            <h2 class="text-xl font-bold mb-6">Configurar Nueva Tarifa</h2>

            <form action="{{ route('admin.rutas.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] uppercase font-bold text-[#737373]">Lugar de Origen</label>
                        <select name="origen_id" required class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white focus:border-white outline-none">
                            <option value="">Selecciona Origen</option>
                            @foreach($lugares as $lugar) <option value="{{ $lugar->id }}">{{ $lugar->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold text-[#737373]">Lugar de Destino</label>
                        <select name="destino_id" required class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white focus:border-white outline-none">
                            <option value="">Selecciona Destino</option>
                            @foreach($lugares as $lugar) <option value="{{ $lugar->id }}">{{ $lugar->nombre }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 bg-[#050505] p-6 rounded-xl border border-[#262626]">
                    <div>
                        <label class="text-[10px] uppercase font-bold text-green-500 block mb-2">Precio Sedán (Q)</label>
                        <input type="number" step="0.01" name="precio_sedan" required class="w-full bg-black border border-[#262626] rounded-lg p-2 text-white">
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold text-blue-400 block mb-2">Precio SUV (Q)</label>
                        <input type="number" step="0.01" name="precio_suv" required class="w-full bg-black border border-[#262626] rounded-lg p-2 text-white">
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold text-purple-400 block mb-2">Precio Bus (Q)</label>
                        <input type="number" step="0.01" name="precio_bus" required class="w-full bg-black border border-[#262626] rounded-lg p-2 text-white">
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 text-[#a1a1a1] font-bold py-3 hover:text-white transition-colors">Cancelar</button>
                    <button type="submit" class="flex-1 bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-200 transition-all uppercase text-xs">Guardar Tarifa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
