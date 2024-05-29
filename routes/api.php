<?php

use App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('register', [V1\RegisterController::class, 'register'])->name('register');
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('check-credentials', [V1\ProfileController::class, 'checkCredentials'])
            ->name('check-credentials');

        Route::apiResource('stores', V1\StoreController::class)->only(['index', 'store', 'show', 'update']);

        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::post('/', [V1\OrderController::class, 'store'])->name('store');
            Route::post('/bulk', [V1\OrderController::class, 'bulkStore'])->name('store.bulk');
        });
    });

    Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
        Route::post('/', [V1\ReviewController::class, 'index'])->name('index');
        Route::post('/create', [V1\ReviewController::class, 'store'])->name('store');
        Route::get('/uploads/{media:uuid}/{fileName?}', [V1\ReviewController::class, 'media'])->name('media');

        Route::post('/{email:uuid}/{product:uuid}/create-from-email', [V1\ReviewController::class, 'createFromEmail'])
            ->name('create-from-email');
    });

    Route::group(['prefix' => 'ratings', 'as' => 'ratings.'], function () {
        Route::post('/', [V1\RatingController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'update', 'as' => 'update.'], function (){
        Route::group(['prefix' => 'wordpress', 'as' => 'wordpress.'], function (){
            Route::get('latest-version-info', [V1\Update\WordpressController::class, 'latestVersionInfo'])->name('latest-version-info');
            Route::get('latest-version-download', [V1\Update\WordpressController::class, 'latestVersionDownload'])->name('latest-version-download');
        });
    });
});




Route::get('translations/{locale}', function ($locale) {
    $path = lang_path($locale . '.json');
    if (!file_exists($path)) {
        abort(404);
    }

    $json = file_get_contents($path);

    return response()->json(json_decode($json, true));
})->name('translations');
