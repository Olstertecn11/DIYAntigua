@extends('layouts.admin')

@section('content')
<div class="min-h-screen p-6 bg-[#050505] text-white">
    <div class="flex flex-col xl:flex-row justify-between gap-5 mb-8">
        <div>
            <p class="text-[#FCCA00] text-[11px] font-black uppercase tracking-[0.22em] mb-2">Operaciones</p>
            <h1 class="text-3xl md:text-4xl font-black tracking-tighter uppercase">Reservaciones</h1>
            <p class="text-[#a3a3a3] text-sm mt-2">Control de reservas, pagos, cancelaciones y reembolsos.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 min-w-full xl:min-w-[680px]">
            <div class="rounded-2xl bg-[#0d0d0d] border border-[#242424] p-4">
                <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Total</p>
                <strong class="text-2xl font-black">{{ $stats['total'] }}</strong>
            </div>
            <div class="rounded-2xl bg-[#0d0d0d] border border-[#242424] p-4">
                <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Pagadas</p>
                <strong class="text-2xl font-black text-emerald-400">{{ $stats['pagadas'] }}</strong>
            </div>
            <div class="rounded-2xl bg-[#0d0d0d] border border-[#242424] p-4">
                <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Programadas</p>
                <strong class="text-2xl font-black text-[#FCCA00]">{{ $stats['programadas'] }}</strong>
            </div>
            <div class="rounded-2xl bg-[#0d0d0d] border border-[#242424] p-4">
                <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Reembolsos</p>
                <strong class="text-2xl font-black text-sky-400">{{ $stats['reembolsos'] }}</strong>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="mb-5 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.reservas.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
        <input name="search" value="{{ request('search') }}" type="text" placeholder="Código, cliente, correo o teléfono"
            class="md:col-span-2 w-full bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-3 text-white text-xs focus:border-[#FCCA00] transition-all outline-none">

        <select name="estado_pago" class="bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-3 text-white text-xs focus:border-[#FCCA00] outline-none">
            <option value="">Pago: todos</option>
            @foreach (['pendiente', 'procesando', 'pagado', 'rechazado', 'fallido', 'reembolso_pendiente'] as $estado)
                <option value="{{ $estado }}" @selected(request('estado_pago') === $estado)>{{ str_replace('_', ' ', ucfirst($estado)) }}</option>
            @endforeach
        </select>

        <select name="estado_viaje" class="bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-3 text-white text-xs focus:border-[#FCCA00] outline-none">
            <option value="">Viaje: todos</option>
            @foreach (['programado', 'completado', 'cancelado'] as $estado)
                <option value="{{ $estado }}" @selected(request('estado_viaje') === $estado)>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <input name="fecha" value="{{ request('fecha') }}" type="date" class="min-w-0 flex-1 bg-[#0a0a0a] border border-[#262626] rounded-xl px-4 py-3 text-white text-xs focus:border-[#FCCA00] outline-none">
            <button class="bg-[#FCCA00] text-black font-black uppercase text-[10px] rounded-xl px-4 py-3 hover:bg-[#ffd83d] transition-all">
                Filtrar
            </button>
        </div>
    </form>

    <div class="bg-[#0a0a0a] border border-[#262626] rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#262626] bg-[#101010]">
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#737373] font-black">Reserva</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#737373] font-black">Ruta</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#737373] font-black">Viaje</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#737373] font-black">Estados</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#737373] font-black">Reembolso</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-[#737373] font-black text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#171717]">
                    @forelse($reservas as $reserva)
                        @php
                            $travelAt = $reserva->travelDateTime();
                            $canCancel = $reserva->canBeCancelledWithRefund();
                            $paymentColor = [
                                'pendiente' => 'text-yellow-300 bg-yellow-300/10 border-yellow-300/20',
                                'procesando' => 'text-sky-300 bg-sky-300/10 border-sky-300/20',
                                'pagado' => 'text-emerald-300 bg-emerald-300/10 border-emerald-300/20',
                                'rechazado' => 'text-red-300 bg-red-300/10 border-red-300/20',
                                'fallido' => 'text-red-300 bg-red-300/10 border-red-300/20',
                                'reembolso_pendiente' => 'text-cyan-300 bg-cyan-300/10 border-cyan-300/20',
                            ][$reserva->estado_pago] ?? 'text-gray-300 bg-gray-300/10 border-gray-300/20';
                            $tripColor = $reserva->estado_viaje === 'cancelado'
                                ? 'text-red-300 bg-red-300/10 border-red-300/20'
                                : 'text-[#FCCA00] bg-[#FCCA00]/10 border-[#FCCA00]/20';
                        @endphp
                        <tr class="hover:bg-[#101010] transition-colors">
                            <td class="p-4">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('admin.reservas.show', $reserva) }}" class="text-[#FCCA00] font-black text-sm tracking-tight hover:underline">{{ $reserva->codigo_reserva }}</a>
                                    <span class="text-white font-bold text-xs">{{ $reserva->nombre_cliente }}</span>
                                    <span class="text-[#737373] text-[10px]">{{ $reserva->correo_cliente }}</span>
                                    <span class="text-[#525252] text-[10px]">{{ $reserva->user_id ? 'Usuario registrado' : 'Invitado' }}</span>
                                    @if($reserva->socio)
                                        <span class="text-[#FCCA00] text-[10px]">Ref: {{ $reserva->socio?->afiliadoInfo?->nombre_comercial ?? $reserva->socio?->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="text-white text-xs font-bold">
                                    {{ $reserva->ruta?->origen?->nombre ?? 'Origen' }}
                                    <i class="fas fa-arrow-right mx-2 text-[8px] text-[#FCCA00]"></i>
                                    {{ $reserva->ruta?->destino?->nombre ?? 'Destino' }}
                                </div>
                                <span class="text-[#737373] text-[10px] uppercase mt-1 block">{{ $reserva->tipo_vehiculo }} · {{ $reserva->pasajeros }} pax</span>
                            </td>
                            <td class="p-4">
                                <span class="text-white text-xs font-black">{{ $travelAt->format('d/m/Y H:i') }}</span>
                                <span class="block text-[#737373] text-[10px]">
                                    {{ $reserva->estado_viaje === 'cancelado' ? 'Cancelada' : ($reserva->hoursUntilTravel() . 'h restantes') }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col gap-2">
                                    <span class="w-fit px-2 py-1 rounded-md border text-[9px] font-black uppercase {{ $paymentColor }}">{{ str_replace('_', ' ', $reserva->estado_pago) }}</span>
                                    <span class="w-fit px-2 py-1 rounded-md border text-[9px] font-black uppercase {{ $tripColor }}">{{ $reserva->estado_viaje }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                @if ($reserva->reembolso_estado)
                                    <span class="text-white font-black text-xs">{{ str_replace('_', ' ', ucfirst($reserva->reembolso_estado)) }}</span>
                                    <span class="block text-[#737373] text-[10px]">Q{{ number_format((float) $reserva->reembolso_monto, 2) }}</span>
                                @else
                                    <span class="text-[#737373] text-xs">Sin solicitud</span>
                                @endif
                                <span class="block mt-1 text-[10px] {{ $canCancel ? 'text-emerald-300' : 'text-red-300' }}">
                                    {{ $canCancel ? 'Cancelable con reembolso' : 'Fuera de política' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.reservas.show', $reserva) }}" class="p-2 bg-[#171717] border border-[#262626] rounded-lg text-white hover:border-[#FCCA00] transition-all">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    @if ($canCancel)
                                        <button type="button"
                                            class="js-cancel-reservation p-2 bg-red-500/10 border border-red-500/25 rounded-lg text-red-200 hover:bg-red-500/20 transition-all"
                                            data-action="{{ route('admin.reservas.cancel', $reserva) }}"
                                            data-code="{{ $reserva->codigo_reserva }}"
                                            data-client="{{ $reserva->nombre_cliente }}"
                                            data-amount="Q{{ number_format((float) $reserva->precio_total, 2) }}"
                                            data-refund="{{ $reserva->estado_pago === 'pagado' ? 'Se creará una solicitud de reembolso administrativo por el monto pagado.' : 'La reserva no está pagada; se cancelará sin reembolso monetario.' }}">
                                            <i class="fas fa-ban text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-[#737373] font-bold">No hay reservaciones con estos filtros.</td>
                        </tr>
                    @endforelse
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

<div id="cancel-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/75 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-[#0a0a0a] p-6 text-white shadow-2xl">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-500/10 text-red-300">
                <i class="fas fa-rotate-left"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#FCCA00]">Política 24 horas</p>
                <h2 class="mt-1 text-2xl font-black">Confirmar cancelación</h2>
                <p class="mt-2 text-sm leading-6 text-[#a3a3a3]">Esta acción cancelará la reservación y dejará trazabilidad para auditoría. El reembolso solo aplica cuando la reserva pagada cumple la política de más de 24 horas antes del viaje.</p>
            </div>
        </div>

        <div class="mt-5 rounded-2xl border border-white/10 bg-white/[0.03] p-4 text-sm">
            <div class="flex justify-between gap-4"><span class="text-[#737373]">Reserva</span><strong id="cancel-modal-code"></strong></div>
            <div class="mt-2 flex justify-between gap-4"><span class="text-[#737373]">Cliente</span><strong id="cancel-modal-client"></strong></div>
            <div class="mt-2 flex justify-between gap-4"><span class="text-[#737373]">Monto</span><strong id="cancel-modal-amount" class="text-[#FCCA00]"></strong></div>
            <p id="cancel-modal-refund" class="mt-4 rounded-xl bg-[#FCCA00]/10 p-3 text-xs font-bold text-[#FCCA00]"></p>
        </div>

        <form id="cancel-modal-form" method="POST" class="mt-5">
            @csrf
            <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Motivo opcional</label>
            <textarea name="motivo_cancelacion" maxlength="500" rows="3" class="mt-2 w-full resize-none rounded-2xl border border-white/10 bg-black px-4 py-3 text-sm text-white outline-none focus:border-[#FCCA00]" placeholder="Ej. Solicitud del cliente, cambio de itinerario..."></textarea>
            <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancel-modal-close" class="rounded-full border border-white/10 px-5 py-3 text-xs font-black uppercase text-white hover:bg-white/5">Volver</button>
                <button class="rounded-full bg-red-500 px-5 py-3 text-xs font-black uppercase text-white hover:bg-red-400">Cancelar y registrar reembolso</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('cancel-modal');
        const form = document.getElementById('cancel-modal-form');
        const close = document.getElementById('cancel-modal-close');

        document.querySelectorAll('.js-cancel-reservation').forEach((button) => {
            button.addEventListener('click', () => {
                form.action = button.dataset.action;
                document.getElementById('cancel-modal-code').textContent = button.dataset.code;
                document.getElementById('cancel-modal-client').textContent = button.dataset.client;
                document.getElementById('cancel-modal-amount').textContent = button.dataset.amount;
                document.getElementById('cancel-modal-refund').textContent = button.dataset.refund;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        function hideModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        close?.addEventListener('click', hideModal);
        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                hideModal();
            }
        });
    });
</script>
@endsection
