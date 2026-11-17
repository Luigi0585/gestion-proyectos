<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Genera las 7 rutas RESTful (projects.index, projects.create, projects.store, projects.show, projects.edit, projects.update 
    // y projects.destroy.) protegidas por sesión.
    Route::resource('projects', ProjectController::class);

    //Añade o actualiza un colaborador en un proyecto.
    Route::post('/projects/{project}/contributors', [ProjectController::class, 'addContributor'])
    ->name('projects.contributors.store');

    //Quita un colaborador de un proyecto.
    Route::delete('/projects/{project}/contributors/{user}', [ProjectController::class, 'removeContributor'])
    ->name('projects.contributors.destroy');
});

require __DIR__.'/auth.php';
