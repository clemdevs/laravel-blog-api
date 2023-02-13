<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $data = $request->validated();

        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //assign a default role to the user as 'user'
        $user_role = Role::where('slug', 'user')->first();
        $user->roles()->attach($user_role);


        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $token = $user->createToken('app_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
    }

    public function logout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return response([
            'success' => true
        ]);
    }
}
