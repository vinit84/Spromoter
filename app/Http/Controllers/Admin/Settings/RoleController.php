<?php

namespace App\Http\Controllers\Admin\Settings;

use App\DataTables\Admin\Settings\Role\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Roles\StoreRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        $roles = Role::with([
            'users' => function ($query) {
                $query->select('id', 'first_name', 'profile_photo_url')->limit(5);
            }
        ])
            ->withCount('users')
            ->get();

        return $dataTable->render('admin.settings.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        // Get all permissions and group them by prefix
        $permissions = Permission::all();
        $permissionsGrouped = $permissions->groupBy(function ($item) {
            $group = explode('-', $item->name);
            $count = count($group);

            if ($count > 1) {
                // Combine all except the last element in the group array
                return implode('-', array_slice($group, 0, $count - 1));
            } else {
                // If there's only one element, return it as it is
                return $item->name;
            }
        });

        return view('admin.settings.roles.create', [
            'permissionsGrouped' => $permissionsGrouped,
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->validated('name')]);

            if ($request->validated('permissions')) {
                $role->permissions()->detach();
                $role->permissions()->sync($request->validated('permissions'));
            }

            DB::commit();

            return success(trans('Role Created Successfully'), route('admin.settings.roles.index'));
        } catch (Throwable $exception) {
            DB::rollBack();
            return error(trans('Something went wrong'));
        }
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0){
            return error(trans('This role has users, you can\'t delete it'));
        }

        $role->delete();

        return success(trans('Role Deleted Successfully'), route('admin.settings.roles.index'));
    }
}
