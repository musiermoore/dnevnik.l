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

    public function getUserById($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return response()->json([
                'error' => [
                    'code'      => 404,
                    'message'   => "Not found",
                    'errors'    => "Student not found",
                ],
            ]);
        }

        return response()->json([
            'data' => UserResource::make($user),
        ], 200);
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
        $groupId = $request->group;

        $users = User::getUsersByRole($roles, $groupId);

        return response()->json([
            'data' => UserResource::collection($users)->sortBy('roles')->sortBy('group')->values(),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::with('roles')->get()
            ->sortBy(function($user) {
                return $user->roles;
            });

        return response()->json([
            'data' => UserResource::collection($users),
        ])->setStatusCode(200);
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
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return response()->json([
                'error' => [
                    'code'      => 404,
                    'message'   => "Not found",
                    'errors'    => "User not found",
                ],
            ]);
        }

        $user->delete();

        return response()->json([
            'data' => [
                'code'      => 200,
                'message'   => 'User was deleted',
            ],
        ]);
    }
}
