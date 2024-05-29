@extends('layouts.user.app', [
    'title' => trans('Export Reviews'),
])

@section('description')
    {{ trans('Create a request to export your reviews') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.reviews.export.store') }}" method="POST" class="ajaxform">
                    @csrf
                    <div class="mb-3">
                        <label for="type" class="form-label">{{ trans('File Type') }}</label>
                        <select name="type" id="type" class="form-select" data-control="select2" required>
                            <option value="xlsx" @selected('excel' == $type)>{{ trans('Excel') }}</option>
                            <option value="csv" @selected('csv' == $type)>{{ trans('CSV') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_range" class="form-label">{{ trans('Date Range') }}</label>
                        <input type="text" name="date_range" id="date_range" class="form-control "/>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ trans('Email') }}</label>
                        <input type="text" name="email" id="email" class="form-control " value="{{ auth()->user()->email }}"/>
                        <small>
                            {{ trans('We will email you with the download link when the export is ready.') }}
                        </small>
                    </div>

                    <button class="btn btn-primary waves-effect">
                        <i class="ti ti-file-export"></i>
                        {{ trans('Submit Request') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        $('#date_range').daterangepicker({
            startDate: '2024/01/01',
            endDate: moment(),
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'All Time': ['2024/01/01', moment()]
            },
            opens: isRtl ? 'left' : 'right',
            locale: {
                format: 'YYYY/MM/DD',
            }
        });

        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#date_range').val('');
        });
    </script>
@endpush
