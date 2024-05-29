@extends('layouts.admin.app', [
    'title' => trans('Plans'),
    'back' => [
        'can'=> 'plan-read',
        'url' => route('admin.business.plans.index')
    ]
])

@section('content')
    <form action="{{ route('admin.business.plans.store') }}" method="POST" class="ajaxform">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h5>{{ trans('Plan Information') }}</h5>
                        </div>
                        <div class="mb-3">
                            <label for="title">{{ trans('Title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="{{ trans('Enter plan name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug">{{ trans('Slug') }}</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="{{ trans('Enter plan slug') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="monthly_price">{{ trans('Monthly Price') }}</label>
                                <input type="number" name="monthly_price" id="monthly_price" class="form-control" placeholder="{{ trans('Enter monthly price') }}" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="yearly_price">{{ trans('Yearly Price') }}</label>
                                <input type="number" name="yearly_price" id="yearly_price" class="form-control" placeholder="{{ trans('Enter yearly price') }}" min="0" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="monthly_order">{{ trans('Monthly Order Limit') }}</label>
                                <input type="number" name="monthly_order" id="monthly_order" class="form-control" value="50" min="0" step="50" placeholder="{{ trans('Enter monthly order limit') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="trial_days">{{ trans('Trial Days') }}</label>
                                <input type="number" name="trial_days" id="trial_days" class="form-control" value="0" min="0" placeholder="{{ trans('Enter trial days') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description">{{ trans('Description') }}</label>
                            <textarea name="description" id="description" class="form-control" placeholder="{{ trans('Enter plan description') }}"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="is_active">{{ trans('Is Active') }}</label>
                            <select name="is_active" id="is_active" class="form-select" data-control="select2">
                                <option value="1">{{ trans("Yes") }}</option>
                                <option value="0">{{ trans("No") }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card overflow-hidden" style="height: 565px">
                    <div class="card-body" id="feature-list">
                        <table>
                            <thead>
                            <tr>
                                <th>{{ trans('Enable') }}</th>
                                <th>{{ trans('Feature') }}</th>
                                <th>{{ trans('Show as primary') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        @foreach($featureGroups as $featureGroup)
                            <div class="card-title">
                                <h5>{{ $featureGroup['title'] }}</h5>
                            </div>

                            <div class="mb-4 mt-0">
                                @foreach($featureGroup['features'] as $feature)
                                    @if($feature['type'] == 'number')
                                        <div @class(['mb-3' => !$loop->last])>
                                            <label for="{{ $feature['slug'] }}">{{ trans($feature['title']) }}</label>
                                            <input
                                                type="number"
                                                name="features[{{ $feature['slug'] }}]"
                                                id="{{ $feature['slug'] }}"
                                                placeholder="{{ trans('Enter :feature', ['feature' => str($feature['title'])->lower()->value()]) }}"
                                                value="{{ $feature['default'] }}"
                                                class="form-control"
                                                required
                                            >
                                        </div>
                                    @elseif($feature['type'] == 'boolean')
                                        <div @class(['form-check form-check-primary', 'mb-3' => !$loop->last])>
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="1"
                                                name="features[{{ $feature['slug'] }}]"
                                                id="{{ $feature['slug'] }}"
                                                @checked($feature['default'])
                                            >
                                            <label class="form-check-label" for="{{ $feature['slug'] }}">{{ $feature['title'] }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i>
                            {{ trans('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/slugify/slugify.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        'use strict';

        document.addEventListener('DOMContentLoaded', function () {
            (function () {

                let featureListWrapper = document.getElementById('feature-list');
                if (featureListWrapper) {
                    new PerfectScrollbar(featureListWrapper, {
                        wheelPropagation: true,

                    });
                }
            })();
        });


        let isManualSlug = false;

        document.getElementById('title').addEventListener('keyup', function () {
            if (!isManualSlug) {
                document.getElementById('slug').value = slugify(this.value, {
                    lower: true,
                    strict: true,
                    replacement: '_',
                });
            }
        });

        document.getElementById('slug').addEventListener('keyup', function () {
            isManualSlug = true;
        });
    </script>
@endpush
