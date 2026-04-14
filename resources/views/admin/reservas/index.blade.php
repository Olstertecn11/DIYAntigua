@extends('layouts.admin')

@section('content')
<div class="min-h-screen p-6 bg-[#050505]">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase">Gestión de Reservas</h1>
            <p class="text-[#737373] text-sm font-medium">Monitoreo en tiempo real de traslados programados.</p>
        </div>

        <div class="flex gap-2">
            <button class="bg-[#171717] border border-[#262626] text-white px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-[#262626] transition-all">
                <i class="fas fa-download mr-2"></i> Exportar
            </button>
        </div>
    </div>

    {{-- Barra de Filtros --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="relative">
            <input type="text" placeholder="Buscar código o cliente..."
                               class="w-full bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-2 text-white text-xs focus:border-[#ffc107] transition-all outline-none">
        </div>

        <select class="bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-2 text-white text-xs focus:border-[#ffc107] outline-none">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="pagado">Pagado</option>
        </select>

        <input type="date" class="bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-2 text-white text-xs focus:border-[#ffc107] outline-none">

        <button class="bg-[#ffc107] text-black font-black uppercase text-[10px] rounded-xl px-4 py-2 hover:bg-yellow-500 transition-all">
            Filtrar Resultados
        </button>
    </div>
    {{-- Tabla de Reservaciones --}}
    <div class="bg-[#0a0a0a] border border-[#262626] rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#262626] bg-[#0d0d0d]">
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#525252] font-black">Reserva / Cliente</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#525252] font-black">Ruta y Vehículo</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#525252] font-black">Fecha y Hora</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#525252] font-black">Estado Pago</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#525252] font-black">Total</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#525252] font-black text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#171717]">
                    @foreach($reservas as $reserva)
                        <tr class="hover:bg-[#0f0f0f] transition-colors group">
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="text-[#ffc107] font-black text-sm tracking-tighter">{{ $reserva->codigo_reserva }}</span>
                                    <span class="text-white font-bold text-xs">{{ $reserva->nombre_cliente }}</span>
                                    <span class="text-[#525252] text-[10px]">{{ $reserva->correo_cliente }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center text-white text-xs font-bold">
                                        {{ $reserva->ruta->origen->nombre }}
                                        <i class="fas fa-arrow-right mx-2 text-[8px] text-[#ffc107]"></i>
                                        {{ $reserva->ruta->destino->nombre }}
                                    </div>
                                    <span class="text-[#525252] text-[10px] uppercase mt-1">
                                        <i class="fas fa-car mr-1"></i> {{ $reserva->tipo_vehiculo }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="text-white text-xs font-bold">{{ \Carbon\Carbon::parse($reserva->fecha_viaje)->format('d M, Y') }}</span>
                                    <span class="text-[#525252] text-[10px]">{{ $reserva->hora_viaje }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                @php
                                    $colorEstado = [
                                        'pendiente' => 'text-yellow-500 bg-yellow-500/10 border-yellow-500/20',
                                        'pagado' => 'text-emerald-500 bg-emerald-500/10 border-emerald-500/20',
                                        'fallido' => 'text-red-500 bg-red-500/10 border-red-500/20'
                                    ][$reserva->estado_pago] ?? 'text-gray-500 bg-gray-500/10';
                                @endphp
                                <span class="px-2 py-1 rounded-md border text-[9px] font-black uppercase {{ $colorEstado }}">
                                    {{ $reserva->estado_pago }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="text-white font-black text-sm">Q{{ number_format($reserva->precio_total, 2) }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="#" class="p-2 bg-[#171717] border border-[#262626] rounded-lg text-white hover:border-[#ffc107] transition-all">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <button class="p-2 bg-[#171717] border border-[#262626] rounded-lg text-white hover:border-blue-500 transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($reservas->hasPages())
            <div class="p-4 border-t border-[#262626] bg-[#0d0d0d]">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
