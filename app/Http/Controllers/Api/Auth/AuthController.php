<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');
        $data['age'] = Carbon::parse($data['birthday'])->diffInYears();

        $user = User::create($data);
        $user->assignRole($data['roles']);
        $user->profile()->create($data);

        return response()->json()->setStatusCode(204);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->all();

        $user = User::where('login', $data['login'])->first();

//        if ( !$user || Hash::check($data['password'], $user->password)) {
        if ( !$user || $data['password'] != $user->password) {
            return response()->json([
                'error' => [
                    'code'       => 401,
                    'message'    => "Unauthorized",
                    'errors'     => [
                        'phone'  => "Login or password incorrect",
                    ],
                ],
            ], 401);
        }

        return response()->json([
            'data'  => [
                'token' => $user->createToken('Auth Token')->accessToken
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
