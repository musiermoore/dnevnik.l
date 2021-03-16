<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsHasGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = \App\Models\User::role(['student'])->pluck('id')->toArray();
        $groups = \App\Models\Group::pluck('id')->toArray();

        for ($i = 0; $i < count($students); $i++)
        {
            $data = [
                'user_id'   => $students[$i],
                'group_id'  => $groups[rand(0, count($groups) - 1)],
            ];

            DB::table('students_has_group')->insert($data);
        }
    }
}
