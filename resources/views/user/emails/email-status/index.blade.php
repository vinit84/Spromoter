@extends('layouts.user.app', [
    'title' => trans('Email Status'),
])

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md mb-md-0 mb-2">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="all" checked />
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('All') }}</span>
                                <span class="text-muted" id="allCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="scheduled"/>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('Scheduled') }}</span>
                                <span class="text-muted" id="scheduledCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="sent"/>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('Sent') }}</span>
                                <span class="text-muted" id="sentCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="opened"/>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('Opened') }}</span>
                                <span class="text-muted" id="openedCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="reviewed"/>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('Reviewed') }}</span>
                                <span class="text-muted" id="reviewedCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="not_sent"/>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('Not Sent') }}</span>
                                <span class="text-muted" id="notSentCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content">
                            <input class="form-check-input" type="radio" name="filter_status" value="limit_exceeded"/>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">{{ trans('Limit Exceeded') }}</span>
                                <span class="text-muted" id="limitExceededCount">0</span>
                              </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-data-table :$dataTable />
@endsection
