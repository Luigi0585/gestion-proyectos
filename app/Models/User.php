<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Relación 1:N: Los proyectos creados por este usuario.
    //Establece la relación entre la tabla project y project_users.
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    //Relación N:N: Los proyectos en los que este usuario participa.
    public function collaborations()
    {
        return $this->belongsToMany(Project::class, 'project_users') //También se pasa project_users como parámetro por la misma razón que en Project.php.
                    ->withPivot('role', 'contribution_hours')
                    ->withTimestamps();
    }
}
