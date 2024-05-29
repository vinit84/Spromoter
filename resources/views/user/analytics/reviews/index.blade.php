@extends('layouts.user.app', [
    'title' => trans('Reviews Analytics'),
])

@section('actions')
    <div class="form-group">
        <input type="text" id="filter_date_range" class="form-control" aria-label/>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ trans('Star Distribution') }}
                    </h5>

                    @for($i = 5; $i >= 1; $i--)
                        @include('user.analytics.reviews.partials.star-distribution', ['count' => $i])
                    @endfor
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ trans('Geographical Distribution') }}
                    </h5>
                    <small>{{ trans('Top 5 countries') }}</small>

                    <ul class="list-group">
                        @forelse($geoGraphicReviews as $iso => $row)
                            <li class="list-group-item">
                                <span>
                                    <i @class(['fi', 'fi-'.$iso])></i>
                                    {{ $row['country'] }}
                                </span>
                                <span class="float-end">
                                    {{ $row['count'] }}
                                </span>
                            </li>
                        @empty
                            @for($i = 0; $i <5; $i++)
                                <li class="list-group-item">
                                    <i class="ti ti-flag"></i>
                                    {{ trans('N/A') }}
                                </li>
                            @endfor
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ trans('Devices Segmentation') }}
                    </h5>

                    <div id="donutChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-align-top nav-tabs-shadow mb-4" id="chartWrapper">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link review-analytics-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-justified-home"
                            aria-controls="navs-justified-home"
                            aria-selected="true"
                        >
                            <h4 class="fw-lighter">{{ 10 }}</h4>
                            <p class="mt-1">
                                {{ trans('Reviews') }}
                                <span class="badge bg-label-secondary ms-1">
                                    {{ trans('All time :count', ['count' => 10]) }}
                                </span>
                            </p>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link review-analytics-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-justified-profile"
                            aria-controls="navs-justified-profile"
                            aria-selected="false"
                        >
                            <h4 class="fw-lighter">{{ 10 }}</h4>
                            <p class="mt-1">
                                {{ trans('Average Star Rating') }}
                                <span class="badge bg-label-secondary ms-1">
                                    {{ trans('All time :count', ['count' => 10]) }}
                                </span>
                            </p>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link review-analytics-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-justified-messages"
                            aria-controls="navs-justified-messages"
                            aria-selected="false"
                        >
                            <h4 class="fw-lighter">{{ 10 }}</h4>
                            <p class="mt-1">
                                {{ trans('Order to Review') }}
                                <span class="badge bg-label-secondary ms-1">
                                    {{ trans('All time :count', ['count' => 10]) }}
                                </span>
                            </p>
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>

                            </div>
                            <div class="dropdown">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="false"
                                    aria-expanded="false"
                                >
                                    <i class="ti ti-stars-filled text-warning ti-xs me-sm-1 me-0"></i>
                                    {{ trans('Star Rating') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="form-check dropdown-form-check-rating">
                                        <input class="form-check-input" type="checkbox" id="ratingAll" checked>
                                        <label class="form-check-label" for="ratingAll">
                                            {{ trans('All Starts') }}
                                        </label>
                                    </div>
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="form-check dropdown-form-check-rating single-rating">
                                            <input class="form-check-input" name="filter_chart_rating" type="checkbox" value="{{ $i }}" id="rating{{ $i }}" checked>
                                            <label class="form-check-label" for="rating{{ $i }}">
                                                @for($star = 0; $star < $i; $star++)
                                                    <i class="ti ti-star-filled text-warning"></i>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div id="reviewsChart"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                        <p>
                            Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice
                            cream. Gummies halvah tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice
                            cream
                            cheesecake fruitcake.
                        </p>
                        <p class="mb-0">
                            Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah
                            cotton candy liquorice caramels.
                        </p>
                    </div>
                    <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                        <p>
                            Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies
                            cupcake gummi bears cake chocolate.
                        </p>
                        <p class="mb-0">
                            Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake.
                            Sweet
                            roll icing sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding
                            jelly
                            jelly-o tart brownie jelly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/apex-charts/apex-charts.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('assets/plugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/flag-icons.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/block-ui/block-ui.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        let storeCreatedDiffInDays = @js(\Carbon\Carbon::parse(activeStore()->created_at)->diffInDays(today()));
        let deviceChart = {
            types: @js($deviceReviewsPercentage->keys()),
            data: @js($deviceReviewsPercentage->values()),
        }
        'use strict';
        (function () {
            let cardColor, headingColor, labelColor, borderColor, legendColor;

            if (isDarkStyle) {
                cardColor = config.colors_dark.cardColor;
                headingColor = config.colors_dark.headingColor;
                labelColor = config.colors_dark.textMuted;
                legendColor = config.colors_dark.bodyColor;
                borderColor = config.colors_dark.borderColor;
            } else {
                cardColor = config.colors.cardColor;
                headingColor = config.colors.headingColor;
                labelColor = config.colors.textMuted;
                legendColor = config.colors.bodyColor;
                borderColor = config.colors.borderColor;
            }

            // Color constant
            const chartColors = {
                donut: {
                    series1: '#826bf8',
                    series2: '#3fd0bd',
                    series3: '#fee802',
                    series4: '#2b9bf4',
                    series5: '#ff4b4b',
                    series6: '#ffab2b',
                },
                area: {
                    reviews: '#826bf8'
                }
            };

            // Line Area Chart
            // --------------------------------------------------------------------
            const reviewChartEl = document.querySelector('#reviewsChart');
            const reviewChartConfig = {
                chart: {
                    height: 450,
                    type: 'bar',
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: false,
                    curve: 'straight'
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'start',
                    labels: {
                        colors: legendColor,
                        useSeriesColors: false
                    }
                },
                grid: {
                    borderColor: borderColor,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                },
                colors: [chartColors.area.reviews],
                series: [],
                xaxis: {
                    categories: [],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '13px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '13px'
                        },
                        formatter: function (val) {
                            return val.toFixed(0);
                        }
                    }
                },
                fill: {
                    opacity: 1,
                    type: 'solid'
                },
                tooltip: {
                    shared: false
                },
                noData: {
                    text: trans('No data available'),
                }
            };

            const reviewChart = new ApexCharts(reviewChartEl, reviewChartConfig);
            reviewChart.render();

            $('#filter_date_range').daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                ranges: {
                    [trans('Today')]: [moment(), moment()],
                    [trans('Yesterday')]: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    [trans('Last :days Days', {days: 7})]: [moment().subtract(6, 'days'), moment()],
                    [trans('Last :days Days', {days: 30})]: [moment().subtract(29, 'days'), moment()],
                    [trans('This Month')]: [moment().startOf('month'), moment().endOf('month')],
                    [trans('Last Month')]: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    [trans('Last Year')]: [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                    [trans('All Time')]: [moment().subtract(storeCreatedDiffInDays, 'days'), moment()]
                },
                opens: isRtl ? 'left' : 'right',
                minDate: moment().subtract(storeCreatedDiffInDays, 'days'),
                maxDate: moment(),
                showDropdowns: true,
                alwaysShowCalendars: true,
            });

            $('#filter_date_range')
                .on('cancel.daterangepicker', function (ev, picker) {
                    $(this).data('daterangepicker').setStartDate(moment().subtract(29, 'days'));
                })
                .on('change', function () {
                    updateReviewsChart();
                })

            $('#ratingAll').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.single-rating input[type="checkbox"]').prop('checked', true);
                } else {
                    $('.single-rating input[type="checkbox"]').prop('checked', false);
                }

                updateReviewsChart();
            })

            $('.single-rating input[type="checkbox"]').on('change', function () {
                if ($('.single-rating input[type="checkbox"]:checked').length === 5) {
                    $('#ratingAll').prop('checked', true);
                } else {
                    $('#ratingAll').prop('checked', false);
                }

                updateReviewsChart();
            })

            function updateReviewsChart() {
                let dateRange = $('#filter_date_range').val();
                let ratings = $('.single-rating input[type="checkbox"]:checked').map(function () {
                    return $(this).val();
                }).get()

                $.ajax({
                    url: route('user.analytics.reviews.chart'),
                    method: 'GET',
                    data: {
                        date_range: dateRange,
                        ratings: ratings
                    },
                    beforeSend: function () {
                        blockChartWrapper();
                    },
                    success: function (response) {
                        reviewChart.updateOptions({
                            xaxis: {
                                categories: response.dates
                            },
                            series: [{
                                name: trans('Reviews'),
                                data: response.reviews
                            }],
                        });
                    },
                    error: function (xhr) {
                        flash('error', xhr.responseJSON.message)
                    },
                    complete: function () {
                        unblockChartWrapper();
                    }
                })
            }

            updateReviewsChart();

            function blockChartWrapper() {
                $('#chartWrapper').block({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        opacity: 0.5
                    }
                });
            }

            function unblockChartWrapper() {
                $('#chartWrapper').unblock();
            }

            // Donut Chart
            // --------------------------------------------------------------------
            const donutChartEl = document.querySelector('#donutChart'),
                donutChartConfig = {
                    chart: {
                        height: 300,
                        type: 'donut'
                    },
                    labels: deviceChart['types'],
                    series: deviceChart['data'],
                    colors: [
                        chartColors.donut.series1,
                        chartColors.donut.series4,
                        chartColors.donut.series3,
                        chartColors.donut.series2,
                        chartColors.donut.series5,
                        chartColors.donut.series6
                    ],
                    stroke: {
                        show: false,
                        curve: 'straight'
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val, opt) {
                            return parseInt(val) + '%';
                        }
                    },
                    legend: {
                        show: true,
                        position: 'bottom',
                        markers: { offsetX: -3 },
                        itemMargin: {
                            vertical: 3,
                            horizontal: 10
                        },
                        labels: {
                            colors: legendColor,
                            useSeriesColors: false
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return parseInt(val) + '%';
                            }
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        fontSize: '2rem',
                                        fontFamily: 'Public Sans'
                                    },
                                    value: {
                                        fontSize: '1.2rem',
                                        color: legendColor,
                                        fontFamily: 'Public Sans',
                                        formatter: function (val) {
                                            return parseInt(val, 10) + '%';
                                        }
                                    },
                                }
                            }
                        }
                    },
                    responsive: [
                        {
                            breakpoint: 992,
                            options: {
                                chart: {
                                    height: 380
                                },
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        colors: legendColor,
                                        useSeriesColors: false
                                    }
                                }
                            }
                        },
                        {
                            breakpoint: 576,
                            options: {
                                chart: {
                                    height: 320
                                },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            labels: {
                                                show: true,
                                                name: {
                                                    fontSize: '1.5rem'
                                                },
                                                value: {
                                                    fontSize: '1rem'
                                                },
                                                total: {
                                                    fontSize: '1.5rem'
                                                }
                                            }
                                        }
                                    }
                                },
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        colors: legendColor,
                                        useSeriesColors: false
                                    }
                                }
                            }
                        },
                        {
                            breakpoint: 420,
                            options: {
                                chart: {
                                    height: 280
                                },
                                legend: {
                                    show: false
                                }
                            }
                        },
                        {
                            breakpoint: 360,
                            options: {
                                chart: {
                                    height: 250
                                },
                                legend: {
                                    show: false
                                }
                            }
                        }
                    ]
                };
            if (typeof donutChartEl !== undefined && donutChartEl !== null) {
                const donutChart = new ApexCharts(donutChartEl, donutChartConfig);
                donutChart.render();
            }
        }())
    </script>
@endpush
