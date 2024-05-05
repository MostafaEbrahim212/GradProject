<?php
use App\Http\Controllers\chairty\ChairtyController;



Route::group(['prefix' => 'chairty'], function () {
    Route::get('profile', [ChairtyController::class, 'profile']);
    Route::post('update-profile', [ChairtyController::class, 'updateProfile']);
    Route::post('add-fundraiser', [ChairtyController::class, 'add_fundraiser']);
    Route::get('fundraisers', [ChairtyController::class, 'fundraisers']);
    Route::post('update-fundraiser/{id}', [ChairtyController::class, 'update_fundraiser']);
    Route::delete('delete-fundraiser/{id}', [ChairtyController::class, 'delete_fundraiser']);
});
