<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|email|string|unique:users,email',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->mixedCase()->numbers()->symbols()
                ]
            ]
        );

        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])

        ]);

        //assign a default role to the user as 'user'
        $user_role = Role::pluck('id', 'slug')->toArray()['user'];
        $user->roles()->attach($user_role);


        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)){

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
