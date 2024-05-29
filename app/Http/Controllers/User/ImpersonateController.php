<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ImpersonateController extends Controller
{
    public function leave()
    {
        if (! Cache::has('impersonate')) {
            flash(trans('You are not impersonating a user.'), 'warning');

            return redirect()->route('user.dashboard.index');
        }

        Auth::loginUsingId(Cache::get('impersonate')['id']);

        Cache::forget('impersonate');

        flash(trans('You are back to your account.'), 'success');

        return to_route('admin.dashboard.index');
    }
}
