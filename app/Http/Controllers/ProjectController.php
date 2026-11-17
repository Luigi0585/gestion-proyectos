<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; //Se importa la clase Project. Útil para consultar, crear, editar o borrar registros de la tabla projects.
use Illuminate\Support\Facades\Auth; //Este import sirve para saber qué usuario tiene su sesión iniciada, y de esa forma asignar su id al proyecto que ha creado.


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
    public function show(string $id) {
        //Se encuentra el proyecto que se quiere ver.
        $project = Project::find($id);

        //Laravel busca automáticamente el proyecto por su ID en la URL.
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //Se encuentra el proyecto que se quiere editar.
        $project = Project::find($id);

        //Se envia el proyecto actual a la vista para rellenar el formulario.
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project) {  //El string que se debe enviar al método según Copilot es $project. ¿Por qué?
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
        //Se borra el registro de la base de datos.
        $project->delete();

        // Redirigimos al listado
        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto eliminado.');
    }
}

/*NOTA: En update y destroy se pasa Project $project por 3 razones:
-Para buscar el registro de un objeto Project, sin tener que crear la consulta SQL con SELECT a mano.
-Evita que se rompa el código si se intenta hacer operaciones sobre un registtro que simplemente no existe. En su lugar, hace que
se muestre el error 404 ("no encontrado").
-Para comprobar permisos y para ejecutar el update (comparando los datos viejos con los nuevos del formulario). 
*/