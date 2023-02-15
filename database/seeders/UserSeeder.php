<?php

namespace Database\Seeders;

use App\Models\User;
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
        //Create an admin user.
        User::factory()->count(1)
            ->hasRoles(1, ['name' => 'Admin'])
            ->create(['name' => 'Jon Doe', 'email' => 'jondoe@local.host']);

        //Create 10 users.
        User::factory(10)
            ->create();

    }
}
