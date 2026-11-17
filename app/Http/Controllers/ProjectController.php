<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; //Se importa la clase Project. Útil para consultar, crear, editar o borrar registros de la tabla projects.
use Illuminate\Support\Facades\Auth; //Este import sirve para saber qué usuario tiene su sesión iniciada, y de esa forma asignar su id al proyecto que ha creado.
use App\Models\User; //Hace que no se deba escribir \App\Models\User dentro del método show.

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //Se pide a la base de datos todos los proyectos.
        $projects = Project::all();

        //Se devuelve la vista 'projects/index.blade.php' pasándole la lista de proyectos.
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //Solo devuelve el HTML del formulario vacío.
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // A. Validamos los datos que vienen del formulario
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'due_date'    => 'required|date',
            'status'      => 'required|string|max:50',
        ]);

        // B. Añadimos el ID del usuario que tiene la sesión iniciada (Relación 1:N)
        $validated['user_id'] = Auth::id();

        // C. Insertamos el registro en la tabla 'projects'
        Project::create($validated);

        // D. Redirigimos al listado con un mensaje temporal de éxito
        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project) {
        //Se cargan todos los usuarios para mostrarlos en el desplegable de asignación.
        $users = \App\Models\User::all();

        //Se envía tanto el proyecto como los usuarios a la vista.
    return view('projects.show', compact('project', 'users'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project) {
        //Esta línea compara el usuario que creó el proyecto con el que tiene su sesión iniciada. Si no coinciden (es decir que 
        // el que tiene sesión iniciada NO es su creador), salta un error 403 e impide que el usuario con la sesión iniciada
        // pueda siquiera tocarlo. 
        if ($project->user_id !== Auth::id()) { abort(403, 'No tienes permiso'); }

        //Se envia el proyecto actual a la vista para rellenar el formulario.
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project) {  
       //Aquí también se compara el usuario que creó el proyecto con el que tiene su sesión activa.
       if ($project->user_id !== Auth::id()) { abort(403, 'No tienes permiso'); }

       //Se validan los nuevos datos.
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'due_date'    => 'required|date',
            'status'      => 'required|string|max:50',
        ]);

        //Se actualiza el proyecto en la base de datos.
        $project->update($validated);

        //Se redirige al listado.
        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project) {  
        //Aquí también se compara el usuario que creó el proyecto con el que tiene su sesión activa.
        if ($project->user_id !== Auth::id()) { abort(403, 'No tienes permiso'); }

        //Se borra el registro de la base de datos.
        $project->delete();

        // Redirigimos al listado
        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto eliminado.');
    }

    /**
     * Asignar un usuario colaborador o actualizar su rol/horas en el proyecto (N:N)
     */
    public function addContributor(Request $request, Project $project) {
        //Autorización: Solo el propietario puede añadir o editar colaboradores.
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar los participantes de este proyecto.');
        }

        //Se validan los datos del formulario.
        $validated = $request->validate([
            'user_id'            => 'required|exists:users,id',
            'role'               => 'required|string|max:100',
            'contribution_hours' => 'required|integer|min:0',
        ]);

        //Se guarda el colaborador en la tabla 'project_users' con datos extra.
        // syncWithoutDetaching() añade al usuario si no estaba, o actualiza su rol y horas si ya participaba
        $project->contributors()->syncWithoutDetaching([
            $validated['user_id'] => [
                'role'               => $validated['role'],
                'contribution_hours' => $validated['contribution_hours'],
            ]
        ]);

        return redirect()->route('projects.show', $project)
        ->with('success', 'Participante asignado/actualizado correctamente.');
    }

    /**
     * Eliminar un colaborador del proyecto
     */
    public function removeContributor(Project $project, User $user) {
        //Autorización: Solo el propietario puede quitar colaboradores.
        if ($project->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para quitar participantes.');
        }

        //Se rompe el vínculo en la tabla pivote usando detach().
        $project->contributors()->detach($user->id);

        return redirect()->route('projects.show', $project)
        ->with('success', 'Participante eliminado del proyecto.');
    }
}

//NOTA: En update y destroy se pasa Project $project por 3 razones:
//-Para buscar el registro de un objeto Project, sin tener que crear la consulta SQL con SELECT a mano.
//-Para evitar que se rompa el código si se intenta hacer operaciones sobre un registtro que simplemente no existe. En su lugar, hace que
//se muestre el error 404 ("no encontrado").
//-Para comprobar permisos y para ejecutar el update (comparando los datos viejos con los nuevos del formulario). 