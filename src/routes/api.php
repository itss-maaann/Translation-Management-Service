<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LocaleController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\TranslationController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('locales', LocaleController::class);
    Route::apiResource('tags',    TagController::class);

    Route::apiResource('translations', TranslationController::class)->where(['translation' => '[0-9]+']);
    Route::get('translations/search', [TranslationController::class, 'search']);
    Route::get('translations/export', [TranslationController::class, 'export']);
});
