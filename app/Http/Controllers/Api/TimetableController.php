<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimetableResource;
use App\Models\Group;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    /**
     * Get timetable for week
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimetableForWeek(Request $request)
    {
        $user = $request->user();

        if (empty($request->date)) {
            $date = \Carbon\Carbon::now();
        } else {
            $date = \Carbon\Carbon::parse($request->date);
        }

        $weekStartDate = $date->startOfWeek()->format('Y-m-d');
        $weekEndDate = $date->endOfWeek()->format('Y-m-d');

        if ($user->hasRole(['teacher', 'educational_part'])) {
            $timetable = Timetable::getWeekTimetableForTeacher($user->id);
        } else if ($user->hasRole('student')) {
            $group = Group::getMainGroup($user->group);
            $timetable = Timetable::getWeekTimetableForStudent($group->id);
        }

        $result = Timetable::getWeekTimetableBetweenTwoDates($timetable, $weekStartDate, $weekEndDate);

        return response()->json([
            'data' => TimetableResource::collection($result),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\Response
     */
    public function show(TimeTable $timeTable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeTable $timeTable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeTable $timeTable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeTable $timeTable)
    {
        //
    }
}
