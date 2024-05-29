@extends('layouts.admin.app', [
    'title' => trans('General Settings'),
])

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h5>{{ trans('Reviews Settings') }}</h5>
                    </div>

                    <form action="">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="reviews.auto_publish_after_days">
                                {{ trans('Auto Publish After') }}
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="reviews.auto_publish_after_days"
                                       id="reviews.auto_publish_after_days"
                                       class="form-control"
                                       value="{{ setting('reviews.auto_publish_after_days', 0)  }}"
                                       min="0"
                                       step="1"
                                       oninput="this.value = Math.abs(this.value)"
                                >
                                <span class="input-group-text">{{ trans('days') }}</span>
                            </div>
                            <div class="form-text">
                                {{ trans('Set 0 to immediately publish reviews') }}
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i>
                            {{ trans('Update') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
