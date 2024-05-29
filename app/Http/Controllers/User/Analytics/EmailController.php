<?php

namespace App\Http\Controllers\User\Analytics;

use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function index()
    {
        return view('user.analytics.emails.index');
    }
}
