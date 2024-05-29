@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.roles.store') }}" method="POST" class="row g-3 ajaxform">
                @csrf
                <div class="col-12 mb-4">
                    <label class="form-label" for="name">{{ trans('Role Name') }}</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        placeholder="{{ trans('Enter a role name') }}"
                        tabindex="-1"/>
                </div>
                <div class="col-12">
                    <h5>{{ trans('Role Permissions') }}</h5>
                    <!-- Permission table -->
                    <div class="table-responsive">
                        <table class="table table-flush-spacing">
                            <tbody>
                            <tr>
                                <td class="text-nowrap fw-medium">
                                    {{ trans('Administrator Access') }}
                                    <i
                                        class="ti ti-info-circle"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="{{ trans('Allows a full access to the system') }}"></i>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input check-all" type="checkbox"
                                               id="selectAllPermissions" data-group="permissions"/>
                                        <label class="form-check-label" for="selectAllPermissions">
                                            {{ trans('Select All') }}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @foreach($permissionsGrouped as $title => $permissionsGroup)
                                <tr>
                                    <td class="text-nowrap fw-medium">
                                        {{ trans((string)str($title)->title()->replace('-', ' ')) }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @foreach($permissionsGroup as $permission)
                                                <div class="form-check me-3 me-lg-5">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="permissions[]" value="{{ $permission->id }}"
                                                           id="{{ str($permission->name)->slug() }}"
                                                           data-group-for="permissions"/>
                                                    <label class="form-check-label" for="{{ str($permission->name)->slug() }}">
                                                        {{ str($permission->name)->remove([$title])->replace('-', ' ')->title() }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Permission table -->
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ trans('Submit') }}</button>
                    <button
                        type="reset"
                        class="btn btn-label-secondary"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                        {{ trans('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
