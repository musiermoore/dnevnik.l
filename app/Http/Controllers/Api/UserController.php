<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserUpdateRequest;
use App\Http\Requests\Api\RoleRequest;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => UserResource::make($user),
        ])->setStatusCode(200);
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

        $users = User::getUsersByRole($roles, $groupId)->get();

        return response()->json([
            'data' => UserResource::collection($users)->sortBy('roles')->sortBy('group')->values(),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (! empty($request->query('roles'))) {
            $users = User::getUsersByRole($request->query('roles'), $request->query('group'))->with('roles');
        } else {
            $users = User::with('roles');
        }

        if (! empty($request->query('field')) && ! empty($request->query('value'))) {
            $result = Profile::where($request->query('field'), 'like', '%' . $request->query('value') . '%')->pluck('user_id');

            $users = $users->whereIn('id', $result);
        }

        $users = $users->get()->sortBy(function($user) {
            return $user->roles;
        });

        return response()->json([
            'data' => UserResource::collection($users),
        ])->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $data = $request->input();

        $user = User::find($id);

        $user->update([
            $data['login'],
            $data['email'],
        ]);

        $user->roles()->detach();
        $user->assignRole($data['roles']);

        $user->profile()->update($data["profile"]);

        return response()->json([
            'data' => [
                'user' => UserResource::make($user),
            ]
        ]);
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
