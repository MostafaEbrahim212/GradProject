<?php
use App\Http\Controllers\chairty\ChairtyController;

Route::group(['prefix' => 'charity'], function () {
    Route::get('profile', [ChairtyController::class, 'profile'])->name('charity.profile');
    Route::post('update-profile', [ChairtyController::class, 'updateProfile'])->name('charity.updateProfile');
    Route::post('add-fundraiser', [ChairtyController::class, 'addFundraiser'])->name('charity.addFundraiser');
    Route::get('fundraisers', [ChairtyController::class, 'fundraisers'])->name('charity.fundraisers');
    Route::post('update-fundraiser/{id}', [ChairtyController::class, 'updateFundraiser'])->name('charity.updateFundraiser');
    Route::delete('delete-fundraiser/{id}', [ChairtyController::class, 'deleteFundraiser'])->name('charity.deleteFundraiser');
    Route::get('transactions', [ChairtyController::class, 'transactions'])->name('charity.transactions');
});
