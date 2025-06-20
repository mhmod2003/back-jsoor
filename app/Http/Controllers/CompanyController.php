<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\RequestT;
use App\Models\Notification;
class CompanyController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string',
        'social_link' => 'nullable|string',
        'description' => 'nullable|string',
        'phone' => 'required|string',
        'map_location' => 'nullable|string',
    ]);

    // إضافة الحالة يدوياً
    $validated['status'] = 'pending';

    // إنشاء الشركة
    $company = Company::create($validated);

    // إنشاء طلب انضمام للادمن
    RequestT::create([
        'user_id' => $request->user_id,
        'ads_id' => null,
        'not_id' => null,
        'status' => 'pending',
    ]);

    // إرسال إشعار إلى الأدمن
    Notification::create([
        'user_id' => 1,
        'title' => 'طلب شركة جديد',
        'description' => 'تم تسجيل شركة جديدة باسم: ' . $company->name . '. الرجاء مراجعة الطلب.',
    ]);

    return response()->json(['message' => 'Company created and join request sent'], 201);
}

    // موافقة الأدمن على شركة
    public function approve($id)
    {
        $company = Company::findOrFail($id);
        $company->status = 'active';
        $company->save();

        // إرسال إشعار لصاحب الشركة
        Notification::create([
            'user_id' => $company->user_id,
            'title' => 'تم تفعيل شركتك',
            'description' => 'تمت الموافقة على شركتك "' . $company->name . '" من قبل الإدارة، يمكنك الآن استخدام المنصة.'
        ]);

        return response()->json(['message' => 'Company approved and user notified.']);
    }

    // تعديل شركة
public function update(Request $request, $id)
{
    $company = Company::findOrFail($id);

    $validated = $request->validate([
        'name' => 'sometimes|string',
        'social_link' => 'nullable|string',
        'description' => 'nullable|string',
        'phone' => 'sometimes|string',
        'map_location' => 'nullable|string',
        'status' => 'in:pending,active,rejected' // اختيارية حسب الحالة
    ]);

    $company->update($validated);

    return response()->json(['message' => 'Company updated successfully.']);
}

// حذف شركة
public function destroy($id)
{
    $company = Company::findOrFail($id);
    $company->delete();

    return response()->json(['message' => 'Company deleted successfully.']);
}
public function index (){
return Company::all();

}
}

