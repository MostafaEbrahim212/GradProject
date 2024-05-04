<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;


Route::group([], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('user', [AuthController::class, 'userInfo']);
    Route::post('profile', [AuthController::class, 'CreateOrUpdateProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
    Route::get('img/{image}', [AuthController::class, 'getImage']);
    Route::get('token', [AuthController::class, 'refreshAccessToken']);
    // request_to_be_chairty
    Route::post('request_to_be_chairty', [AuthController::class, 'request_charity']);
});




route::fallback(function () {
    return res_data('Nothing Found!!', 'Invalid Request', 404);
});
