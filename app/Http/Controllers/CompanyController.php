<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'email'       => 'required|email|unique:users',
            'phone'       => 'required|string|min:10|unique:companies',
            'password'    => 'required|string|min:6',
            'description' => 'nullable|string',
            'social_link' => 'nullable|url',
        ]);

        // إنشاء مستخدم من النوع company
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'type'     => 'company',
        ]);

        // إنشاء الشركة وربطها بالمستخدم
        $company = Company::create([
            'user_id'     => $user->id,
            'name'        => $request->name,
            'phone'       => $request->phone,
            'description' => $request->description,
            'social_link' => $request->social_link,
            'status'      => 'pending', // أو 'inactive' حسب النظام عندك
            'map_location'   => null,      // مبدئياً فارغ
        ]);

        return response()->json([
            'message' => 'Company registered successfully and waiting for approval.',
            'data'    => $company,
        ], 201);
    }
}
