<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\Security\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('admin.profile.security', [
            'user' => $user,
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        Auth::user()->update([
            'password' => bcrypt($request->validated('password')),
        ]);

        // TODO:: Send email to user about password change

        return success(trans('Password Changed Successfully'));
    }
}
