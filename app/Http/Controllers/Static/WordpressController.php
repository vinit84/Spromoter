<?php

namespace App\Http\Controllers\Static;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class WordpressController extends Controller
{
    public function widgetJs(Store $store)
    {
        return response()
            ->file(resource_path('views/static/wordpress/widget.js'), [
                'Content-Type' => 'text/javascript',
            ]);
    }
}
