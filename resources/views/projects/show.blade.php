<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle del Proyecto: {{ $project->title }}
            </h2>
            <a href="{{ route('projects.index') }}" style="background-color: #6b7280; color: white;" class="font-bold py-2 px-4 rounded">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensaje de éxito al asignar o quitar participantes --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- BLOQUE 1: INFORMACIÓN DEL PROYECTO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                <div class="border-b pb-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Título</span>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Propietario: <span class="font-semibold">{{ $project->creator->name }}</span></p>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $project->status }}
                    </span>
                </div>

                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Descripción</span>
                    <p class="mt-2 text-gray-700 whitespace-pre-line leading-relaxed">
                        {{ $project->description }}
                    </p>
                </div>

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

                @if ($project->user_id === Auth::id())
                    <div class="pt-4 border-t flex justify-end space-x-3">
                        <a href="{{ route('projects.edit', $project) }}" style="background-color: #eab308; color: white;" class="font-bold py-2 px-4 rounded">
                            Editar Proyecto
                        </a>
                    </div>
                @endif
            </div>

            {{-- BLOQUE 2: LISTA DE PARTICIPANTES ACTUALES (N:N) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Equipo y Participantes</h3>

                @if ($project->contributors->isEmpty())
                    <p class="text-gray-500 text-sm">Todavía no hay colaboradores asignados a este proyecto.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Horas Aportadas</th>
                                @if ($project->user_id === Auth::id())
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($project->contributors as $contributor)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ $contributor->name }} ({{ $contributor->email }})
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $contributor->pivot->role }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $contributor->pivot->contribution_hours }} h
                                    </td>
                                    @if ($project->user_id === Auth::id())
                                        <td class="px-4 py-3 text-right text-sm">
                                            <form action="{{ route('projects.contributors.destroy', [$project, $contributor]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('¿Quitar a este colaborador del proyecto?')">
                                                    Quitar
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- BLOQUE 3: FORMULARIO PARA ASIGNAR / ACTUALIZAR PARTICIPANTES (Solo Propietario) --}}
            @if ($project->user_id === Auth::id())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Asignar o Actualizar Participante</h3>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('projects.contributors.store', $project) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                        @csrf

                        {{-- Desplegable de Usuarios --}}
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
                            <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Rol --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Rol en el proyecto</label>
                            <input type="text" name="role" id="role" placeholder="Ej. Backend, Diseño..." required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        {{-- Horas trabajadas --}}
                        <div>
                            <label for="contribution_hours" class="block text-sm font-medium text-gray-700">Horas aportadas</label>
                            <input type="number" name="contribution_hours" id="contribution_hours" value="0" min="0" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        {{-- Botón Guardar --}}
                        <div>
                            <button type="submit" style="background-color: #2563eb; color: white;" class="w-full font-bold py-2 px-4 rounded">
                                Asignar / Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>