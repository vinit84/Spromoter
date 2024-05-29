<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Frontend;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ScriptTagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'common', 'as' => 'common.'], function (){
    Route::get('change-language/{code}', [CommonController::class, 'changeLanguage'])->name('change-language');

    Route::get('uploads/avatar/{user}/{media:uuid}/{file_name}', [CommonController::class, 'avatar'])
        ->name('avatar')
        ->scopeBindings();

    Route::get('uploads/store/{store:uuid}/{media:uuid}/{file_name}', [CommonController::class, 'storePreviewImage'])
        ->name('store-preview-image')
        ->scopeBindings();
});

Route::group(['domain' => config('app.auth_domain')], function (){
    Route::get('/', function (){
        return to_route('login');
    });
});

Route::get('/', [Frontend\HomeController::class, 'index'])->name('home.index');
Route::get('/review-request/{store/email/product}', [CommonController::class, 'reviewRequest'])->name('review-request');
Route::get('/{page:slug}', [Frontend\HomeController::class, 'page'])->name('home.page');


Route::middleware(['auth.shopify'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


Route::post('/webhook/orders-create', [WebhookController::class, 'ordersCreate']);
Route::get('/scripttag/js-method-response', [ScriptTagController::class, 'jsMethodResponse']);