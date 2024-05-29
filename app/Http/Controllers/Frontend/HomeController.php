<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return to_route('login');
        return view('frontend.home.index');
    }

    public function page(Page $page)
    {
        return view('frontend.page', [
            'page' => $page
        ]);
    }
}
