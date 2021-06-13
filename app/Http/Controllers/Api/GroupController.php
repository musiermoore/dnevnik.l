<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GroupRequest;
use App\Http\Requests\Api\GroupUpdateRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getGroups()
    {
        return response()->json([
        'data'  => [
            'group'     => GroupResource::collection(Group::all()),
        ],
    ], 200);;
    }

    public function getStudentsByGroup(Request $request)
    {
        $groups = Group::getGroupById($request->group);

        if (empty($request->group)) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "No results",
                ],
            ], 400);
        }

        return response()->json([
            'data'  => [
                'group'     => GroupResource::make($groups),
                'students'  => UserResource::collection($groups->students),
            ],
        ], 200);
    }

    public function index()
    {
        $groups = Group::all();

        return response()->json([
            'data'  => [
                'groups'     => GroupResource::collection($groups),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupRequest $request
     * @return mixed
     */
    public function store(GroupRequest $request)
    {
        $data = $request->all();
        $data['enrollment_date'] = Carbon::createFromFormat('Y-m-d', $data['enrollment_date'])->format('Y');
        $data['enrollment_date'] = Carbon::createFromDate($data["enrollment_date"], 9, 1)->format('Y-m-d');
        $data['graduation_date'] = Carbon::createFromDate($data["enrollment_date"], 9, 1)->addYears($data["duration_study"])->format('Y-m-d');

        $curator = User::find($data["curator_id"]);

        if (! ($curator->hasRole(['teacher', 'educational_part', 'admin']) && ! $curator->hasRole('student'))) {
            return response()->json([
                'error' => [
                    'code'      => 400,
                    'message'   => "User doesn't have roles",
                ],
            ], 400);
        }

        $group = Group::create($data);

        return response()->json([
            'data'  => [
                'group'     => GroupResource::make($group),
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return response()->json([
                'error' => [
                    'code'      => 404,
                    'message'   => "Not found",
                    'errors'    => "Group not found",
                ],
            ]);
        }

        return response()->json([
            'data' => GroupResource::make($group),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GroupUpdateRequest $request, $id)
    {
        $data = $request->input();

        $group = Group::find($id);
        $data['graduation_date'] = Carbon::parse($data["enrollment_date"])->addYears($data["duration_study"])->format('Y-m-d');

        $group->update($data);

        return response()->json([
            'data' => [
                'group' => GroupResource::make($group),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeTable  $timeTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return response()->json([
                'error' => [
                    'code'      => 404,
                    'message'   => "Not found",
                    'errors'    => "User not found",
                ],
            ]);
        }

        $group->delete();

        return response()->json([
            'data' => [
                'code'      => 200,
                'message'   => 'Group was deleted',
            ],
        ]);
    }
}
