<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
            if (Auth::guard('customer')->attempt(['username' => request('username'), 'password' => request('password')])) {
                $user = Auth::guard('customer')->user();
                // $user = Auth::guard('auth')->user();
                $token = $user->createToken('auth-customer-token',['*'])->plainTextToken;

                $response = [
                    'id' => $user->id,
                    'token' => $token,
                    'type' => "2",
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Login Successful',
                    'data' => $response,
                ]);
            }
            elseif(Auth::attempt(['username' => request('username'), 'password' => request('password')]))
            {
                $user = Auth::user();
                $token = $user->createToken('auth-customer-token',['*'])->plainTextToken;
                $response = [
                    'id' => $user->id,
                    'token' => $token,
                    'type' => $user->is_admin,
                ];
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login Successful',
                    'data' => $response,
                ]);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorised',
                ], 401);
            }
    }
}
