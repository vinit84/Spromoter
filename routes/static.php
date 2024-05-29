<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{store:uuid}', 'domain' => config('app.static_wp_domain')], function (){
    Route::get('widget.js', [App\Http\Controllers\Static\WordpressController::class, 'widgetJs']);
});
