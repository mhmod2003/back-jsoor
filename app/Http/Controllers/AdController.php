<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    // الشركة تضيف إعلان
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'location' => 'nullable|string',
        'meet_link' => 'nullable|url',
        'service_id' => 'required|exists:services,id',
    ]);

    $user = Auth::user();

    $service = Service::findOrFail($request->service_id);

    // توليد رابط Jitsi Meet فقط إذا كانت الخدمة دعم نفسي
    $meetLink = null;
    if (strtolower($service->service_type) === 'دعم نفسي') {
        $roomName = 'support-' . uniqid();
        $meetLink = "https://meet.jit.si/" . $roomName;
    } else {
        $meetLink = $request->meet_link; // يسمح للمستخدم بإدخاله إن أراد
    }

    $ad = Ad::create([
        'company_id' => $user->company->id,
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'location' => $request->location,
        'meet_link' => $meetLink,
        'service_id' => $request->service_id,
        'status' => 'pending',
    ]);

    return response()->json([
        'message' => 'Ad submitted for review',
        'ad' => $ad
    ], 201);
}


    // الأدمن يشاهد كل الإعلانات قيد المراجعة
    public function pendingAds()
    {
        $user = Auth::user();
        if ($user->type !== 'Admin') {
            return response()->json(['message' => 'غير مصرح لك'], 403);
        }

        $ads = Ad::where('status', 'pending')->get();
        return response()->json($ads);
    }

    // ✅ الأدمن يوافق على إعلان
    public function approve($id)
    {
        $user = Auth::user();
        if ($user->type !== 'Admin') {
            return response()->json(['message' => 'غير مصرح لك'], 403);
        }

        $ad = Ad::findOrFail($id);
        $ad->status = 'approved';
        $ad->save();

        return response()->json(['message' => 'تمت الموافقة على الإعلان']);
    }

    // ✅ الأدمن يرفض إعلان
    public function reject($id)
    {
        $user = Auth::user();
        if ($user->type !== 'Admin') {
            return response()->json(['message' => 'غير مصرح لك'], 403);
        }

        $ad = Ad::findOrFail($id);
        $ad->status = 'rejected';
        $ad->save();

        return response()->json(['message' => 'تم رفض الإعلان']);
    }
}
