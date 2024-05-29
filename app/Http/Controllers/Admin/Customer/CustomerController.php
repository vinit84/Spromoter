<?php

namespace App\Http\Controllers\Admin\Customer;

use App\DataTables\Admin\Customer\StoreDataTable;
use App\DataTables\Admin\CustomerDataTable;
use App\Helpers\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customers\StoreCustomerRequest;
use App\Http\Requests\Admin\Customers\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customer-create')->only(['create', 'store']);
        $this->middleware('permission:customer-read')->only(['index', 'show']);
        $this->middleware('permission:customer-update')->only(['edit', 'update', 'restore']);
        $this->middleware('permission:customer-delete')->only('destroy');
    }

    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('admin.customers.index');
    }

    public function create()
    {
        $countries = Country::get();

        return view('admin.customers.create', [
            'countries' => $countries,
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        DB::beginTransaction();
        try {
            $customer = User::create($request->validated() + [
                    'group' => 'customer',
                    'password' => bcrypt($request->validated('password')),
                ]);

            $customer->sendEmailVerificationNotification();

            DB::commit();
            return success(trans('Customer Created Successfully'), route('admin.customers.index'));
        }catch (\Throwable $exception){
            DB::rollBack();

            return error($exception->getMessage());
        }
    }

    public function show(StoreDataTable $dataTable, User $customer)
    {
        abort_if($customer->group !== 'customer', 404);

        return $dataTable->render('admin.customers.show', [
            'customer' => $customer,
        ]);
    }

    public function edit(User $customer)
    {
        abort_if($customer->group !== 'customer', 404);

        $countries = Country::get();
        return view('admin.customers.edit', [
            'customer' => $customer,
            'countries' => $countries,
        ]);
    }

    public function update(UpdateCustomerRequest $request, User $customer)
    {
        abort_if($customer->group !== 'customer', 404);

        DB::beginTransaction();
        try {
            $customer->update($request->validated());

            if ($customer->wasChanged('email')) {
                $customer->update([
                    'email_verified_at' => null,
                ]);

                $customer->sendEmailVerificationNotification();
            }

            activity('admin')
                ->performedOn($customer)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $customer->toArray(), 'old' => $customer->getOriginal()])
                ->log('Profile Updated');

            DB::commit();

            return success(trans('Customer Updated Successfully'), route('admin.customers.index'));
        }catch (\Throwable $exception){
            DB::rollBack();

            return error($exception->getMessage());
        }
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return success(trans('Customer Deleted Successfully'));
    }

    public function forceDestroy(User $customer)
    {
        $customer->forceDelete();

        return success(trans('Customer Permanently Deleted Successfully'));
    }

    public function restore(User $customer)
    {
        if ($customer->trashed()) {
            $customer->restore();

            return success(trans('Customer Restored Successfully'));
        }

        return error(trans('Customer Not Found'));
    }

    public function suspend(User $customer)
    {
        $customer->update([
            'status' => 'suspend',
        ]);

        return success(trans('Customer Suspended Successfully'));
    }

    public function active(User $customer)
    {
        $customer->update([
            'status' => 'active',
        ]);

        return success(trans('Customer Suspended Successfully'));
    }

    public function verify(User $customer)
    {
        $customer->update([
            'email_verified_at' => now(),
        ]);

        return success(trans('Customer Email Verified Successfully'));
    }

    public function loginAs(User $customer)
    {
        Cache::forever('impersonate', [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
        ]);

        Auth::login($customer);

        flash(trans('You are now logged in as :name', ['name' => '<strong>' . $customer->name . '</strong>']));

        return to_route('user.dashboard.index');
    }
}
