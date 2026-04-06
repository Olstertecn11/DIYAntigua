<div class="overflow-hidden rounded-xl border border-white/10 bg-[#0a0a0a]">
    <table class="w-full text-left text-sm text-gray-400">
        <thead class="border-b border-white/10 bg-white/[0.02] text-xs uppercase tracking-widest text-gray-500">
            <tr>
                <th class="px-6 py-4 font-medium">Socio / Empresa</th>
                <th class="px-6 py-4 font-medium">Contacto</th>
                <th class="px-6 py-4 font-medium text-center">Comisión</th>
                <th class="px-6 py-4 font-medium text-center">Estado</th>
                <th class="px-6 py-4 font-medium text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($afiliados as $afiliado)
                <tr class="group transition-colors hover:bg-white/[0.02]">
                    <td class="whitespace-nowrap px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-gradient-to-br from-gray-800 to-black text-xs font-bold text-white group-hover:border-yellow-500/50 transition-colors">
                                {{ substr($afiliado->nombre_comercial, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $afiliado->nombre_comercial ?? 'Sin nombre' }}</div>
                                <div class="text-xs text-gray-500">ID: #00{{ $afiliado->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-gray-300">{{ $afiliado->user->email ?? 'N/A' }}</span>
                            <span class="text-[10px] text-gray-500 italic">{{ $afiliado->user->name ?? '' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center rounded-md border border-white/10 bg-white/[0.03] px-2 py-1 text-xs font-medium text-yellow-500">
                            {{ number_format($afiliado->comision_porcentaje, 2) }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($afiliado->activo)
                            <div class="flex items-center justify-center space-x-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                <span class="text-xs font-medium text-green-500">Online</span>
                            </div>
                        @else
                            <div class="flex items-center justify-center space-x-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500 opacity-50"></span>
                                <span class="text-xs font-medium text-gray-500">Offline</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <button class="flex h-8 w-8 items-center justify-center rounded-md border border-white/10 bg-transparent text-gray-400 transition-all hover:border-white/30 hover:text-white">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button class="flex h-8 w-8 items-center justify-center rounded-md border border-white/10 bg-transparent text-gray-400 transition-all hover:border-red-500/50 hover:text-red-500">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-folder-open mb-3 text-3xl text-gray-700"></i>
                            <span class="text-sm text-gray-500">No se encontraron afiliados en Antigua Transfers.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
