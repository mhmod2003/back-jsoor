<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // تسجيل الدخول للأدوار: superadmin, admin, survey_team
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
        if (! in_array($user->role, ['superadmin','admin','survey_team'])) {
            Auth::logout();
            return response()->json([
                'message' => 'غير مصرح لك بالدخول.'
            ], 403);
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

    // تسجيل دخول المستخدم العادي (role = user)
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

    // تسجيل مستخدم جديد (refugee أو company)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|digits:10|unique:users',      // صار مطلوب
            'type'     => 'required|string',                      // صار نصيّ بدون حصر enum
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

    // تسجيل الخروج
    public function logout(Request $request)
    {
        // التأكد من أن المستخدم مسجل دخول
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // حذف جميع التوكنات المرتبطة بالمستخدم
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح'], 200);
    }
}
