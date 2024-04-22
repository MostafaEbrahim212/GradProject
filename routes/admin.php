<?php
use App\Http\Controllers\admin\AdminController;



Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'login']);
    Route::get('info', [AdminController::class, 'info']);
});
