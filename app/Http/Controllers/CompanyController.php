<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'email'       => 'required|email|unique:companies,email',
            'password'    => 'required|string|min:6',
            'description' => 'nullable|string',
            'social_link' => 'nullable|url',
        ]);

        $company = Company::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'description' => $request->description,
            'social_link' => $request->social_link,
        ]);

        return response()->json([
            'message' => 'Company created successfully',
            'data'    => $company
        ], 201);
    }
}

