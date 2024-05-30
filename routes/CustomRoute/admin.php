<?php
use App\Http\Controllers\admin\AdminController;



Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'login']);
    Route::get('info', [AdminController::class, 'info']);
    Route::post('logout', [AdminController::class, 'logout']);
    Route::get('users', [AdminController::class, 'users']);
    Route::get('user/{id}', [AdminController::class, 'user']);
    Route::post('toggle_active/{id}', [AdminController::class, 'toggle_active']);
    Route::get('requests', [AdminController::class, 'requests']);
    Route::get('request/{id}', [AdminController::class, 'request']);
    Route::post('accept_request/{id}', [AdminController::class, 'accept_request']);
    Route::post('reject_request/{id}', [AdminController::class, 'reject_request']);
    Route::get('chairites', [AdminController::class, 'chairites']);
    Route::get('chairty/{id}', [AdminController::class, 'chairty']);
    Route::post('add_fundraiser_category', [AdminController::class, 'add_fundraiser_category']);
    Route::post('update_fundraiser_category/{id}', [AdminController::class, 'update_fundraiser_category']);
    Route::get('fundraisers_categories', [AdminController::class, 'fundraisers_categories']);
    Route::get('fundraisers', [AdminController::class, 'fundraisers']);
});
