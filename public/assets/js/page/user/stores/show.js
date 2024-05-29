'use strict';
(function () {
    const maxReviewsIndex = window.reviews.indexOf(Math.max(...window.reviews));

    let labelColor;
    if (isDarkStyle) {
        labelColor = config.colors_dark.textMuted;
    } else {
        labelColor = config.colors.textMuted;
    }

    const reviewsChartEl = document.querySelector('#reviewsChart'),
        reviewsChartConfig = {
            chart: {
                height: 160,
                width: '100%',
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    barHeight: '75%',
                    columnWidth: '40%',
                    startingShape: 'rounded',
                    endingShape: 'rounded',
                    borderRadius: 5,
                    distributed: true
                }
            },
            grid: {
                show: false,
                padding: {
                    top: -25,
                    bottom: -12
                }
            },
            colors: [
                maxReviewsIndex === 0 ? config.colors.success : config.colors_label.success,
                maxReviewsIndex === 1 ? config.colors.success : config.colors_label.success,
                maxReviewsIndex === 2 ? config.colors.success : config.colors_label.success,
                maxReviewsIndex === 3 ? config.colors.success : config.colors_label.success,
                maxReviewsIndex === 4 ? config.colors.success : config.colors_label.success,
                maxReviewsIndex === 5 ? config.colors.success : config.colors_label.success,
                maxReviewsIndex === 6 ? config.colors.success : config.colors_label.success,
            ],
            dataLabels: {
                enabled: false
            },
            series: [
                {
                    name: trans('Reviews'),
                    data: window.reviews
                }
            ],
            legend: {
                show: false
            },
            xaxis: {
                categories: window.days,
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
                    show: false
                }
            },
            responsive: [
                {
                    breakpoint: 0,
                    options: {
                        chart: {
                            width: '100%'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 1440,
                    options: {
                        chart: {
                            height: 150,
                            width: 190,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 1400,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 1200,
                    options: {
                        chart: {
                            height: 130,
                            width: 190,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 992,
                    chart: {
                        height: 150,
                        width: 190,
                        toolbar: {
                            show: false
                        }
                    },
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 5,
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 883,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 5,
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 150,
                            width: 190,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '40%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            width: '100%',
                            height: '200',
                            type: 'bar'
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                columnWidth: '30% '
                            }
                        }
                    }
                },
                {
                    breakpoint: 420,
                    options: {
                        plotOptions: {
                            chart: {
                                width: '100%',
                                height: '200',
                                type: 'bar'
                            },
                            bar: {
                                borderRadius: 3,
                                columnWidth: '30%'
                            }
                        }
                    }
                }
            ]
        };
    if (typeof reviewsChartEl !== undefined && reviewsChartEl !== null) {
        const reviewsChart = new ApexCharts(reviewsChartEl, reviewsChartConfig);
        reviewsChart.render();
    }

    $('#filter_date_range').daterangepicker({
        startDate: '01/01/2000',
        endDate: moment(),
        ranges: {
            Today: [moment(), moment()],
            Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'All Time': ['01/01/2000', moment()]
        },
        opens: isRtl ? 'left' : 'right'
    });

    $('#filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#filter_date_range').val('');
    });
})();
