<?php
use App\Http\Controllers\chairty\ChairtyController;



Route::group(['prefix' => 'chairty'], function () {
    Route::get('profile', [ChairtyController::class, 'profile']);
});