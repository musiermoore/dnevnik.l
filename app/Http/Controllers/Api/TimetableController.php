<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TimetableRequest;
use App\Http\Resources\TimetableCollection;
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
            'data' => [
                'lessons' => TimetableCollection::collection($result),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TimetableRequest $request
     * @return mixed
     */
    public function store(TimetableRequest $request)
    {
        $teacher = \App\Models\User::find($request->teacher);

        if (! $teacher->hasRole('teacher')) {
            return response()->json([
                'data' => [
                    'error' => [
                        'code'      => 400,
                        'message'   => 'The subject should be taught by the teacher',
                    ],
                ],
            ])->setStatusCode(400);
        }

        $lessonData = $request->all();
        $lessonData['date'] = \Carbon\Carbon::parse($lessonData['date'])->format('Y-m-d');

        $availabilityTeacher = Timetable::checkAvailabilityTeacher($lessonData);
        if (! $availabilityTeacher->isEmpty()) {
            return response()->json([
                'data' => [
                    'error' => [
                        'code'      => 422,
                        'message'   => 'The teacher cannot be in two lessons at once',
                    ],
                ],
            ])->setStatusCode(422);
        }

        $availabilityGroup = Timetable::checkAvailabilityGroup($lessonData);
        if (! $availabilityGroup->isEmpty()) {
            return response()->json([
                'data' => [
                    'error' => [
                        'code'      => 422,
                        'message'   => 'The group cannot be in two lessons at once',
                    ],
                ],
            ])->setStatusCode(422);
        }

        $availabilityClassroom = Timetable::checkAvailabilityClassroom($lessonData);
        if (! $availabilityClassroom->isEmpty()) {
            return response()->json([
                'data' => [
                    'error' => [
                        'code'      => 422,
                        'message'   => 'The classroom is already taken',
                    ],
                ],
            ])->setStatusCode(422);
        }

        $timetable = Timetable::create([
            'subject_id'        => $lessonData['subject'],
            'classroom_id'      => $lessonData['classroom'],
            'lesson_numbers_id' => $lessonData['lesson_number'],
            'group_id'          => $lessonData['group'],
            'teacher_id'        => $lessonData['teacher'],
            'weekday_id'        => $lessonData['weekday'],
            'date'              => $lessonData['date'],
        ]);

        return response()->json([
            'date' => [
                'code'      => 201,
                'message'   => "The lesson is scheduled",
                'lesson'    => TimetableResource::make($timetable),
            ],
        ])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\Response
     */
    public function show(Timetable $timeTable)
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
    public function update(TimetableRequest $request, TimeTable $timeTable)
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
