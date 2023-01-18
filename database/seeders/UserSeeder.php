<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)
            ->hasRoles(1, ['name' => 'Admin', 'slug' => 'admin'])
            ->create(['name' => 'Jon Doe', 'email' => 'jondoe@local.host']);
    }
}
