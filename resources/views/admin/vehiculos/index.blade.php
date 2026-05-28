@extends('layouts.admin')

@section('content')
<div x-data="{ openmodal: false, editMode: false, currentVehiculo: {} }" class="p-8 max-w-7xl mx-auto text-white">

    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold uppercase tracking-tight">Flota de Vehículos</h1>
            <p class="text-sm text-[#a1a1a1]">Administra los tipos de transporte y sus capacidades.</p>
        </div>
        <button @click="openmodal = true; editMode = false; currentVehiculo = {}"
                class="bg-white text-black px-6 py-2 rounded-md font-bold text-xs hover:bg-gray-200 shadow-lg uppercase">
            <i class="fas fa-plus mr-2"></i> Nuevo Vehículo
        </button>
    </header>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-xl overflow-hidden shadow-2xl">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#050505] text-[10px] uppercase text-[#737373] tracking-widest border-b border-[#262626]">
                <tr>
                    <th class="px-6 py-4">Tipo de Vehículo</th>
                    <th class="px-6 py-4 text-center">Capacidad</th>
                    <th class="px-6 py-4 text-center">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626]">
                @foreach($vehiculos as $v)
                <tr class="hover:bg-[#111] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-[#1a1a1a] rounded-lg flex items-center justify-center border border-[#262626]">
                                <i class="fas {{ $v->icono ?? 'fa-car' }} text-yellow-500"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-white uppercase">{{ $v->nombre }}</span>
                                <span class="text-[10px] text-[#555]">{{ $v->min_pasajeros }}-{{ $v->max_pasajeros }} pasajeros</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-black border border-[#262626] px-3 py-1 rounded-full text-xs " style="color: #a1a1a1">
                            <i class="fas fa-users mr-1 opacity-50"></i> {{ $v->min_pasajeros }} - {{ $v->max_pasajeros }} pers.
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($v->activo)
                            <span class="text-[10px] uppercase font-bold text-green-500 bg-green-500/10 px-2 py-1 rounded">Activo</span>
                        @else
                            <span class="text-[10px] uppercase font-bold text-red-500 bg-red-500/10 px-2 py-1 rounded">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">

                            <button @click="openmodal = true; editMode = true; currentVehiculo = {{ json_encode($v) }}"
                             class="flex items-center justify-center w-9 h-9 rounded-lg text-[#737373] hover:text-white hover:bg-[#1a1a1a] transition-all border border-transparent hover:border-[#262626]"
                             title="Editar">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <form action="{{ route('admin.vehiculos.destroy', $v) }}"
                                  method="post"
                                  class="inline-block"
                                  onsubmit="return confirm('¿Eliminar este tipo de vehículo?')">
                                  @csrf
                                  @method('delete')
                                  <button type="submit"
                                          class="mt-2 flex items-center justify-center w-9 h-9 rounded-lg text-red-500/40 hover:text-red-500 hover:bg-red-500/10 transition-all border border-transparent hover:border-red-500/20"
                                          title="Eliminar">
                                      <i class="fas fa-trash text-sm"></i>
                                  </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openmodal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4" x-cloak x-transition>
        <div class="bg-[#0a0a0a] border border-[#262626] w-full max-w-md rounded-2xl p-8 shadow-2xl" @click.away="openmodal = false">
            <h2 class="text-xl font-bold mb-6" x-text="editMode ? 'Editar Vehículo' : 'Nuevo Vehículo'"></h2>

            <form :action="editMode ? `/admin/vehiculos/${currentVehiculo.id}` : '{{ route('admin.vehiculos.store') }}'" method="post" class="space-y-5">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="text-[10px] uppercase font-bold text-[#737373]">Nombre del Tipo</label>
                    <input type="text" name="nombre" x-model="currentVehiculo.nombre" required placeholder="Ej: SUV Premium, Microbús..."
                                                                                               class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white focus:border-yellow-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] uppercase font-bold text-[#737373]">Mín. Pasajeros</label>
                        <input type="number" name="min_pasajeros" x-model="currentVehiculo.min_pasajeros" required min="1"
                                                                                                                   class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold text-[#737373]">Máx. Pasajeros</label>
                        <input type="number" name="max_pasajeros" x-model="currentVehiculo.max_pasajeros" required min="1"
                                                                                                                   class="w-full bg-black border border-[#262626] rounded-lg p-3 mt-1 text-white outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-[#050505] p-3 rounded-lg border border-[#262626]">
                    <input type="checkbox" name="activo" id="activo" x-model="currentVehiculo.activo"
                                                                     class="w-4 h-4 accent-yellow-500">
                    <label for="activo" class="text-xs font-bold uppercase text-white cursor-pointer">Vehículo Disponible</label>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="openmodal = false" class="flex-1 text-[#a1a1a1] font-bold py-3 hover:text-white transition-colors uppercase text-[10px]">Cancelar</button>
                    <button type="submit" class="flex-1 bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-200 transition-all uppercase text-[10px]"
                                          x-text="editMode ? 'Actualizar' : 'Guardar'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
