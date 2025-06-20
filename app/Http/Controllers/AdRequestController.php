<?php
namespace App\Http\Controllers;

use App\Models\RequestT;
use App\Models\Notification;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdRequestController extends Controller
{
    public function index(){
        return RequestT::all();
    }
    // موافقة الشركة على المهجر في إعلانها
   public function approveAdRequest(Request $request, $id)
{
    // جلب الطلب
    $requestData = RequestT::find($id);
    if (!$requestData) {
        return response()->json(['message' => 'الطلب غير موجود'], 404);
    }

    // جلب الإعلان
    $ad = Ad::with('company')->find($requestData->ads_id);
    if (!$ad) {
        return response()->json(['message' => 'الإعلان غير موجود'], 404);
    }

    // التحقق من وجود الشركة المرتبطة
    if (!$ad->company) {
        return response()->json(['message' => 'الإعلان غير مرتبط بأي شركة'], 422);
    }

    // التحقق من صلاحيات المستخدم
    if ($request->user()->id !== $ad->company->user_id) {
        return response()->json(['message' => 'ليس لديك صلاحية الموافقة على هذا الطلب'], 403);
    }

    // تحديث حالة الطلب
    $requestData->status = 'accepted';
    $requestData->save();

    // إنشاء إشعار
    Notification::create([
        'user_id' => $requestData->user_id,
        'title' => 'تم قبولك في الإعلان',
        'description' => 'تهانينا! تم قبول طلبك للانضمام إلى الإعلان: "' . $ad->title . '"',
    ]);

    return response()->json(['message' => 'تمت الموافقة على الطلب وتم إرسال إشعار']);
}


    // رفض الشركة لطلب المهجر
    public function rejectAdRequest(Request $request, $id)
    {
        $requestData = RequestT::findOrFail($id);
        $ad = Ad::findOrFail($requestData->ads_id);

        if ($request->user()->id !== $ad->company->user_id) {
            return response()->json(['message' => 'ليس لديك صلاحية رفض هذا الطلب'], 403);
        }

        $requestData->status = 'rejected';
        $requestData->save();

        Notification::create([
            'user_id' => $requestData->user_id,
            'title' => 'تم رفض طلبك',
            'description' => 'نأسف، لم يتم قبولك في الإعلان: "' . $ad->title . '"',
        ]);

        return response()->json(['message' => 'تم رفض الطلب وإرسال إشعار']);
    }
}
