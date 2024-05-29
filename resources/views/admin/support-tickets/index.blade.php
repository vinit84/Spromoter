@extends('layouts.admin.app', [
    'title' => trans('Support Tickets'),

])

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                    <i class="ti ti-ticket ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">
                                        <span id="totalTickets">0</span>
                                        <small id="trashedTickets" class="text-danger">

                                        </small>
                                    </h5>
                                    <small>{{ trans('Total Tickets') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-info me-3 p-2">
                                    <i class="ti ti-ticket ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">
                                        <span id="openTickets">0</span>
                                    </h5>
                                    <small>{{ trans('Open Tickets') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                    <i class="ti ti-ticket-off ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">
                                        <span id="closedTickets">0</span>
                                    </h5>
                                    <small>{{ trans('Closed Tickets') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-success me-3 p-2">
                                    <i class="ti ti-percentage ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">
                                        <span id="resolveRate">0</span>%
                                    </h5>
                                    <small>{{ trans('Resolve Rate') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <x-data-table :$dataTable />
        </div>
    </div>
@endsection
