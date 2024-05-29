@extends('layouts.admin.app')

@section('content')
    <h4 class="mb-4">{{ trans('Roles List') }}</h4>

    <div class="row g-4">
        @foreach($roles as $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-normal mb-2">{{ trans('Total :count users', ['count' => $role->users_count]) }}</h6>
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                @foreach($role->users as $user)
                                    <li
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="{{ $user->first_name }}"
                                        class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="{{ $user->avatar }}"
                                             alt="{{ $user->first_name }}"/>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-1">
                            <div class="role-heading">
                                <h4 class="mb-1">{{ $role->name }}</h4>
                                <a href="">
                                    <span>{{ trans('Edit Role') }}</span>
                                </a
                                >
                            </div>
                            <a href="{{ route('admin.settings.roles.destroy', $role) }}" class="text-danger confirm-delete">
                                <i class="ti ti-trash ti-md"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-5">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                            <img
                                src="{{ asset('assets/img/illustrations/add-new-roles.png') }}"
                                class="img-fluid mt-sm-4 mt-md-0"
                                alt="add-new-roles"
                                width="83"/>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <a class="btn btn-primary mb-2 text-nowrap" href="{{ route('admin.settings.roles.create') }}">
                                {{ trans('Add New Role') }}
                            </a>
                            <p class="mb-0 mt-1">{{ trans('Add a role if it does not exist') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <x-data-table :$dataTable />
        </div>
    </div>
@endsection
