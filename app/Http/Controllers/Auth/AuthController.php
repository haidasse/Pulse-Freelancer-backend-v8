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
        try {
            // Validate the request data
            $validation = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8'
            ]);
    
            // Return validation errors if any
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 400);
            }
    
            // Attempt to authenticate the user
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
    
                // Prepare the response data
                $result = [
                    'token' => $user->createToken('api-auth')->accessToken,
                    'user' => [
                        'email' => $user->email,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'role_id' => $user->role_id
                    ]
                ];
    
                // Log the login event
                EventLog::log([
                    "action" => "login"
                ]);
    
                // Return the response data with a 200 status code
                return response()->json($result, 200);
            } else {
                // Return a 404 status code if authentication fails
                return response()->json(['message' => 'Password does not match'], 404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            return response()->json(['error' => 'Database error occurred: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle all other errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    

    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validation = Validator::make($request->all(), [
                'first_name' => 'required|min:4',
                'last_name' => 'required|min:4',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:8',
            ]);
    
            // Return validation errors if any
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 400);
            }
    
            // Prepare data for user creation
            $data = $request->all();
            $data['password'] = bcrypt($data['password']);
    
            // Create the user
            $user = User::create($data);
    
            // Generate the access token
            $user['token'] = $user->createToken('api-auth')->accessToken;
    
            // Send email verification
            $user->sendEmailVerificationNotification();
    
            // Return the created user with token
            return response()->json($user, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            return response()->json(['error' => 'Database error occurred: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle all other errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function logout()
{
    try {
        // Check if the user is authenticated
        if (Auth::user()) {
            // Revoke the user's token
            $user = Auth::user()->token();
            $user->revoke();

            // Return a success response
            return response()->json([
                'message' => 'Logout successfully'
            ], 200);
        } else {
            // Return an error response if the user is not authenticated
            return response()->json([
                'message' => 'Unable to Logout'
            ], 401);
        }
    } catch (\Exception $e) {
        // Handle all other errors
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

    
}
