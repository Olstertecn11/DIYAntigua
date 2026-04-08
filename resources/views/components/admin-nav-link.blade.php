@props(['href', 'active' => false, 'icon' => 'fa-circle'])

<li>
    <a href="{{ $href }}"
       class="flex flex-col md:flex-row items-center justify-between p-2 md:px-4 md:py-3 transition-all duration-200 group rounded-xl
       {{ $active ? 'bg-white/10 text-white shadow-[0_0_15px_rgba(255,255,255,0.05)]' : 'text-[#737373] hover:bg-white/5 hover:text-white' }}">
        <div class="flex items-center">
            <i class="fas {{ $icon }} text-lg md:mr-4 opacity-70 transition-transform group-hover:scale-110"></i>
            <span class="text-[10px] md:text-sm font-semibold tracking-wide">{{ $slot }}</span>
        </div>
        @if($active)
            <div class="hidden md:block w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_white]"></div>
        @endif
    </a>
</li>
