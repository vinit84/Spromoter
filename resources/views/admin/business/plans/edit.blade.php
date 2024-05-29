@extends('layouts.admin.app', [
    'title' => trans('Plans'),
    'back' => [
        'can'=> 'plan-read',
        'url' => route('admin.business.plans.index')
    ]
])

@section('content')
    <form action="{{ route('admin.business.plans.update', $plan) }}" method="POST" class="ajaxform">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h5>{{ trans('Plan Information') }}</h5>
                        </div>
                        <div class="mb-3">
                            <label for="title">{{ trans('Title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $plan->title }}" placeholder="{{ trans('Enter plan name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug">{{ trans('Slug') }}</label>
                            <input type="text" id="slug" class="form-control" value="{{ $plan->slug }}" placeholder="{{ trans('Enter plan slug') }}" required disabled>
                        </div>
                        <div class="mb-3">
                            <label for="monthly_price">{{ trans('Monthly Price') }}</label>
                            <input type="number" id="monthly_price" class="form-control" value="{{ $plan->monthly_price }}" placeholder="{{ trans('Enter monthly price') }}" disabled required>
                        </div>
                        <div class="mb-3">
                            <label for="yearly_price">{{ trans('Yearly Price') }}</label>
                            <input type="number" id="yearly_price" class="form-control" value="{{ $plan->yearly_price }}" placeholder="{{ trans('Enter yearly price') }}" disabled required>
                        </div>
                        <div class="mb-3">
                            <label for="description">{{ trans('Description') }}</label>
                            <textarea name="description" id="description" class="form-control" placeholder="{{ trans('Enter plan description') }}">{{ $plan->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="is_active">{{ trans('Is Active') }}</label>
                            <select name="is_active" id="is_active" class="form-select" data-control="select2">
                                <option value="1" @selected($plan->is_active)>{{ trans("Yes") }}</option>
                                <option value="0" @selected(!$plan->is_active)>{{ trans("No") }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card overflow-hidden" style="height: 565px">
                    <div class="card-body" id="feature-list">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('Feature') }}</th>
                                <th class="text-end">{{ trans('Show in card') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($featureGroups as $featureGroup)
                                <tr>
                                    <th colspan="3">{{ $featureGroup['title'] }}</th>
                                </tr>
                                @foreach($featureGroup['features'] as $feature)
                                    <tr>
                                        <td>
                                            @if($feature['type'] == 'number')
                                                <div>
                                                    <label for="{{ $feature['slug'] }}">{{ trans($feature['title']) }}</label>
                                                    <input
                                                        type="number"
                                                        name="features[{{ $feature['slug'] }}]"
                                                        id="{{ $feature['slug'] }}"
                                                        placeholder="{{ trans('Enter :feature', ['feature' => str($feature['title'])->lower()->value()]) }}"
                                                        value="{{ $plan->features[$feature['slug']] ?? $feature['default'] }}"
                                                        class="form-control"
                                                        required
                                                    >
                                                </div>
                                            @elseif($feature['type'] == 'boolean')
                                                <div @class(['form-check form-check-primary'])>
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        value="1"
                                                        name="features[{{ $feature['slug'] }}]"
                                                        id="{{ $feature['slug'] }}"
                                                        @checked($plan->features[$feature['slug']] ?? $feature['default'])
                                                    >
                                                    <label class="form-check-label" for="{{ $feature['slug'] }}">{{ $feature['title'] }}</label>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="1"
                                                name="card_features[{{ $feature['slug'] }}]"
                                                id="{{ $feature['slug'] }}"
                                                @checked($plan->card_features[$feature['slug']] ?? false)
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
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
                                                value="{{ $plan->features[$feature['slug']] ?? $feature['default'] }}"
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
                                                @checked($plan->features[$feature['slug']] ?? $feature['default'])
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
                            {{ trans('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

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
    </script>
@endpush
