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
        $groups = Group::getGroupByName($request->group);
        if (!empty($request->group))
            return response()->json(GroupResource::collection($groups));
        else
            return response()->json([], 400);
    }
}
