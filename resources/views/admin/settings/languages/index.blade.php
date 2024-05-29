@extends('layouts.admin.app', [
    'title' => trans('Languages'),
    'actions' => [
        [
            'text' => trans('Create Language'),
            'link' => route('admin.settings.languages.create'),
            'icon' => 'ti-plus',
            'can' => 'language-create',
        ]
    ],
])

@section('content')
    <x-data-table :data-table="$dataTable"/>
@endsection
