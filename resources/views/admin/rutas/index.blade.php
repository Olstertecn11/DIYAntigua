@extends('layouts.admin')

@section('content')
<div x-data="{
    openmodal: false,
    selectedVehiculo: '',
    vehiculosAgregados: [],
    addVehiculo() {
        if(!this.selectedVehiculo) return;
        const v = JSON.parse(this.selectedVehiculo);
        // Evitar duplicados
        if(this.vehiculosAgregados.find(item => item.id === v.id)) return;

        this.vehiculosAgregados.push({ id: v.id, nombre: v.nombre, precio: 0 });
        this.selectedVehiculo = '';
    },
    removeVehiculo(index) {
        this.vehiculosAgregados.splice(index, 1);
    }
}" class="p-8 max-w-7xl mx-auto text-white">

    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold uppercase">Tarifario Operativo</h1>
            <p class="text-sm text-[#a1a1a1]">Gestión dinámica de rutas por tipo de vehículo.</p>
        </div>
        <button @click="openmodal = true" class="bg-white text-black px-6 py-2 rounded-md font-bold text-xs hover:bg-gray-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i> NUEVA RUTA
        </button>
    </header>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#050505] text-[10px] uppercase text-[#737373] tracking-widest border-b border-[#262626]">
                <tr>
                    <th class="px-6 py-4">Ruta (Origen a Destino)</th>
                    <th class="px-6 py-4">Vehículos Disponibles y Precios</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626]">
                @foreach($rutas as $ruta)
                <tr class="hover:bg-[#111] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-white">{{ $ruta->origen->nombre }}</span>
                            <i class="fas fa-arrow-right text-yellow-500 text-[10px]"></i>
                            <span class="font-bold text-white">{{ $ruta->destino->nombre }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($ruta->vehiculosDisponibles as $v)
                                <span class="bg-[#1a1a1a] border border-[#262626] px-3 py-1 rounded-full text-[11px] text-green-500 font-mono">
                                    <strong class="text-yellow-500">{{ $v->nombre }}:</strong>
                                    Q.{{ number_format($v->pivot->precio_tarifa, 2) }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.rutas.destroy', $ruta->id) }}" method="post">
                            @csrf @method('delete')
                            <button class="text-red-500/40 hover:text-red-500 transition-colors"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openmodal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4" x-cloak x-transition>
        <div class="bg-[#0a0a0a] border border-[#262626] w-full max-w-3xl rounded-2xl p-8 max-h-[90vh] overflow-y-auto" @click.away="openmodal = false">
            <h2 class="text-xl font-bold mb-6">Configurar Nueva Ruta y Vehículos</h2>

            <form action="{{ route('admin.rutas.store') }}" method="post" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] uppercase font-bold text-[#737373]">Lugar de Origen</label>
                        <select name="origen_id" required class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white focus:border-white outline-none">
                            @foreach($lugares as $lugar) <option value="{{ $lugar->id }}">{{ $lugar->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold text-[#737373]">Lugar de Destino</label>
                        <select name="destino_id" required class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white focus:border-white outline-none">
                            @foreach($lugares as $lugar) <option value="{{ $lugar->id }}">{{ $lugar->nombre }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-[#050505] border border-[#262626] p-4 rounded-xl">
                    <label class="text-[10px] uppercase font-bold text-yellow-500 block mb-2">Asignar Vehículo a esta Ruta</label>
                    <div class="flex gap-2">
                        <select x-model="selectedVehiculo" class="flex-1 bg-black border border-[#262626] rounded-lg p-2 text-white outline-none">
                            <option value="">Selecciona un tipo de vehículo...</option>
                            @foreach($vehiculos as $v)
                                <option value="{{ json_encode(['id' => $v->id, 'nombre' => $v->nombre]) }}">{{ $v->nombre }} (Cap: {{ $v->max_pasajeros }})</option>
                            @endforeach
                        </select>
                        <button type="button" @click="addVehiculo()" class="bg-yellow-600 hover:bg-yellow-500 text-black px-4 rounded-lg font-bold text-xs uppercase">
                            Agregar
                        </button>
                    </div>

                    <div class="mt-4">
                        <template x-if="vehiculosAgregados.length > 0">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-[#737373] border-b border-[#262626]">
                                        <th class="py-2">Vehículo</th>
                                        <th class="py-2">Tarifa (Q)</th>
                                        <th class="py-2 text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in vehiculosAgregados" :key="index">
                                        <tr class="border-b border-[#1a1a1a]">
                                            <td class="py-3 font-bold text-white" x-text="item.nombre"></td>
                                            <td class="py-3">
                                                <input type="hidden" :name="'vehiculos['+index+'][id]'" :value="item.id">
                                                <input type="number" step="0.01" :name="'vehiculos['+index+'][precio]'"
                                                       x-model="item.precio" required
                                                       class="bg-black border border-[#262626] rounded px-2 py-1 w-32 text-green-500 font-mono focus:border-green-500 outline-none">
                                            </td>
                                            <td class="py-3 text-right">
                                                <button type="button" @click="removeVehiculo(index)" class="text-red-500 hover:text-red-400">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                        <template x-if="vehiculosAgregados.length === 0">
                            <p class="text-[10px] text-[#4a4a4a] text-center py-4 italic">No has agregado vehículos a esta ruta aún.</p>
                        </template>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="openmodal = false; vehiculosAgregados = []" class="flex-1 text-[#a1a1a1] font-bold py-3 hover:text-white transition-colors uppercase text-xs">Cancelar</button>
                    <button type="submit" class="flex-1 bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-200 transition-all uppercase text-xs">Guardar Ruta Completa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
