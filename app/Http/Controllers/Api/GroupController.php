<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
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
            'data'  => GroupResource::collection($groups),
        ], 200);
    }
}
