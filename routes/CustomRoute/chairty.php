<?php
use App\Http\Controllers\chairty\ChairtyController;

Route::group(['prefix' => 'charity'], function () {
    Route::get('profile', [ChairtyController::class, 'Profile'])->name('charity.profile');
    Route::post('update-profile', [ChairtyController::class, 'updateProfile'])->name('charity.updateProfile');

    Route::post('add-fundraiser', [ChairtyController::class, 'add_fundraiser'])->name('charity.addFundraiser');

    Route::get('fundraisers', [ChairtyController::class, 'fundraisers'])->name('charity.fundraisers');
    Route::post('update-fundraiser/{id}', [ChairtyController::class, 'update_fundraiser'])->name('charity.updateFundraiser');
    Route::delete('delete-fundraiser/{id}', [ChairtyController::class, 'deleteFundraiser'])->name('charity.deleteFundraiser');
    Route::get('transactions', [ChairtyController::class, 'transactions'])->name('charity.transactions');
});
