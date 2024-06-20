<?php

use App\Http\Controllers\user\donateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('profile', [AuthController::class, 'userInfo'])->name('user.profile');
    Route::post('profile', [AuthController::class, 'CreateOrUpdateProfile'])->name('user.updateProfile');
    Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::post('update-password', [AuthController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('img/{image}', [AuthController::class, 'getImage'])->name('image.get');
    Route::get('token', [AuthController::class, 'refreshAccessToken'])->name('token.refresh');
    Route::post('request-charity', [AuthController::class, 'request_charity'])->name('user.requestCharity');
    Route::post('recommendation', [AuthController::class, 'recommendation'])->name('user.recommendation');
    Route::post('donate', [donateController::class, 'stripePost'])->name('donate');
    Route::get('transactions', [donateController::class, 'transactions'])->name('transactions');


    Route::get('charities', [AuthController::class, 'charities'])->name('charities');
    Route::get('charities/{id}', [AuthController::class, 'charity'])->name('charity');
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::fallback(function () {
    return res_data('Nothing Found!!', 'Invalid Request', 404);
});
