<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::active()->get();

        return view('user.plans.index', [
            'plans' => $plans
        ]);
    }
}
