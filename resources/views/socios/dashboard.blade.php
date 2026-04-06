@extends('layouts.admin')

@section('content')
<div class="p-8 bg-black min-h-screen text-white">

    <header class="mb-10">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 rounded-full border border-yellow-500/50 bg-yellow-500/10 flex items-center justify-center text-yellow-500 text-xl font-bold">
                {{ substr($info->nombre_comercial ?? $user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-tight">
                    Bienvenido, {{ $info->nombre_comercial ?? $user->name }}
                </h1>
                <p class="text-gray-500 text-sm">Panel de control de afiliado para Antigua Transfers.</p>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl hover:border-white/20 transition-colors">
            <p class="text-gray-500 text-xs uppercase font-black tracking-widest">Mis Ganancias</p>
            <p class="text-3xl font-mono mt-2 text-green-500 font-bold">Q{{ number_format($stats['ganancias'], 2) }}</p>
        </div>
        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl hover:border-white/20 transition-colors">
            <p class="text-gray-500 text-xs uppercase font-black tracking-widest">Mi Comisión</p>
            <p class="text-3xl font-mono mt-2 text-yellow-500 font-bold">{{ $stats['comision'] }}%</p>
        </div>
        <div class="bg-[#0a0a0a] border border-white/10 p-6 rounded-xl hover:border-white/20 transition-colors">
            <p class="text-gray-500 text-xs uppercase font-black tracking-widest">Viajes Referidos</p>
            <p class="text-3xl font-mono mt-2 font-bold">{{ $stats['referidos_count'] }}</p>
        </div>
    </div>

    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 h-32 w-32 bg-blue-500/5 blur-3xl rounded-full"></div>

        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-link mr-3 text-blue-500"></i> Enlace de Reservación para tu Airbnb
        </h2>
        <p class="text-gray-400 text-sm mb-6 max-w-2xl">
            Usa este enlace único. Cuando tus huéspedes reserven a través de él, el sistema te asignará automáticamente tu comisión.
        </p>

        {{-- Contenedor del Link --}}
        <div class="relative max-w-2xl">
            <div class="flex items-center bg-black border border-white/20 rounded-lg p-1 group focus-within:border-blue-500/50 transition-all">
                <input type="text" id="referralInput" readonly value="{{ $referralLink }}"
                       class="bg-transparent border-none w-full px-4 py-3 text-sm font-mono text-gray-300 focus:ring-0 outline-none">

                <button onclick="copyLink()" id="copyBtn"
                        class="bg-white text-black px-6 py-2 rounded-md font-bold text-[10px] uppercase tracking-tighter hover:bg-gray-200 transition-all active:scale-95 whitespace-nowrap">
                    <span id="btnText">Copiar Link</span>
                </button>
            </div>
            {{-- Notificación flotante (opcional) --}}
            <p id="copyMessage" class="hidden text-green-500 text-[10px] font-bold mt-2 uppercase tracking-widest transition-opacity">
                <i class="fas fa-check mr-1"></i> ¡Enlace copiado correctamente!
            </p>
        </div>

        <div class="mt-10 pt-6 border-t border-white/5 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">¿Dónde pegarlo?</h4>
                <ul class="text-xs text-gray-400 space-y-2">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Guía de bienvenida de Airbnb</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Mensaje automático de confirmación</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function copyLink() {
    const input = document.getElementById("referralInput");
    const btn = document.getElementById("copyBtn");
    const btnText = document.getElementById("btnText");
    const message = document.getElementById("copyMessage");

    // Copiamos directamente el valor sin "seleccionar" el input visualmente
    navigator.clipboard.writeText(input.value).then(() => {
        // Feedback Visual
        btn.classList.replace('bg-white', 'bg-green-600');
        btn.classList.add('text-white');
        btnText.innerHTML = '<i class="fas fa-check mr-1"></i> ¡COPIADO!';
        message.classList.remove('hidden');

        setTimeout(() => {
            btn.classList.replace('bg-green-600', 'bg-white');
            btn.classList.remove('text-white');
            btnText.innerHTML = 'Copiar Link';
            message.classList.add('hidden');
        }, 2000);
    });
}
</script>
