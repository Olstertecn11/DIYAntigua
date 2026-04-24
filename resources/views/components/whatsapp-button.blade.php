@props([
    'phone' => '50200000000',
    'message' => 'Hola, quiero información sobre un traslado privado.',
])

@php
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    $encodedMessage = urlencode($message);
    $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
@endphp

<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Contactar por WhatsApp"
    class="fixed bottom-5 right-5 z-[9999] group">

    <div class="relative">
        {{-- Pulso --}}
        <span class="absolute inset-0 rounded-full bg-green-500 opacity-40 animate-ping"></span>

        {{-- Botón --}}
        <div
            class="relative h-16 w-16 rounded-full bg-green-500 shadow-2xl shadow-green-500/40 flex items-center justify-center hover:scale-110 transition duration-300">
            <i class="fab fa-whatsapp text-white text-4xl"></i>
        </div>

        {{-- Tooltip --}}
        <div
            class="hidden sm:block absolute right-20 top-1/2 -translate-y-1/2 whitespace-nowrap rounded-2xl bg-slate-950 px-4 py-2 text-sm font-bold text-white shadow-xl opacity-0 translate-x-3 group-hover:opacity-100 group-hover:translate-x-0 transition">
            ¿Necesitas ayuda?
            <span class="absolute right-[-6px] top-1/2 -translate-y-1/2 h-3 w-3 rotate-45 bg-slate-950"></span>
        </div>
    </div>
</a>
