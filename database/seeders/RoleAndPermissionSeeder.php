<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el permiso para administrar ubicaciones
        $permission = Permission::firstOrCreate([
            'nombre' => 'ubicaciones.manager'
        ], [
            'is_acceso' => 1
        ]);

        // Crear el nuevo rol "User"
        $role = Role::firstOrCreate([
            'nombre' => 'User'
        ]);

        // Asociar el permiso al rol si aún no lo tiene
        if (!$role->permissions()->where('permissions.id', $permission->id)->exists()) {
            $role->permissions()->attach($permission->id);
        }
    }
}
