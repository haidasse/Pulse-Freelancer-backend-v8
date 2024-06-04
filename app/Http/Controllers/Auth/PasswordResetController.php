<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        }
        $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email,
                'token' => Str::random(60)
        ]);
        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(10)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        return response()->json($passwordReset);
    }
    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return $user;
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'oldpassword' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validation->fails()) {
            response()->json($validation->errors(), 400);
        }

        if ($request->oldpassword == $request->password) {
            return response()->json(['message' => 'The new password shoud be different from current password'], 404);
        }

        if (Auth::guard('web')->attempt([
            'email' => Auth::user()->email,
            'password' => $request->oldpassword
            ])) {
            $user = Auth::user() ;
            $user->password = bcrypt($request->password);
            $user->save() ;

            return response()->json(['message' => 'Password updated'], 200);
        } else {
            return response()->json(['message' => 'Current Password doesnt match'], 404);
        }
    }
}
