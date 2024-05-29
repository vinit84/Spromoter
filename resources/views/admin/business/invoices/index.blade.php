@extends('layouts.admin.app', [
    'title' => trans('Invoices'),
])

@section('content')
    <x-data-table :data-table="$dataTable" />
@endsection
