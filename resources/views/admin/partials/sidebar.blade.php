<aside class="bg-[#0f1115] shadow-2xl h-16 fixed bottom-0 md:relative md:h-screen z-50 w-full md:w-64 transition-all duration-300 border-t border-white/5 md:border-t-0 md:border-r border-white/5">
    <div class="md:fixed md:left-0 md:top-0 md:w-64 h-full flex flex-col">

        <div class="hidden md:flex items-center p-6 border-b border-white/5">
            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center mr-3 shadow-lg shadow-yellow-500/20">
                <span class="text-slate-900 font-black text-xl">A</span>
            </div>
            <h1 class="text-white text-sm font-bold tracking-widest uppercase">
                Antigua <span class="text-yellow-500 block text-[10px]">Transfers</span>
            </h1>
        </div>

        <nav class="flex-grow overflow-y-auto custom-scrollbar">
            <ul class="flex flex-row md:flex-col py-2 md:py-6 px-4 space-y-0 md:space-y-1">

                @if(Auth::user()->role_id == 1)
                    <li class="flex-1 md:flex-none">
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex flex-col md:flex-row items-center justify-between p-2 md:px-4 md:py-3 transition-all duration-200 group rounded-xl
                           {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-500/10 text-yellow-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center">
                                <i class="fas fa-grid-2 text-lg md:mr-4 opacity-70"></i>
                                <span class="text-[10px] md:text-sm font-semibold tracking-wide">Panel Admin</span>
                            </div>
                        </a>
                    </li>

                    <li class="flex-1 md:flex-none">
                        <a href="#socios" class="flex flex-col md:flex-row items-center justify-between p-2 md:px-4 md:py-3 text-slate-400 hover:bg-white/5 hover:text-white rounded-xl transition-all duration-200 group">
                            <div class="flex items-center">
                                <i class="fas fa-users text-lg md:mr-4 opacity-70"></i>
                                <span class="text-[10px] md:text-sm font-semibold tracking-wide">Lista Afiliados</span>
                            </div>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->role_id == 2)
                    <li class="flex-1 md:flex-none">
                        <a href="{{ route('socios.dashboard') }}"
                           class="flex flex-col md:flex-row items-center justify-between p-2 md:px-4 md:py-3 transition-all duration-200 group rounded-xl
                           {{ request()->routeIs('socios.dashboard') ? 'bg-yellow-500/10 text-yellow-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center">
                                <i class="fas fa-home text-lg md:mr-4 opacity-70"></i>
                                <span class="text-[10px] md:text-sm font-semibold tracking-wide">Mi Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <li class="flex-1 md:flex-none">
                        <a href="#" class="flex flex-col md:flex-row items-center justify-between p-2 md:px-4 md:py-3 text-slate-400 hover:bg-white/5 hover:text-white rounded-xl transition-all duration-200 group">
                            <div class="flex items-center">
                                <i class="fas fa-wallet text-lg md:mr-4 opacity-70"></i>
                                <span class="text-[10px] md:text-sm font-semibold tracking-wide">Mis Ganancias</span>
                            </div>
                        </a>
                    </li>
                @endif

                <li class="hidden md:block pt-6 pb-2 px-4 border-t border-white/5 mt-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Configuración</span>
                </li>

                <li class="hidden md:block">
                    <a href="#" class="flex items-center px-4 py-2 text-slate-400 hover:text-white transition-colors group">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mr-4 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                        <span class="text-sm font-medium">Perfil</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="hidden md:block p-4">
            <div class="bg-white/5 rounded-2xl p-4 border border-white/5 shadow-inner">
                <div class="flex items-center mb-4">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-yellow-500 font-bold border border-white/10">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0f1115] rounded-full"></div>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-tighter">
                            {{ Auth::user()->role_id == config('constantes.idAdmin') ? 'Administrator' : 'Socio Afiliado' }}
                        </p>
                    </div>
                </div>

                <form action="{{ Auth::user()->role_id == config('constantes.idAdmin') ? route('admin.logout') : route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white/5 hover:bg-red-500/10 py-2 rounded-xl text-[10px] font-bold text-slate-400 hover:text-red-500 transition-all border border-white/5 uppercase tracking-widest flex items-center justify-center">
                        <i class="fas fa-power-off mr-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Scrollbar minimalista para el nav */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
</style>
