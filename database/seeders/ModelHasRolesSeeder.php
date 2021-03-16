<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countRows = DB::table('spatie_roles')->count();
        $users = User::all();

        $roles = DB::table('spatie_roles')->pluck('id')->toArray();

        foreach ($users as $user)
        {
            if ($user->id == 1)
                $user->assignRole('admin');
            else
            {
                $user->assignRole($roles[rand(1, $countRows - 1)]);
            }
        }
    }
}
