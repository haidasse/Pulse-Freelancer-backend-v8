<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\EventLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $result = [
                'token' => $user->createToken('api-auth')->accessToken,
                'user' => [
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'role_id' => $user->role_id
                ]
            ];

            EventLog::log([
                "action" => "login"
            ]);

            return response()->json($result, 200);
        } else {
            return response()->json(['message' => 'Password doesnt match'], 404);
        }
    }


    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required|min:4',
            'last_name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $user['token'] = $user->createToken('api-auth')->accessToken;

        $user->sendEmailVerificationNotification();

        return $user;
    }

    public function logout()
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'message' => 'Logout successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unable to Logout'
            ]);
        }
    }
}
