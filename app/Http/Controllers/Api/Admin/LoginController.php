<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api_admin')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is incorrect',
            ], 401);
        }
        // Login success with generate token
        return response()->json([
            'success' => true,
            'user' => auth()->guard('api_admin')->user(),
            'token' => 'Bearer ' . $token,

        ], 200);
    }

    public function getUser()
    {
        try {
            $user = auth()->guard('api_admin')->user();
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }
    }

    public function refreshToken(Request $request)
    {
        // refersh Token
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