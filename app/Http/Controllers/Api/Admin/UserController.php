<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::when(Request()->q, function ($users) {
            $users = $users->where('name', 'like', '%' . Request()->q . '%');
        })->latest()->paginate(5);
        return new UserResource(true, 'Data List User', $users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($user) {
            return new UserResource(true, 'Data User Berhasil Ditambahkan', $user);
        } else {
            return new UserResource(false, 'Data User Gagal Ditambahkan', $user);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return new UserResource(true, 'Detail Data User', $user);
        } else {
            return new UserResource(false, 'Detail Data User tidak ditemukan', $user);
        }
    }
}