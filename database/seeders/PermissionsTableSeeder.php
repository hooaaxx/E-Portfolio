<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'view', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'create', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'update', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'delete', 'guard_name' => 'web'],
        ];

        foreach ($items as $item) {
            Permission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
