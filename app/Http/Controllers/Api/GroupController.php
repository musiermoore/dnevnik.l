<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
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
}
