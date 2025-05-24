<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefugeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginAdminController;

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

