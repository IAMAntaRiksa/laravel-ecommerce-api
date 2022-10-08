<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $credential = $request->only('password', 'email');

        if (!$token = auth()->guard('api_customer')->attempt($credential)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is incorrect',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => auth()->guard('api_customer')->user(),
            'token' => 'Bearer ' . $token,
        ], 201);
    }

    public function getUser()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data user',
            'user' => auth()->guard('api_customer')->user(),
        ], 201);
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = JWTAuth::refresh(JWTAuth::getToken());
        // set user with new token
        $user = JWTAuth::setToken($refreshToken)->toUser();
        // set header "Authorization" with type Bearer + new "token"
        $request->headers->set("Authorization", "Bearer" . $refreshToken);
        //response data "user" dengan "token" baru
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $refreshToken,
        ], 200);
    }

    public function logout()
    {
        $user = JWTAuth::invalidate(JWTAuth::getToken());
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Success Logout'
            ]);
        }
    }
}