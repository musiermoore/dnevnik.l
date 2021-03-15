<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countRows = DB::table('spatie_roles')->count();
        if ($countRows == 0)
        {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
            Role::create(['name' => 'teacher', 'guard_name' => 'api']);
            Role::create(['name' => 'student', 'guard_name' => 'api']);
            Role::create(['name' => 'educational_part', 'guard_name' => 'api']);
        }

        $users = User::all();

        $roles = DB::table('spatie_roles')->get();

        foreach ($users as $user)
        {
            if ($user->id == 1)
                $user->assignRole('admin');
            else
            {
                $random = rand(1, $countRows - 1);

                $user->assignRole($roles[$random]->id);
            }

        }
    }
}
