<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    //register user
    public function register(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|string|max:15|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $roleName = $r->input('role', 'user');
            $role = Role::firstOrCreate(['name' => $roleName]);

            $user = new User();
            $user->name = $r->name;
            $user->email = $r->email;
            $user->phone_number = $r->phone_number;
            $user->password = bcrypt($r->password);
            $user->role_id = $role->id;
            $user->save();

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],

                ],
                'message' => 'User registered successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to register user');
        }
    }

    //login user
    public function login(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user = User::where('email', $r->email)->first();
            $role = Role::where('role_id', $r->role_id);

            if (!$user || !Hash::check($r->password, $user->password)) {
                return response()->json([
                    'error' => ['email' => ['The provided credentials are incorrect.']],
                ], 401);
            }

            $token = $user->createToken('auth-token')->plainTextToken;


            //response user,token
            return response()->json([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'role' => $role,
                    'token' => $token,
                ],

                // 'access_token' => $token,
                // 'token_type' => 'Bearer',
                'message' => 'User logged in successfully'
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to log in user');
        }
    }

    //user logout
    public function logout(Request $r)
    {
        try {
            return response()->json([
                'message' => 'User logged out successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to log out user');
        }
    }

    //user forgot password
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => __($status),
                ], 200);
            }

            return response()->json([
                'message' => __($status),
            ], 400);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to process password reset');
        }
    }

    //recovery account by email
    public function recoveryAccount(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            return response()->json([
                'message' => 'Recovery email sent successfully',
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to send recovery email');
        }
    }

    //recovery account by phone number
    public function recoveryAccountByPhone(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'phone' => 'required|string|max:15',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            return response()->json([
                'message' => 'Recovery SMS sent successfully',
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to send recovery SMS');
        }
    }
}
