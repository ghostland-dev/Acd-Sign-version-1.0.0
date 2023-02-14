<?php

use App\Http\Controllers\TemplateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::put('/confirmSignature/requests', [RequestController::class, 'confirmSignature'])->name('request.confirmSignature');

//Les routes utilisables uniquement si l'utilisateur est connecté, sont placé ici
Route::group(['middleware' => ['jwt.verify']], function() {
    //Les routes utilisables uniquement si l'utilisateur est admin, sont placé ici
    Route::group(['middleware' => ['role:admin']], function() {
        Route::post('/register', [AuthController::class, 'register'])->name('user.register');
        Route::apiResource('templates',TemplateController::class);
        Route::apiResource('users',UserController::class);
        Route::apiResource('requests',RequestController::class);
    });

    Route::get('/requests/{request}', [RequestController::class, 'show']);
    Route::get('/mine/requests', [RequestController::class, 'showMine']);
    Route::post('/sendEmail/requests/{request}', [RequestController::class, 'sendEmail']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::get('/templates/{template}', [TemplateController::class, 'show']);
});
