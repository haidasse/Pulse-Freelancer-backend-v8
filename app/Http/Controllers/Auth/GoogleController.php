<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Api\Controller;
use Auth;
use Exception;
use Socialite;
use Illuminate\Support\Str;
use App\Models\AauthAcessToken;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // Google login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Google callback
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);

                return response()->json([
                    'message' => 'User already registered'
                ], 200);
            } else {
                $newUser = User::create([
                    'first_name' => $user->user['given_name'],
                    'last_name' => $user->user['family_name'],
                    'email' => $user->user['email'],
                    'google_id' => $user->user['id'],
                    'google_token' => $user->token,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'password' => Str::random(20),
                ]);

                Auth::login($newUser);

                $user=Auth::user();
                $token=$user->createToken('google-api')->accessToken;

                return response()->json($token, 200);
            }
            //dump($user);
            // TODO get token
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
