<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {

    }

    public function portal()
    {
        return auth()->user()->redirectToBillingPortal(route('user.profile.billing.index'));
    }
}
