<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     */
    public function getUsersByRole(Request $request)
    {
        $users = User::getUsersByRole($request->query('role'));

        return $users;
    }

}
