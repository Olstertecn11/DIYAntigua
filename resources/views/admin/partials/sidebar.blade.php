<aside class="bg-[#0a0a0a] shadow-2xl h-16 fixed bottom-0 md:relative md:h-screen z-50 w-full md:w-64 transition-all duration-300 border-t border-white/5 md:border-t-0 md:border-r border-[#262626]">
    <div class="md:fixed md:left-0 md:top-0 md:w-64 h-full flex flex-col">

        <div class="hidden md:flex items-center p-8 border-b border-[#262626]">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3 shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                <span class="text-black font-black text-lg italic">A</span>
            </div>
            <div class="flex flex-col">
                <span class="text-white text-sm font-bold tracking-tighter leading-none">ANTIGUA</span>
                <span class="text-[#737373] text-[10px] font-medium tracking-widest uppercase">Transfers</span>
            </div>
        </div>

        <nav class="flex-grow overflow-y-auto custom-scrollbar pt-6">
            <ul class="flex flex-row md:flex-col px-4 space-y-0 md:space-y-1">

                @if(Auth::user()->role_id == 1) {{-- ADMINISTRADOR --}}
                    <x-admin-nav-link href="{{ route('admin.dashboard') }}" icon="fa-grid-2" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-admin-nav-link>

                    <li class="hidden md:block pt-4 pb-2 px-4">
                        <span class="text-[10px] font-semibold text-[#404040] uppercase tracking-[0.2em]">Operaciones</span>
                    </li>

                    <x-admin-nav-link href="{{ route('admin.reservas.index') }}" icon="fa-calendar-check" :active="request()->routeIs('admin.reservas.*')">
                        Reservas
                    </x-admin-nav-link>

                    <x-admin-nav-link href="{{ route('admin.rutas.index') }}" icon="fa-route" :active="request()->routeIs('admin.rutas.*')">
                        Rutas y Tarifas
                    </x-admin-nav-link>

                    <x-admin-nav-link href="{{ route('admin.lugares.index') }}" icon="fa-map-location-dot" :active="request()->routeIs('admin.lugares.*')">
                        Lugares / Hoteles
                    </x-admin-nav-link>

                    <x-admin-nav-link href="{{ route('admin.conductores.index') }}" icon="fa-truck-fast" :active="request()->routeIs('admin.conductores.*')">
                        Conductores
                    </x-admin-nav-link>

                    <li class="hidden md:block pt-4 pb-2 px-4">
                        <span class="text-[10px] font-semibold text-[#404040] uppercase tracking-[0.2em]">Negocio</span>
                    </li>

                    <x-admin-nav-link href="{{ route('admin.afiliados.index') }}" icon="fa-users" :active="request()->routeIs('admin.afiliados.*')">
                        Afiliados
                    </x-admin-nav-link>

                    <x-admin-nav-link href="#" icon="fa-wallet">Pagos y Comis.</x-admin-nav-link>
                @endif

                @if(Auth::user()->role_id == 2) {{-- SOCIO --}}
                    <x-admin-nav-link href="{{ route('socios.dashboard') }}" icon="fa-home" :active="request()->routeIs('socios.dashboard')">
                        Mi Panel
                    </x-admin-nav-link>
                    <x-admin-nav-link href="#" icon="fa-chart-line">Mis Ventas</x-admin-nav-link>
                @endif
            </ul>
        </nav>

        <div class="hidden md:block p-4 border-t border-[#262626] bg-[#050505]">
            <div class="flex items-center mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-[#171717] flex items-center justify-center text-white font-bold border border-[#262626] text-xs">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-[11px] font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] text-[#737373] font-medium uppercase tracking-tighter">
                    {{ Auth::user()->role_id == 1 ? 'Administrator' : 'Socio Afiliado' }}
                    </p>
                </div>
            </div>

            <form action="{{ Auth::user()->role_id == 1 ? route('admin.logout') : route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-[#a1a1a1] hover:text-white hover:bg-red-500/10 py-2 rounded-lg text-[10px] font-bold transition-all flex items-center justify-center border border-transparent hover:border-red-500/20 uppercase tracking-widest">
                    <i class="fas fa-power-off mr-2 text-[8px]"></i> Salir del Sistema
                </button>
            </form>
        </div>
    </div>
</aside>
