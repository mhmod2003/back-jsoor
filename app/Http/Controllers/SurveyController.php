<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    // عرض الطلبات بانتظار التحقق
    public function index()
    {
        $requests = RequestModel::where('status', 'pending')->get();
        return response()->json($requests);
    }

    // إرسال فريق استطلاع (من قبل الأدمن)
    public function assign($id)
    {
        $request = RequestModel::findOrFail($id);
        $request->status = 'pending';
        $request->save();

        return response()->json(['message' => 'Survey team assigned']);
    }

    // فريق الاستطلاع يرفع تقرير
    public function teamResponse(Request $req, $id)
    {
        $request = RequestModel::findOrFail($id);
        //$request->status = $req->input('status'); // confirmed / rejected
        $request->save();

        return response()->json(['message' => 'Survey result submitted']);
    }

    // تأكيد دخول المستخدم بعد تحقق الفريق
    public function approve($id)
    {
        $request = RequestModel::findOrFail($id);
        if ($request->survey_status !== 'accepted') {
            return response()->json(['error' => 'Survey not accepted'], 400);
        }

        $request->status = 'accepted';
        $request->save();

        return response()->json(['message' => 'Request accepted for access']);
    }
}
