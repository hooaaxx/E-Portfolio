<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Master', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'Client', 'guard_name' => 'web'],
        ];

        foreach ($items as $item) {
            Role::updateOrCreate(['id' => $item['id']], $item);
        }

        $permissions = Permission::all();

        Role::where('name', 'Master')->firstOrFail()->syncPermissions($permissions);
    }
}
