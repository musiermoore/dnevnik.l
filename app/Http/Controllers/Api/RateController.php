<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RateAttendanceRequest;
use App\Http\Requests\Api\RateRequest;
use App\Http\Resources\RateCollection;
use App\Http\Resources\RateResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Rate;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function setAttendanceStudent(RateAttendanceRequest $request)
    {
        if (empty($request->student_id)) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "Bad request",
                    'errors'    => "Student is not selected",
                ],
            ])->setStatusCode(400);
        }

        $student = User::find($request->student_id);

        $group = Timetable::where('id', $request->lesson_id)->pluck('group_id');

        if ($student->group[0]->id != $group[0]) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "Bad request",
                    'errors'    => "Groups differ",
                ],
            ])->setStatusCode(400);
        }

        $error = $this->checkStudent($student);
        if (! empty($error)) {
            return response()
                ->json($error["message"])
                ->setStatusCode($error["code"]);
        }

        $data = [
            'student_id'    => $request->student_id,
            'lesson_id'     => $request->lesson_id,
            'attendance'    => $request->attendance,
        ];

        $attendance = Rate::where('student_id', $request->student_id)->where('lesson_id', $request->lesson_id)->first();


        if (! empty($attendance)) {
            $attendance->attendance = $data['attendance'];
            $attendance->save();
        } else {
            $attendance = Rate::create($data);
        }

        return response()->json([
            'data' => [
                'code'      => 200,
                'message'   => "Attendance is given",
                'student'   => UserResource::make($student),
                'rate'      => RateResource::make($attendance),
            ],
        ])->setStatusCode(200);
    }

    public function setRateStudent(RateRequest $request)
    {
        if (empty($request->student_id)) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "Bad request",
                    'errors'    => "Student is not selected",
                ],
            ])->setStatusCode(400);
        }

        $student = User::find($request->student_id);

        $group = Timetable::where('id', $request->lesson_id)->pluck('group_id');

        if ($student->group[0]->id != $group[0]) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "Bad request",
                    'errors'    => "Groups differ",
                ],
            ])->setStatusCode(400);
        }

        $error = $this->checkStudent($student);
        if (! empty($error)) {
            return response()
                ->json($error["message"])
                ->setStatusCode($error["code"]);
        }

        $data = [
            'student_id'    => $request->student_id,
            'lesson_id'     => $request->lesson_id,
            'rate'          => $request->rate,
        ];

        $rate = Rate::where('student_id', $request->student_id)->where('lesson_id', $request->lesson_id)->first();

        if (! empty($rate)) {
            $rate->rate = $data['rate'];
            $rate->save();
        } else {
            $rate = Rate::create($data);
        }

        return response()->json([
            'data' => [
                'code'      => 200,
                'message'   => "Rate is given",
                'student'   => UserResource::make($student),
                'rate'      => RateResource::make($rate),
            ],
        ])->setStatusCode(200);
    }

    /*
     * Get all student rates
     */
    public function getAllStudentRates(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            $student = $request->user();
        } elseif ($user->hasAnyRole(['admin', 'teacher', 'educational_part'])) {
            if (empty($request->query('student_id'))) {
                return response()->json([
                    'error' => [
                        'code'      => 400,
                        'message'   => "Bad request",
                        'errors'    => "Student is not selected",
                    ],
                ])->setStatusCode(400);
            }

            $student = User::find($request->query('student_id'));
            $error = $this->checkStudent($student);

            if (! empty($error)) {
                return response()
                    ->json($error["message"])
                    ->setStatusCode($error["code"]);
            }
        }

        $lesson = Timetable::where('group_id', Group::getMainGroup($student->group)->id)
            ->orderBy('subject_id')
            ->get()
            ->groupBy(function($events) {
                return Subject::find($events->subject_id)->subject; // А это то-же поле по нему мы и будем группировать
            });


        $rates = Rate::where('student_id', $student->id)
            ->with('lesson')
            ->get()
            ->groupBy(function ($rate) {
                return $rate->lesson->subject->id;
            });


        return response()->json([
            'data' => [
                'code'      => 200,
                'student'   => UserResource::make($student),
                'rates'     => RateCollection::make($rates),
            ],
        ])->setStatusCode(200);
    }

    private function checkStudent($student)
    {
        $error = null;

        if (empty($student)) {
            $error["code"] = 404;
            $error["message"] = [
                'error' => [
                    'code'      => $error["code"],
                    'message'   => "Not found",
                    'errors'    => "Student not found",
                ],
            ];
        }
        elseif (! $student->hasRole('student')) {
            $error["code"] = 400;
            $error["message"] = [
                'error' => [
                    'code'      => $error["code"],
                    'message'   => "Bad request",
                    'errors'    => "User is not a student",
                ],
            ];
        }

        return $error;
    }

    /*
     * Get rates for a student
     */
    public function getRatesForStudentBySubject(Request $request)
    {
        $subject = $request->query('subject');
    }

    /*
     * Get rates for a teacher
     */
    public function getRatesForGroupByLesson($id)
    {
        $users =Timetable::find($id)->group->students;

        return response()->json([
            'data' => [
                'students' => UserCollection::make($users),
            ],
        ]);
    }
}
