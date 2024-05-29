<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth.'], function (){
    Route::get('account-status', function (){
        return view('auth.account-status');
    })->name('account-status');
});


Route::get('oauth/{driver}', [App\Http\Controllers\Auth\OAuthController::class, 'redirectToProvider'])
    ->name('oauth.redirect-to-provider');

Route::get('oauth/{driver}/callback', [App\Http\Controllers\Auth\OAuthController::class, 'handleProviderCallback']);
