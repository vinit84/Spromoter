@extends('layouts.admin.app', [
    'title' => trans('Plans'),
    'actions' => [
        [
            'text' => trans('Add Plan'),
            'icon' => 'ti ti-plus',
            'link' => route('admin.business.plans.create'),
        ]
    ]
])

@section('content')
    <x-data-table :data-table="$dataTable" />
@endsection
