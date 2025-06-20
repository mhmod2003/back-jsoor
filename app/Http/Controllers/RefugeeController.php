<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refugee;
use App\Models\Notification;

class RefugeeController extends Controller
{
    public function index(){
        return  Refugee::all();
     }
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
 
     // موافقة الأدمن على المهجّر
 public function approve($id)
 {
     $refugee = Refugee::findOrFail($id);
 
     $refugee->status = 'active';  
     $refugee->save();
 
     // إرسال إشعار لصاحب الحساب (المستخدم المرتبط بالمهجّر)
     Notification::create([
         'user_id' => $refugee->user_id,
         'title' => 'تمت الموافقة على طلبك',
         'description' => 'تهانينا! تمت الموافقة على تسجيلك كمهجّر في النظام، يمكنك الآن الاستفادة من الخدمات المتاحة.'
     ]);
 
     return response()->json(['message' => 'تمت الموافقة على المهجّر وتم إرسال إشعار']);
 }
 
 }
 
 