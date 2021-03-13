<?php

namespace Database\Seeders;

use App\Models\Profile;
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
        $countUser = 100;


        \App\Models\Profile::factory($countUser)->create();
        \App\Models\User::factory($countUser)->create();
        $this->call(UserSeeder::class);
    }
}
