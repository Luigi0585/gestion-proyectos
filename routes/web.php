<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');



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
});

require __DIR__.'/auth.php';
