@extends('layouts.admin')

@section('content')
@php
    $travelAt = $reserva->travelDateTime();
    $canCancel = $reserva->canBeCancelledWithRefund();
    $transaction = $reserva->paymentTransactions->first();
@endphp

<div class="min-h-screen p-6 bg-[#050505] text-white">
    <div class="flex flex-col lg:flex-row justify-between gap-5 mb-8">
        <div>
            <a href="{{ route('admin.reservas.index') }}" class="text-[#FCCA00] text-xs font-black uppercase tracking-widest hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
            <h1 class="text-3xl md:text-4xl font-black tracking-tighter uppercase mt-3">{{ $reserva->codigo_reserva }}</h1>
            <p class="text-[#a3a3a3] text-sm mt-2">{{ $reserva->nombre_cliente }} · {{ $reserva->correo_cliente }}</p>
        </div>

        <div class="flex flex-wrap items-start gap-3">
            <a href="{{ route('reservas.pdf', $reserva->codigo_reserva) }}" class="rounded-xl border border-[#262626] bg-[#101010] px-4 py-3 text-xs font-black uppercase text-white hover:border-[#FCCA00]">
                <i class="fas fa-file-pdf mr-2"></i>PDF
            </a>
            @if ($canCancel)
                <button type="button" id="open-cancel-modal" class="rounded-xl bg-red-500 px-4 py-3 text-xs font-black uppercase text-white hover:bg-red-400">
                    Cancelar
                </button>
            @else
                <span class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-xs font-black uppercase text-red-200">
                    No cancelable con reembolso
                </span>
            @endif
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <section class="xl:col-span-2 rounded-3xl border border-[#262626] bg-[#0a0a0a] p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="rounded-2xl bg-[#101010] border border-[#242424] p-4">
                    <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Pago</p>
                    <strong class="text-lg font-black uppercase">{{ str_replace('_', ' ', $reserva->estado_pago) }}</strong>
                </div>
                <div class="rounded-2xl bg-[#101010] border border-[#242424] p-4">
                    <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Viaje</p>
                    <strong class="text-lg font-black uppercase">{{ $reserva->estado_viaje }}</strong>
                </div>
                <div class="rounded-2xl bg-[#101010] border border-[#242424] p-4">
                    <p class="text-[10px] uppercase tracking-widest text-[#737373] font-black">Total</p>
                    <strong class="text-lg font-black text-[#FCCA00]">Q{{ number_format((float) $reserva->precio_total, 2) }}</strong>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-2xl bg-[#101010] border border-[#242424] p-5">
                    <h2 class="text-sm font-black uppercase tracking-widest text-[#FCCA00] mb-4">Cliente</h2>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Nombre</dt><dd>{{ $reserva->nombre_cliente }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Correo</dt><dd>{{ $reserva->correo_cliente }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Teléfono</dt><dd>{{ $reserva->telefono_cliente }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Cuenta</dt><dd>{{ $reserva->user?->name ?? 'Invitado' }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Referido por</dt><dd>{{ $reserva->socio?->afiliadoInfo?->nombre_comercial ?? 'Sin socio' }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-2xl bg-[#101010] border border-[#242424] p-5">
                    <h2 class="text-sm font-black uppercase tracking-widest text-[#FCCA00] mb-4">Viaje</h2>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Ruta</dt><dd>{{ $reserva->ruta?->origen?->nombre }} → {{ $reserva->ruta?->destino?->nombre }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Fecha</dt><dd>{{ $travelAt->format('d/m/Y H:i') }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Restante</dt><dd>{{ $reserva->estado_viaje === 'cancelado' ? 'Cancelada' : $reserva->hoursUntilTravel() . ' horas' }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Vehículo</dt><dd>{{ strtoupper($reserva->tipo_vehiculo) }} · {{ $reserva->pasajeros }} pax</dd></div>
                    </dl>
                </div>
            </div>

            <div class="rounded-2xl bg-[#101010] border border-[#242424] p-5 mt-5">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#FCCA00] mb-4">Notas operativas</h2>
                <pre class="whitespace-pre-wrap text-sm text-[#d4d4d4] font-sans leading-relaxed">{{ $reserva->notas_adicionales ?: 'Sin notas.' }}</pre>
            </div>
        </section>

        <aside class="space-y-5">
            <section class="rounded-3xl border border-[#262626] bg-[#0a0a0a] p-5">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#FCCA00] mb-4">Pago QPayPro</h2>
                @if ($transaction)
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Estado</dt><dd>{{ strtoupper($transaction->status) }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Referencia</dt><dd class="break-all">{{ $transaction->provider_transaction_id ?: $transaction->reference }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Tarjeta</dt><dd>{{ strtoupper($transaction->card_brand ?: 'CARD') }} **** {{ $transaction->card_last_four }}</dd></div>
                        <div><dt class="text-[#737373] text-[10px] uppercase font-black">Respuesta</dt><dd>{{ $transaction->response_message ?: 'Sin mensaje' }}</dd></div>
                    </dl>
                @else
                    <p class="text-sm text-[#a3a3a3]">Sin transacciones registradas.</p>
                @endif
            </section>

            <section class="rounded-3xl border border-[#262626] bg-[#0a0a0a] p-5">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#FCCA00] mb-4">Cancelación</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-[#737373] text-[10px] uppercase font-black">Política</dt><dd>{{ $canCancel ? 'Cancelable con reembolso' : 'No cumple 24 horas previas' }}</dd></div>
                    <div><dt class="text-[#737373] text-[10px] uppercase font-black">Cancelado</dt><dd>{{ $reserva->cancelado_at?->format('d/m/Y H:i') ?? 'No' }}</dd></div>
                    <div><dt class="text-[#737373] text-[10px] uppercase font-black">Reembolso</dt><dd>{{ $reserva->reembolso_estado ? str_replace('_', ' ', $reserva->reembolso_estado) : 'Sin solicitud' }}</dd></div>
                    <div><dt class="text-[#737373] text-[10px] uppercase font-black">Monto</dt><dd>Q{{ number_format((float) $reserva->reembolso_monto, 2) }}</dd></div>
                </dl>
            </section>

            <section class="rounded-3xl border border-[#262626] bg-[#0a0a0a] p-5">
                <h2 class="text-sm font-black uppercase tracking-widest text-[#FCCA00] mb-4">Comisión socio</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-[#737373] text-[10px] uppercase font-black">Socio</dt><dd>{{ $reserva->socio?->afiliadoInfo?->nombre_comercial ?? 'No referido' }}</dd></div>
                    <div><dt class="text-[#737373] text-[10px] uppercase font-black">Ganancia</dt><dd>Q{{ number_format((float) $reserva->comision_socio, 2) }}</dd></div>
                </dl>
            </section>
        </aside>
    </div>
</div>

@if ($canCancel)
    <div id="cancel-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/75 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-[#0a0a0a] p-6 text-white shadow-2xl">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-500/10 text-red-300">
                    <i class="fas fa-rotate-left"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#FCCA00]">Política de reembolso</p>
                    <h2 class="mt-1 text-2xl font-black">Cancelar {{ $reserva->codigo_reserva }}</h2>
                    <p class="mt-2 text-sm leading-6 text-[#a3a3a3]">La reserva cumple la política de más de 24 horas antes del viaje. Si el pago está aprobado, se registrará un reembolso pendiente para gestión administrativa.</p>
                </div>
            </div>

            <div class="mt-5 rounded-2xl border border-white/10 bg-white/[0.03] p-4 text-sm">
                <div class="flex justify-between gap-4"><span class="text-[#737373]">Cliente</span><strong>{{ $reserva->nombre_cliente }}</strong></div>
                <div class="mt-2 flex justify-between gap-4"><span class="text-[#737373]">Viaje</span><strong>{{ $travelAt->format('d/m/Y H:i') }}</strong></div>
                <div class="mt-2 flex justify-between gap-4"><span class="text-[#737373]">Monto</span><strong class="text-[#FCCA00]">Q{{ number_format((float) $reserva->precio_total, 2) }}</strong></div>
            </div>

            <form action="{{ route('admin.reservas.cancel', $reserva) }}" method="POST" class="mt-5">
                @csrf
                <label class="text-[10px] font-black uppercase tracking-widest text-[#737373]">Motivo opcional</label>
                <textarea name="motivo_cancelacion" maxlength="500" rows="3" class="mt-2 w-full resize-none rounded-2xl border border-white/10 bg-black px-4 py-3 text-sm text-white outline-none focus:border-[#FCCA00]" placeholder="Ej. Solicitud del cliente, cambio de itinerario..."></textarea>
                <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancel-modal-close" class="rounded-full border border-white/10 px-5 py-3 text-xs font-black uppercase text-white hover:bg-white/5">Volver</button>
                    <button class="rounded-full bg-red-500 px-5 py-3 text-xs font-black uppercase text-white hover:bg-red-400">Confirmar cancelación</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('cancel-modal');
            const open = document.getElementById('open-cancel-modal');
            const close = document.getElementById('cancel-modal-close');

            open?.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
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
@endif
@endsection
