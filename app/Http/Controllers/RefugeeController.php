<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refugee;

class RefugeeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'number_of_family_members' => 'required|integer',
            'need' => 'required|string',
            'date_of_birth' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $refugee = Refugee::create($request->all());

        return response()->json([
            'message' => 'Refugee created successfully',
            'data' => $refugee
        ], 201);
    }
}

