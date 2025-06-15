<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function __construct()
    {
        // فقط الأدمن يستطيع تنفيذ هذه العمليات
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->only(['store', 'update', 'destroy']);
    }

    // عرض كل الخدمات
    public function index()
    {
        return response()->json(Service::all());
    }

    // إنشاء خدمة (أدمن فقط)
    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        $service = Service::create([
            'service_type' => $request->service_type,
        ]);

        return response()->json(['message' => 'Service created', 'service' => $service], 201);
    }

    // عرض خدمة واحدة (مع إعلاناتها إن وجدت)
    public function show($id)
    {
        $service = Service::with('ads')->findOrFail($id);

        $serviceData = $service->toArray();

        $serviceData['ads'] = $service->ads->map(function ($ad) use ($service) {
            $adArray = $ad->toArray();

            // إذا كانت الخدمة ليست "دعم نفسي" نخفي الرابط
            if (strtolower($service->service_type) !== 'دعم نفسي') {
                unset($adArray['meet_link']);
            }

            return $adArray;
        });

        return response()->json($serviceData);
    }


    // تعديل خدمة (أدمن فقط)
    public function update(Request $request, $id)
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'service_type' => $request->service_type,
        ]);

        return response()->json(['message' => 'Service updated', 'service' => $service]);
    }

    // حذف خدمة (أدمن فقط)
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service deleted']);
    }

    // المهجر يقدم على خدمة
    public function applyToService($serviceId)
    {
        $user = Auth::user();

        if ($user->type !== 'refugee') {
            return response()->json(['message' => 'Only refugees can apply for services.'], 403);
        }

        $service = Service::findOrFail($serviceId);

        // جلب أول إعلان مرتبط بالخدمة (معتمد approved)
        $ad = $service->ads()->where('status', 'approved')->first();

        if (!$ad) {
            return response()->json(['message' => 'No available approved ads for this service.'], 404);
        }

        $response = [
            'message' => 'You have successfully applied for the service.',
            'service_type' => $service->service_type,
            'ad_title' => $ad->title,
            'ad_location' => $ad->location,
            'ad_start' => $ad->start_date,
            'ad_end' => $ad->end_date,
        ];

        if ($service->service_type === 'دعم نفسي' && $ad->meet_link) {
            $response['meet_link'] = $ad->meet_link;
        }

        return response()->json($response);
    }
}
