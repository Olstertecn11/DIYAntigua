@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-black p-6 md:p-8 text-white">
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-[#fcca00]">Negocio</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight">Pagos y comisiones</h1>
            <p class="mt-2 text-sm text-[#a1a1a1]">Control de ventas referidas por socios Airbnb y ganancias acumuladas.</p>
        </div>
    </div>

    <div class="mb-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Ventas referidas</p>
            <strong class="mt-2 block text-3xl font-black">Q{{ number_format($totales['ventas'], 2) }}</strong>
        </div>
        <div class="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Comisiones</p>
            <strong class="mt-2 block text-3xl font-black text-[#fcca00]">Q{{ number_format($totales['comisiones'], 2) }}</strong>
        </div>
        <div class="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
            <p class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Reservas pagadas</p>
            <strong class="mt-2 block text-3xl font-black">{{ $totales['reservas'] }}</strong>
        </div>
    </div>

    <section class="mb-8 rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
        <h2 class="mb-4 text-sm font-black uppercase tracking-widest">Socios</h2>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b border-white/10 text-[10px] uppercase tracking-widest text-[#737373]">
                    <tr>
                        <th class="py-3">Socio</th>
                        <th class="py-3">Pago</th>
                        <th class="py-3 text-center">Reservas</th>
                        <th class="py-3 text-right">Ventas</th>
                        <th class="py-3 text-right">Ganancia</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($afiliados as $afiliado)
                        <tr>
                            <td class="py-4">
                                <div class="font-bold">{{ $afiliado->nombre_comercial }}</div>
                                <div class="text-xs text-[#737373]">{{ $afiliado->user?->email }}</div>
                            </td>
                            <td class="py-4">
                                <div class="text-xs text-[#d4d4d4]">{{ $afiliado->metodo_pago ?: 'Sin configurar' }}</div>
                                <div class="text-[11px] text-[#737373]">{{ $afiliado->titular_pago ?: 'Titular pendiente' }}</div>
                            </td>
                            <td class="py-4 text-center">{{ $afiliado->reservas_pagadas_count }}</td>
                            <td class="py-4 text-right">Q{{ number_format($afiliado->ventas_referidas_total, 2) }}</td>
                            <td class="py-4 text-right font-black text-[#fcca00]">Q{{ number_format($afiliado->comisiones_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-[#737373]">Aún no hay socios registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
        <h2 class="mb-4 text-sm font-black uppercase tracking-widest">Reservas referidas</h2>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-white/10 text-[10px] uppercase tracking-widest text-[#737373]">
                    <tr>
                        <th class="py-3">Reserva</th>
                        <th class="py-3">Socio</th>
                        <th class="py-3">Ruta</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 text-right">Total</th>
                        <th class="py-3 text-right">Comisión</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reservasReferidas as $reserva)
                        <tr>
                            <td class="py-4">
                                <a href="{{ route('admin.reservas.show', $reserva) }}" class="font-black text-white hover:text-[#fcca00]">{{ $reserva->codigo_reserva }}</a>
                                <div class="text-xs text-[#737373]">{{ $reserva->nombre_cliente }}</div>
                            </td>
                            <td class="py-4">{{ $reserva->socio?->afiliadoInfo?->nombre_comercial ?? $reserva->socio?->name }}</td>
                            <td class="py-4 text-[#d4d4d4]">{{ $reserva->ruta?->origen?->nombre }} → {{ $reserva->ruta?->destino?->nombre }}</td>
                            <td class="py-4">
                                <span class="rounded-full border border-white/10 px-3 py-1 text-[11px] font-bold">{{ $reserva->estado_pago }} / {{ $reserva->estado_viaje }}</span>
                            </td>
                            <td class="py-4 text-right">Q{{ number_format((float) $reserva->precio_total, 2) }}</td>
                            <td class="py-4 text-right font-black text-[#fcca00]">Q{{ number_format((float) $reserva->comision_socio, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-[#737373]">Aún no hay reservas referidas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $reservasReferidas->links() }}</div>
    </section>
</div>
@endsection
