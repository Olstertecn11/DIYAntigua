@extends('layouts.admin')

@section('content')

    <div x-data="{ openModal: false }" class="p-6">
        <div class="bg-transparent rounded shadow">
            <div class="border-b p-3 flex justify-between items-center">
                <h5 class="font-bold uppercase text-gray-600">Gestión de Afiliados</h5>
                <button @click="openModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                    <i class="fas fa-plus mr-2"></i>Nuevo Socio
                </button>
            </div>

            <div class="p-5">
                @include('admin.modules.afiliados_table', ['afiliados' => $afiliados])
            </div>
        </div>

        <div x-show="openModal"
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/50"
             x-cloak>

            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6" @click.away="openModal = false">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Registrar Nuevo Socio</h2>

                <form action="{{ route('admin.afiliados.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Encargado</label>
                            <input type="text" name="name" required class="mt-1 block w-full border rounded-md shadow-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email de Acceso</label>
                            <input type="email" name="email" required class="mt-1 block w-full border rounded-md shadow-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre Comercial (Negocio)</label>
                            <input type="text" name="nombre_comercial" required class="mt-1 block w-full border rounded-md shadow-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Comisión (%)</label>
                            <input type="number" name="comision" value="10" step="0.01" class="mt-1 block w-full border rounded-md shadow-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                            <input type="password" name="password" required class="mt-1 block w-full border rounded-md shadow-sm p-2">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Guardar Afiliado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
