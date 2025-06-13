<?php

use App\Http\Controllers\AdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefugeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SurveyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/refugees', [RefugeeController::class, 'store']);
Route::post('/companies', [CompanyController::class, 'store']);
Route::post('/login1', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login2', [AuthController::class, 'userLogin']);
// تقديم المهجر على خدمة
Route::post('/services/{id}/apply', [ServiceController::class, 'applyToService']);


Route::middleware(['auth:sanctum', 'is_company'])->group(function () {
    Route::post('/ads', [AdController::class, 'store']); // فقط الشركات
});

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('/ads/pending', [AdController::class, 'pendingAds']);
    Route::post('/ads/{id}/approve', [AdController::class, 'approve']);
    Route::post('/ads/{id}/reject', [AdController::class, 'reject']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::get('/surveys', [SurveyController::class, 'index']);

});
Route::post('/surveys/assign/{id}', [SurveyController::class, 'assign']); // تعيين فريق استطلاع من قبل الأدمن (حاليًا تعيين فقط بتغيير الحالة لـ pending)
Route::post('/surveys/response/{id}', [SurveyController::class, 'teamResponse']); // فريق الاستطلاع يرفع تقرير (confirmed or rejected)
Route::post('/surveys/approve/{id}', [SurveyController::class, 'approve']); // تأكيد الدخول للمنصة بعد قبول الفريق
