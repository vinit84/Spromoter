<?php

use App\Http\Controllers\EmailController;

Route::group(['as' => 'email.'], function () {
    Route::get('confirm-review-request/{review:uuid}', [EmailController::class, 'confirmReviewRequest'])
        ->name('confirm-review-request');
});
