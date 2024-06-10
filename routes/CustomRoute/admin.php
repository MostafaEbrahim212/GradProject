<?php

use App\Http\Controllers\admin\AdminController;

Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');
    Route::get('profile', [AdminController::class, 'info'])->name('admin.profile');
    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('users/{id}', [AdminController::class, 'user'])->name('admin.user');
    Route::post('users/toggle-active/{id}', [AdminController::class, 'toggleActiveUser'])->name('admin.toggleActiveUser');

    Route::get('requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::get('requests/{id}', [AdminController::class, 'request'])->name('admin.request');
    Route::post('requests/accept/{id}', [AdminController::class, 'acceptRequest'])->name('admin.acceptRequest');
    Route::post('requests/reject/{id}', [AdminController::class, 'rejectRequest'])->name('admin.rejectRequest');

    Route::get('charities', [AdminController::class, 'charities'])->name('admin.charities');
    Route::get('charities/{id}', [AdminController::class, 'charity'])->name('admin.charity');

    Route::post('fundraiser-categories', [
        AdminController::class,
        'addFundraiserCategory'
    ])->name('admin.addFundraiserCategory');

    Route::put('fundraiser-categories/{id}', [
        AdminController::class,
        'updateFundraiserCategory'
    ])->name('admin.updateFundraiserCategory');

    Route::get('fundraiser-categories', [
        AdminController::class,
        'fundraiserCategories'
    ])->name('admin.fundraiserCategories');

    Route::get('fundraisers', [AdminController::class, 'fundraisers'])->name('admin.fundraisers');
    Route::get('fundraisers/{id}', [AdminController::class, 'fundraiser'])->name('admin.fundraiser');

    Route::get('transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
});
