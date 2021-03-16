<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curatorsId = \App\Models\User::role(['teacher', 'educational_part'])->pluck('id')->toArray();

        $enrollmentDate = '2021-09-01';

        for ($i = 0; $i < 4; $i++)
        {
            $durationStudy = rand(3, 5);

            $groups = [
                'ПКС-371',
                'ТО-107',
                'ИСП-381',
                'ТД-202',
            ];

            $data = [
                'name'                  => $groups[$i],
                'curator_id'            => $curatorsId[rand(0, count($curatorsId) - 1)],
                'enrollment_date'       => \Carbon\Carbon::parse($enrollmentDate),
                'graduation_date'       => \Carbon\Carbon::parse($enrollmentDate)->addYears($durationStudy),
                'duration_study'        => $durationStudy,
            ];

            DB::table('groups')->insert($data);
        }
    }
}
