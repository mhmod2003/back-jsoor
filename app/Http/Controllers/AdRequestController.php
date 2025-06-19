<?php
namespace App\Http\Controllers;

use App\Models\RequestT as JoinRequest;
use App\Models\Notification;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdRequestController extends Controller
{
    // موافقة الشركة على المهجر في إعلانها
    public function approveAdRequest(Request $request, $id)
    {
        $requestData = JoinRequest::findOrFail($id);
        $ad = Ad::findOrFail($requestData->ads_id);

        // تأكيد أن المستخدم الحالي هو صاحب الشركة
        if ($request->user()->id !== $ad->company->user_id) {
            return response()->json(['message' => 'ليس لديك صلاحية الموافقة على هذا الطلب'], 403);
        }

        $requestData->status = 'accepted';
        $requestData->save();

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
        $requestData = JoinRequest::findOrFail($id);
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
