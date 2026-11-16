<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscamos el primer usuario existente; si no hay ninguno, creamos uno de prueba
        // Esto evita errores con la clave foránea 'user_id'
        $user = User::first() ?? User::factory()->create([
            'name'  => 'Usuario Prueba',
            'email' => 'prueba@ejemplo.com',
        ]);

        // 2. Definimos los 4 proyectos
        $projects = [
            [
                'title'       => 'Plataforma E-Commerce',
                'description' => 'Desarrollo de tienda online con pasarela de pagos y gestión de inventario.',
                'due_date'    => '2026-12-15',
                'status'      => 'en_progreso',
                'user_id'     => $user->id,
            ],
            [
                'title'       => 'App Móvil de Reservas',
                'description' => 'Aplicación multiplataforma para reservar pistas deportivas en tiempo real.',
                'due_date'    => '2027-01-20',
                'status'      => 'pendiente',
                'user_id'     => $user->id,
            ],
            [
                'title'       => 'Rediseño Portal Corporativo',
                'description' => 'Actualización visual y optimización SEO de la web principal de la empresa.',
                'due_date'    => '2026-11-30',
                'status'      => 'completado',
                'user_id'     => $user->id,
            ],
            [
                'title'       => 'Sistema de Tickets Soporte',
                'description' => 'Herramienta interna para gestionar incidencias técnicas y atención al cliente.',
                'due_date'    => '2027-02-10',
                'status'      => 'pendiente',
                'user_id'     => $user->id,
            ],
        ];

        // 3. Insertamos cada proyecto usando el modelo Eloquent
        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}