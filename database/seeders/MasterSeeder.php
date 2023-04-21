<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'id'             => 1,
                'name'           => 'Master',
                'email'          => 'admin@mjbonganay.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ]
        );
        $user->syncRoles('Master');
    }
}
