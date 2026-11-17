<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle del Proyecto: {{ $project->title }}
            </h2>
            <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">

                {{-- Título y Estado --}}
                <div class="border-b pb-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Título</span>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h3>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $project->status }}
                    </span>
                </div>

                {{-- Descripción --}}
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Descripción</span>
                    <p class="mt-2 text-gray-700 whitespace-pre-line leading-relaxed">
                        {{ $project->description }}
                    </p>
                </div>

                {{-- Metadatos (Fecha límite y fechas de registro) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg text-sm">
                    <div>
                        <span class="font-semibold text-gray-600">Fecha Límite:</span>
                        <span class="text-gray-800 ml-1">{{ $project->due_date }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-600">Creado el:</span>
                        <span class="text-gray-800 ml-1">{{ $project->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Botones de acción rápida (Solo visibles para el propietario) --}}
                @if ($project->user_id === Auth::id())
                    <div class="pt-4 border-t flex justify-end space-x-3">
                        <a href="{{ route('projects.edit', $project) }}" style="background-color: #eab308; color: white;" class="font-bold py-2 px-4 rounded">
                            Editar Proyecto
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>