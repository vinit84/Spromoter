/**
 * Dashboard Analytics
 */

'use strict';

(function () {
    let cardColor, headingColor, labelColor, shadeColor, grayColor;
    if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        labelColor = config.colors_dark.textMuted;
        headingColor = config.colors_dark.headingColor;
        shadeColor = 'dark';
        grayColor = '#5E6692'; // gray color is for stacked bar chart
    } else {
        cardColor = config.colors.cardColor;
        labelColor = config.colors.textMuted;
        headingColor = config.colors.headingColor;
        shadeColor = '';
        grayColor = '#817D8D';
    }

    // swiper loop and autoplay
    // --------------------------------------------------------------------
    const swiperWithPagination = document.querySelector('#swiper-with-pagination-cards');
    if (swiperWithPagination) {
        new Swiper(swiperWithPagination, {
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            pagination: {
                clickable: true,
                el: '.swiper-pagination'
            }
        });
    }

    // Revenue Generated Area Chart
    // --------------------------------------------------------------------
    const revenueGeneratedEl = document.querySelector('#revenueGenerated'),
        revenueGeneratedConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.success],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: shadeColor,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [
                {
                    data: [300, 350, 330, 380, 340, 400, 380]
                }
            ],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: false
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            },

            noData: {
                text: undefined,
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '14px',
                    fontFamily: undefined
                }
            }
        };
    if (typeof revenueGeneratedEl !== undefined && revenueGeneratedEl !== null) {
        const revenueGenerated = new ApexCharts(revenueGeneratedEl, revenueGeneratedConfig);
        revenueGenerated.render();
    }

    // Earning Reports Bar Chart
    // --------------------------------------------------------------------
    const weeklyEarningReportsEl = document.querySelector('#weeklyEarningReports'),
        maxWeeklyEarningIndex = window.weekylyEarningAmounts.indexOf(Math.max(...window.weekylyEarningAmounts)),

        weeklyEarningReportsConfig = {
            chart: {
                height: 202,
                parentHeightOffset: 0,
                type: 'bar',
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    barHeight: '60%',
                    columnWidth: '38%',
                    startingShape: 'rounded',
                    endingShape: 'rounded',
                    borderRadius: 4,
                    distributed: true
                }
            },
            grid: {
                show: false,
                padding: {
                    top: -30,
                    bottom: 0,
                    left: -10,
                    right: -10
                }
            },
            colors: [
                maxWeeklyEarningIndex === 0 ? config.colors.primary : config.colors_label.primary,
                maxWeeklyEarningIndex === 1 ? config.colors.primary : config.colors_label.primary,
                maxWeeklyEarningIndex === 2 ? config.colors.primary : config.colors_label.primary,
                maxWeeklyEarningIndex === 3 ? config.colors.primary : config.colors_label.primary,
                maxWeeklyEarningIndex === 4 ? config.colors.primary : config.colors_label.primary,
                maxWeeklyEarningIndex === 5 ? config.colors.primary : config.colors_label.primary,
                maxWeeklyEarningIndex === 6 ? config.colors.primary : config.colors_label.primary,
            ],
            dataLabels: {
                enabled: false
            },
            series: [
                {
                    data: window.weekylyEarningAmounts
                }
            ],
            legend: {
                show: false
            },
            xaxis: {
                categories: window.weeklyEarningDays,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '13px',
                        fontFamily: 'Public Sans'
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            tooltip: {
                enabled: false
            },
            responsive: [
                {
                    breakpoint: 1025,
                    options: {
                        chart: {
                            height: 199
                        }
                    }
                }
            ],
        };
    if (typeof weeklyEarningReportsEl !== undefined && weeklyEarningReportsEl !== null) {
        const weeklyEarningReports = new ApexCharts(weeklyEarningReportsEl, weeklyEarningReportsConfig);
        weeklyEarningReports.render();
    }

    // Support Tracker - Radial Bar Chart
    // --------------------------------------------------------------------
    const supportTrackerEl = document.querySelector('#supportTracker'),
        supportTrackerOptions = {
            series: [supportTrackerEl.dataset.closedTickets],
            labels: [trans('Closed Tickets')],
            chart: {
                height: 360,
                type: 'radialBar'
            },
            plotOptions: {
                radialBar: {
                    offsetY: 10,
                    startAngle: -140,
                    endAngle: 130,
                    hollow: {
                        size: '65%'
                    },
                    track: {
                        background: cardColor,
                        strokeWidth: '100%'
                    },
                    dataLabels: {
                        name: {
                            offsetY: -20,
                            color: labelColor,
                            fontSize: '13px',
                            fontWeight: '400',
                            fontFamily: 'Public Sans'
                        },
                        value: {
                            offsetY: 10,
                            color: headingColor,
                            fontSize: '38px',
                            fontWeight: '500',
                            fontFamily: 'Public Sans'
                        }
                    }
                }
            },
            colors: [config.colors.primary],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.5,
                    gradientToColors: [config.colors.primary],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 0.6,
                    stops: [30, 70, 100]
                }
            },
            stroke: {
                dashArray: 10
            },
            grid: {
                padding: {
                    top: -20,
                    bottom: 5
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 1025,
                    options: {
                        chart: {
                            height: 330
                        }
                    }
                },
                {
                    breakpoint: 769,
                    options: {
                        chart: {
                            height: 280
                        }
                    }
                }
            ]
        };
    if (typeof supportTrackerEl !== undefined && supportTrackerEl !== null) {
        const supportTracker = new ApexCharts(supportTrackerEl, supportTrackerOptions);
        supportTracker.render();
    }

    // Total Earning Chart - Bar Chart
    // --------------------------------------------------------------------
    const totalEarningChartEl = document.querySelector('#totalEarningChart'),
        totalEarningChartOptions = {
            series: [
                {
                    name: 'Earning',
                    data: [15, 10, 20, 8, 12, 18, 12, 5]
                },
                {
                    name: 'Expense',
                    data: [-7, -10, -7, -12, -6, -9, -5, -8]
                }
            ],
            chart: {
                height: 230,
                parentHeightOffset: 0,
                stacked: true,
                type: 'bar',
                toolbar: { show: false }
            },
            tooltip: {
                enabled: false
            },
            legend: {
                show: false
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '18%',
                    borderRadius: 5,
                    startingShape: 'rounded',
                    endingShape: 'rounded'
                }
            },
            colors: [config.colors.primary, grayColor],
            dataLabels: {
                enabled: false
            },
            grid: {
                show: false,
                padding: {
                    top: -40,
                    bottom: -20,
                    left: -10,
                    right: -2
                }
            },
            xaxis: {
                labels: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            responsive: [
                {
                    breakpoint: 1468,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '22%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 1197,
                    options: {
                        chart: {
                            height: 228
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 8,
                                columnWidth: '26%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 783,
                    options: {
                        chart: {
                            height: 232
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                columnWidth: '28%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 589,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '16%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 520,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                columnWidth: '18%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 426,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 5,
                                columnWidth: '20%'
                            }
                        }
                    }
                },
                {
                    breakpoint: 381,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '24%'
                            }
                        }
                    }
                }
            ],
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            }
        };
    if (typeof totalEarningChartEl !== undefined && totalEarningChartEl !== null) {
        const totalEarningChart = new ApexCharts(totalEarningChartEl, totalEarningChartOptions);
        totalEarningChart.render();
    }
})();
