<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $user = $request->user();

        return UserResource::make($user);
    }

    /**
     * Get users by role
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersByRole(RoleRequest $request)
    {
        $roles = $request->roles;

        $users = User::getUsersByRole($roles);

        return response()->json([
            'data' => UserResource::collection($users)->sortBy('roles')->values(),
        ], 200);
    }

}
