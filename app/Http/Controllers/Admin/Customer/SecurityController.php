<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\Security\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct()
    {

    }

    public function index(User $customer)
    {
        return view('admin.customers.security.index', [
            'customer' => $customer
        ]);
    }

    public function changePassword(UpdatePasswordRequest $request, User $customer)
    {
        $customer->update([
            'password' => bcrypt($request->password)
        ]);

        return success(trans('Password Updated Successfully'), route('admin.customers.security.index', $customer));
    }
}

