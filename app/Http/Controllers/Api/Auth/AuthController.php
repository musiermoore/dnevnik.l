<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            // profile
            'lastname'      => 'required|string|max:64',
            'firstname'     => 'required|string|max:64',
            'patronymic'    => 'required|string|max:64',
            'birthday'      => 'required|date',
            'gender'        => 'required|size:1',
            'phone'         => 'required|string|max:64',
            // user
            'login'         => 'required|string|max:64|unique:users',
            'email'         => 'required|string|max:64|unique:users|email',
            'password'      => 'required|string|max:64|confirmed',
        ];

        $data = $request->all();
        $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');
        $data['age'] = $age = Carbon::parse($data['birthday'])->diffInYears();

        $validator = Validator::make($data, $rules);

        if ($validator->fails())
        {
            return response()->json([
                'error' => [
                    'code'      => 422,
                    'message'   => 'Validation error',
                    'errors'    => $validator->errors(),
                ],
            ])->setStatusCode(422);
        }

        $profile = Profile::create($data);
        $profile->users()->create([
            'profile_id'    => $profile->id,
            'login'         => $data['login'],
            'email'         => $data['email'],
            'password'      => $data['password']
        ]);

        return response()->json()->setStatusCode(204);
    }

    public function login(Request $request)
    {
        $rules = [
            'login'         => 'required|string|max:64',
            'password'      => 'required|string|max:64',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code'      => 422,
                    'message'   => 'Validation error',
                    'errors'    => $validator->errors(),
                ],
            ])->setStatusCode(422);
        }

        $user = User::where('login', $data['login'])->first();

        if ( !$user || $data['password'] != $user->password) {
            return response()->json([
                'error' => [
                    'code'       => 401,
                    'message'    => "Unauthorized",
                    'errors'     => [
                        'phone'  => "phone or password incorrect",
                    ],
                ],
            ], 401);
        }

//        return response()->json([
//            'data'  => [
//                'user'  => $user,
//                'token' => $user->createToken('Auth Token')->accessToken,
//            ]
//        ]);
        return response($user->createToken('Auth Token')->accessToken);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
