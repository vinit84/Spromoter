@extends('admin.customers.layout', [
    'title' => trans('Customer Profile')
])

@section('userContent')
    <div class="mb-3">
        <x-data-table :dataTable="$dataTable" :title="trans('Customer Stores')"/>
    </div>

@endsection
