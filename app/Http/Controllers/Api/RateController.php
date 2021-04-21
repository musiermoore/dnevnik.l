<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RateRequest;
use App\Http\Resources\RateResource;
use App\Http\Resources\UserResource;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function setRateStudent(RateRequest $request)
    {
        $studentId = $request->query('student_id');

        if (empty($request->query('student_id'))) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "Bad request",
                    'errors'    => "Student is not selected",
                ],
            ])->setStatusCode(400);
        }

        $student = User::find($studentId);

        if (empty($student)) {
            return response()->json([
                'error' => [
                    'code'      => 404,
                    'message'   => "Not found",
                    'errors'    => "Student not found",
                ],
            ])->setStatusCode(404);
        }

        if (! $student->hasRole('student')) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "Bad request",
                    'errors'    => "User is not a student",
                ],
            ])->setStatusCode(400);
        }

        $data = [
            'student_id'    => $request->student_id,
            'lesson_id'     => $request->lesson_id,
            'rate'          => $request->rate,
        ];

        $rate = Rate::where('student_id', $request->student_id)->where('lesson_id', $request->lesson_id)->first();

        if (! empty($rate)) {
            $rate->rate = $data['rate'];
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
     * Get all rates for a student
     */
    public function getAllRatesForStudent(Request $request)
    {
        if ($request->user()->hasRole('student')) {
            $student = $request->user();
        } elseif ($request->user()->hasAnyRole(['admin', 'teacher', 'educational_part'])) {
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

            if (empty($student)) {
                return response()->json([
                    'error' => [
                        'code'      => 404,
                        'message'   => "Not found",
                        'errors'    => "Student not found",
                    ],
                ])->setStatusCode(404);
            }

            if (! $student->hasRole('student')) {
                return response()->json([
                    'error' => [
                        'code'      => 400,
                        'message'   => "Bad request",
                        'errors'    => "User is not a student",
                    ],
                ])->setStatusCode(400);
            }
        }

        $rates = Rate::where('student_id', $student->id)->get();

        return response()->json([
            'data' => [
                'code'      => 200,
                'student'   => UserResource::make($student),
                'rates'     => RateResource::collection($rates),
            ],
        ])->setStatusCode(200);
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
    public function getRatesForGroupBySubject(Request $request)
    {
        $subject = $request->query('subject');
    }
}
