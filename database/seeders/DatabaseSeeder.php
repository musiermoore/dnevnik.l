<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $usersCount = 100;

        \App\Models\Profile::factory($usersCount)->create();

        $this->call(RoleSeeder::class);
        $this->call(ModelHasRolesSeeder::class);

        $this->call(GroupSeeder::class);
        $this->call(StudentsHasGroupSeeder::class);
    }
}
