<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refugee;

class RefugeeController extends Controller
{
// إنشاء مهجر جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'number_of_family_member' => 'required|integer',
            'need' => 'required|string',
            'date_of_birth' => 'required|date',
            'status' => 'required|string',
        ]);

        $refugee = Refugee::create($validated);
        return response()->json($refugee, 201);
    }

    // تعديل بيانات مهجر
    public function update(Request $request, $id)
    {
        $refugee = Refugee::findOrFail($id);
        $refugee->update($request->all());
        return response()->json($refugee);
    }

    // حذف مهجر
    public function destroy($id)
    {
        $refugee = Refugee::findOrFail($id);
        $refugee->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

