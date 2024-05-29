<?php

namespace App\Http\Controllers\Admin\Settings;

use App\DataTables\Admin\Settings\UserDataTable;
use App\Helpers\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Users\StoreUserRequest;
use App\Http\Requests\Admin\Settings\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-create')->only(['create', 'store']);
        $this->middleware('permission:user-read')->only(['index', 'show']);
        $this->middleware('permission:user-update')->only(['edit', 'update', 'active', 'suspend']);
        $this->middleware('permission:user-delete')->only(['destroy']);
    }

    public function index(UserDataTable $dataTable)
    {
        $roles = Role::all();

        return $dataTable->render('admin.settings.users.index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $countries = Country::get();
        $roles = Role::pluck('name');

        return view('admin.settings.users.create', [
            'countries' => $countries,
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated() + [
                    'group' => 'admin',
                    'password' => bcrypt($request->validated('password')),
                ]);

            $user->assignRole($request->validated('role'));

            DB::commit();
            return success(trans('User Created Successfully'), route('admin.settings.users.index'));
        }catch (\Throwable $exception){
            DB::rollBack();
            return error(trans('Something went wrong! Please check log file for more information'));
        }
    }

    public function show(User $user)
    {
        abort_if($user->group != 'admin', 404);

        return view('admin.settings.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        $countries = Country::get();
        $roles = Role::pluck('name');
        $assignedRole = $user->roles()->first();

        return view('admin.settings.users.edit', [
            'user' => $user,
            'countries' => $countries,
            'roles' => $roles,
            'assignedRole' => $assignedRole,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->update($request->validated());

            $user->roles()->detach();

            $user->assignRole($request->validated('role'));

            DB::commit();

            return success(trans('User Updated Successfully'), route('admin.settings.users.index'));
        }catch (\Throwable $exception){
            DB::rollBack();

            return error(trans('Something went wrong! Please check log file for more information'));
        }
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return warning(trans('You can\'t delete yourself'));
        }

        $user->delete();


        return success(trans('User Deleted Successfully'));
    }

    public function suspend(User $user)
    {
        if ($user->id == auth()->id()) {
            return error(trans('You can\'t suspend yourself'));
        }

        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            return error(trans('You can\'t suspend Super Admin'));
        }

        $user->update([
            'status' => 'suspend',
        ]);

        return success(trans('User Suspended Successfully'));
    }

    public function active(User $user)
    {
        if ($user->status == 'active'){
            return warning(trans('User Already Active'));
        }

        $user->update([
            'status' => 'active',
        ]);

        return success(trans('User Activated Successfully'));
    }
}
