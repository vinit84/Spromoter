@extends('layouts.admin.app', [
    'title' => trans('Users'),
    'actions' => [
        [
            'text' => trans('Add User'),
            'icon' => 'ti ti-user-plus',
            'link' => route('admin.settings.users.create'),
            'can' => 'user-create'
        ]
    ]
])

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <x-data-table :$dataTable>
                <x-slot:header>
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-3">{{ trans('Search Filter') }}</h5>
                        <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                            <div class="col-md-6 user_role">
                                <select id="filterRole" class="form-select" data-control="select2"
                                        data-allow-clear="true" data-placeholder="{{ trans('Select Role') }}"
                                        aria-label="{{ trans('Select Role') }}">
                                    <option value=""></option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 user_status">
                                <select id="filterStatus" class="form-select" data-control="select2"
                                        data-allow-clear="true" data-placeholder="{{ trans('Select Status') }}"
                                        aria-label="{{ trans('Select Status') }}">
                                    <option value=""></option>
                                    <option value="active">{{ trans('Active') }}</option>
                                    <option value="suspend">{{ trans('Suspend') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </x-slot:header>
            </x-data-table>
        </div>
    </div>
@endsection
