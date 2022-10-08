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

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($request->password == "") {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        }
        if ($user) {
            return new UserResource(true, 'user behasil diupdate', $user);
        } else {
            return new UserResource(false, 'user gagal diupdate', $user);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->delete() == true) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasi dihapus'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'gagal di hapus'
            ]);
        }
    }
}