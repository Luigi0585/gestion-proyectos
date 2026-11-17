<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
  protected $fillable = [
    'title', 
    'description', 
    'due_date', 
    'status', 
    'user_id',
  ];

  //Relación 1:N (Inversa): El usuario que creó este proyecto.
  //Establece la relación entre la tabla user y project_users.
    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación N:N: Los usuarios que participan en este proyecto
    public function contributors() {
        return $this->belongsToMany(User::class, 'project_users') //*
                    ->withPivot('role', 'contribution_hours')
                    ->withTimestamps();
    }
}
//*De no ser porque cometí el error de poner el nombre de la tabla N:N de usuarios y projectos como 'project_users', se debe 
//escribir como parámetro para que Laravel pueda reconocerla. Por defecto, Laravel busca nombres en singular (es más, de no haber
//cometido este error no haría falta especificarlo como parámetro).