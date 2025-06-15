<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($data)) {
            return response()->json([
                'message' => 'بيانات الدخول غير صحيحة أو ليس لديك صلاحية.'
            ], 401);
        }

        $user = Auth::user();
        if (! in_array($user->type, ['refugee', 'company', 'Admin'])) {
            Auth::logout();
            return response()->json([
                'message' => 'غير مصرح لك بالدخول.'
            ], 403);
        }
         $token = $user->createtoken('vue-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'type'       => $user->type,
                'role'       => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
             'token' => $token,
        ], 200);
    }

    public function userLogin(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($data)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة.'], 401);
        }

        $user = Auth::user();
        if ($user->role !== 'user') {
            Auth::logout();
            return response()->json(['message' => 'غير مصرح لك بالدخول كمستخدم عادي.'], 403);
        }

        $token = $user->createToken('vue-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'type'       => $user->type,
                'role'       => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|digits:10|unique:users',      
            'type'     => 'required|string',                      
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'type'     => $data['type'],
            'role'     => 'user',
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('vue-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'type'       => $user->type,
                'role'       => $user->role,
                'password'   => $data['password'],
                'password_confirmation' => $request->input('password_confirmation'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح'], 200);
    }
}