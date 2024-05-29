@extends('layouts.admin.app', [
    'title' => trans('Stores'),
    'actions' => [
        [
            'text' => trans('Add Store'),
            'icon' => 'ti ti-plus',
            'link' => route('admin.stores.create'),
        ]
    ]
])

@section('content')
    <x-data-table :data-table="$dataTable">
        <x-slot name="header">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <select
                            name="filter_category"
                            id="filter_category"
                            data-control="select2"
                            data-placeholder="{{ trans('Filter Category') }}"
                            data-allow-clear="true"
                            aria-label="{{ trans('Filter Category') }}"
                        >
                            <option/>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }} ({{ $category->stores_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select
                            name="filter_deleted"
                            id="filter_deleted"
                            data-control="select2"
                            data-placeholder="{{ trans('Filter Deleted') }}"
                            data-allow-clear="true"
                            aria-label="{{ trans('Filter Deleted') }}"
                        >
                            <option/>
                            <option value="only_trashed">{{ trans('Only Trashed') }}</option>
                            <option value="with_trashed">{{ trans('With Trashed') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-data-table>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/spinkit/spinkit.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/block-ui/block-ui.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        $(function () {
            $('.store-preview').each(function () {
                $(this).block({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0',
                    },
                    overlayCSS: {
                        opacity: 0.5
                    }
                });
            });
        });
    </script>
@endpush
