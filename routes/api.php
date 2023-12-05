<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\InteractionController;
use App\Http\Controllers\Apis\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'api', 'prefix' => 'user'], function ($router) {
    Route::post('/register', [UserController::class, 'register'])->name('user-registration');
    Route::post('/authentication', [UserController::class, 'authentication'])->name('user-authentication');
    Route::post('/logout', [UserController::class, 'logout'])->name('user-logout');
});

Route::group(['middleware' => 'api', 'prefix' => 'interaction'], function ($router) {
    Route::post('/create', [InteractionController::class, 'create'])->name('interaction-creation');
    Route::post('/retrieve', [InteractionController::class, 'retrieve'])->name('interaction-retrieve');
    Route::post('/update', [InteractionController::class, 'update'])->name('interaction-update');
    Route::post('/delete', [InteractionController::class, 'delete'])->name('interaction-delete');
    Route::post('/statistics', [InteractionController::class, 'statistics'])->name('interaction-statistics');
});
