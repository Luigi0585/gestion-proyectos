<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nuevo Proyecto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Bloque para mostrar errores de validación si el usuario deja campos vacíos --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulario que envía los datos por POST al método store() --}}
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    {{-- Campo: Título --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Título del Proyecto</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Campo: Descripción --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    {{-- Campo: Fecha Límite --}}
                    <div class="mb-4">
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Fecha Límite</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Campo: Estado --}}
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado inicial</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pendiente" {{ old('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_progreso" {{ old('status') == 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                            <option value="completado" {{ old('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('projects.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Proyecto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>