<?php

use App\Http\Controllers\Api\ActionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SearchController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::post('reset-password', [AuthController::class, 'reset'])->middleware('guest')->name('password.update');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('action', ActionController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('client', ClientController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('category', CategoryController::class)->only(['index']);
    Route::apiResource('course', CourseController::class)->only(['index']);
    Route::apiResource('type', TypeController::class)->only(['index']);
    Route::apiResource('user', UserController::class)->only(['index', 'update']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('search', [SearchController::class, 'search']);
});
