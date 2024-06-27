<?php
use App\Http\Controllers\charity\CharityController;

Route::group(['prefix' => 'charity'], function () {
    Route::get('profile', [CharityController::class, 'Profile'])->name('charity.profile');
    Route::post('update-profile', [CharityController::class, 'updateProfile'])->name('charity.updateProfile');

    Route::post('add-fundraiser', [CharityController::class, 'add_fundraiser'])->name('charity.addFundraiser');

    Route::get('fundraisers', [CharityController::class, 'fundraisers'])->name('charity.fundraisers');
    Route::post('update-fundraiser/{id}', [CharityController::class, 'update_fundraiser'])->name('charity.updateFundraiser');
    Route::delete('delete-fundraiser/{id}', [CharityController::class, 'deleteFundraiser'])->name('charity.deleteFundraiser');
    Route::get('transactions', [CharityController::class, 'transactions'])->name('charity.transactions');
});
