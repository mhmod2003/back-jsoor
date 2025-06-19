<?php

namespace App\Http\Controllers;


use App\Models\RequestT;
use Illuminate\Http\Request;

class RequestTController extends Controller
{
    public function sendJoinRequest(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $existing = RequestT::where('user_id', $validated['user_id'])->first();
        if ($existing) {
            return response()->json(['message' => 'Request already exists'], 400);
        }

        $joinRequest = RequestT::create([
            'user_id' => $validated['user_id'],
            'status' => 'pending'
        ]);

        return response()->json($joinRequest, 201);
    }

    // عرض جميع الطلبات للأدمن
    public function list()
    {
        return response()->json(RequestT::with('user')->get());
    }

    // تحديث حالة الطلب (من قبل الأدمن)
    public function updateStatus(Request $request, $id)
    {
        $requestData = $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $joinRequest = RequestT::findOrFail($id);
        $joinRequest->status = $requestData['status'];
        $joinRequest->save();

        // جعل حساب المستخدم فعّال إذا تم القبول
        if ($joinRequest->status == 'accepted') {
            $user = $joinRequest->user;
            $user->status = 'active'; // أضف هذا العمود في جدول users إذا غير موجود
            $user->save();
        }

        return response()->json($joinRequest);
    }


}

