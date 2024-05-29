@extends('layouts.user.app', [
    'title' => trans('Emails Analytics'),
])

@section('actions')
    <div class="form-group">
        <input type="text" id="filter_date_range" class="form-control" aria-label/>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h5>{{ trans('Emails Sent') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h5>{{ trans('Emails Opened') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h5>{{ trans('Emails Converted') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
