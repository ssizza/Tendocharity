@extends('admin.layouts.app')

@section('panel')

    <div class="row gy-4">
        <div class="col-xxl-6 col-xl-12">
            <div class="row gy-4">
                <div>
                    <span class="info-badge">@lang('Clients')</span>
                </div>

                <div class="col-xxl-6 col-xl-6 col-md-6 col-sm-6">
                    <x-widget
                        style="6" 
                        link="javascript:void(0)" 
                        icon="las la-users" 
                        title="Total Clients" 
                        value="125" 
                        bg="primary" 
                    />
                </div><!-- dashboard-w1 end -->
                <div class="col-xxl-6 col-xl-6 col-md-6 col-sm-6">
                    <x-widget
                        style="6" 
                        link="javascript:void(0)" 
                        icon="las la-user-check" 
                        title="Active Clients" 
                        value="98" 
                        bg="success" 
                    />
                </div><!-- dashboard-w1 end -->
                <div class="col-xxl-6 col-xl-6 col-md-6 col-sm-6">
                    <x-widget
                        style="6" 
                        link="javascript:void(0)" 
                        icon="lar la-envelope" 
                        title="Email Unverified Clients" 
                        value="15" 
                        bg="danger" 
                    />
                </div><!-- dashboard-w1 end -->
                <div class="col-xxl-6 col-xl-6 col-md-6 col-sm-6">
                    <x-widget
                        style="6" 
                        link="javascript:void(0)" 
                        icon="las la-comment-slash" 
                        title="Mobile Unverified Clients" 
                        value="12" 
                        bg="red" 
                    />
                </div><!-- dashboard-w1 end -->

                <div>
                    <span class="info-badge">@lang('Orders')</span>
                </div>
                <div class="col-xxl-12">
                    <div class="card box-shadow3 h-100">
                        <div class="card-body">
                            <h5 class="card-title"></h5>
                            <div class="widget-card-wrapper">
                                <div class="widget-card bg--success">
                                    <a href="javascript:void(0)" class="widget-card-link"></a>
                                    <div class="widget-card-left">
                                        <div class="widget-card-icon">
                                            <i class="las la-shopping-cart"></i>
                                        </div>
                                        <div class="widget-card-content">
                                            <h6 class="widget-card-amount">{{ showAmount(1250.50) }}</h6>
                                            <p class="widget-card-title">@lang('Total Orders')</p>
                                        </div>
                                    </div>
                                    <span class="widget-card-arrow">
                                        <i class="las la-angle-right"></i>
                                    </span>
                                </div>
                                <div class="widget-card bg--warning">
                                    <a href="javascript:void(0)" class="widget-card-link"></a>
                                    <div class="widget-card-left">
                                        <div class="widget-card-icon">
                                            <i class="las la-check"></i>
                                        </div>
                                        <div class="widget-card-content">
                                            <h6 class="widget-card-amount">{{ showAmount(850.00) }}</h6>
                                            <p class="widget-card-title">@lang('Active Orders')</p>
                                        </div>
                                    </div>
                                    <span class="widget-card-arrow">
                                        <i class="las la-angle-right"></i>
                                    </span>
                                </div>
                                <div class="widget-card bg--danger">
                                    <a href="javascript:void(0)" class="widget-card-link"></a>
                                    <div class="widget-card-left">
                                        <div class="widget-card-icon">
                                            <i class="las la-spinner"></i>
                                        </div>
                                        <div class="widget-card-content">
                                            <h6 class="widget-card-amount">{{ showAmount(150.25) }}</h6>
                                            <p class="widget-card-title">@lang('Pending Orders')</p>
                                        </div>
                                    </div>
                                    <span class="widget-card-arrow">
                                        <i class="las la-angle-right"></i>
                                    </span>
                                </div>
                                <div class="widget-card bg--primary">
                                    <a href="javascript:void(0)" class="widget-card-link"></a>
                                    <div class="widget-card-left">
                                        <div class="widget-card-icon">
                                            <i class="las la-times"></i>
                                        </div>
                                        <div class="widget-card-content">
                                            <h6 class="widget-card-amount">{{ showAmount(50.25) }}</h6>
                                            <p class="widget-card-title">@lang('Cancelled Orders')</p>
                                        </div>
                                    </div>
                                    <span class="widget-card-arrow">
                                        <i class="las la-angle-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="col-xxl-6">
            <span class="info-badge-two">@lang('Order Statistics')</span>
            <div class="card full-view">
                <div class="card-header d-flex justify-content-between flex-wrap bg--dark">
                    <div>
                        <small class="time_text text--white"></small> @lang('Orders')
                        <small class="text--white">{{ gs('cur_sym') }}</small><span class="total_orders text--white"></span>
                    </div>
                    <div class="d-flex justify-content-sm-end gap-2 mt-2 mt-xl-0 mt-md-0 mt-sm-0">
                        <div>
                            <select name="order_statistics" class="widget_select bg--dark text--white">
                                <option value="today">@lang('Today')</option>
                                <option value="week">@lang('This Week')</option>
                                <option value="month" selected>@lang('This Month')</option>
                                <option value="year">@lang('This Year')</option>
                            </select>
                            <select name="order_status" class="widget_select bg--dark text--white ms-1">
                                <option value="" selected>@lang('All')</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <button class="exit-btn text--white">
                            <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>
                            <i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="order_canvas">
                        <canvas height="162" id="order_chart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- row end-->

    <div class="row gy-4 mt-2">
        <div>
            <span class="info-badge">@lang('Invoices')</span>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget 
                style="2" 
                link="javascript:void(0)" 
                icon="las la-money-bill-wave" 
                icon_style="outline" 
                title="Paid Invoices" 
                value="{{ showAmount(950.75) }}" 
                color="success" 
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget 
                style="2" 
                link="javascript:void(0)" 
                icon="las la-file-invoice" 
                icon_style="outline" 
                title="Unpaid Invoices" 
                value="{{ showAmount(250.25) }}" 
                color="warning" 
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget 
                style="2" 
                link="javascript:void(0)" 
                icon="las la-spinner" 
                icon_style="outline" 
                title="Payment Pending Invoices" 
                value="{{ showAmount(49.50) }}" 
                color="danger" 
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget 
                style="2" 
                link="javascript:void(0)" 
                icon="las la-hand-holding-usd" 
                icon_style="outline" 
                title="Refunded Invoices" 
                value="{{ showAmount(25.00) }}" 
                color="primary" 
            />
        </div>
    </div><!-- row end-->

    <!-- Cron Modal (Optional - can be removed if not needed) -->
    <div class="modal fade" id="cronModal" tabindex="-1" role="dialog" aria-labelledby="cronModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cronModalLabel">@lang('Cron Job Setup')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Cron job is not required for demo setup.')</p>
                    <p>@lang('This is a demo dashboard with sample data.')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary" data-bs-toggle="modal" data-bs-target="#cronModal">
        <i class="las la-server"></i>@lang('Cron Setup')
    </button>
@endpush

@push('style')
    <style>
        .exit-btn {
            padding: 0;
            font-size: 30px;
            line-height: 1;
            color: #5b6e88;
            background: transparent;
            border: none;
            transition: all .3s ease;
        }

        .exit-btn .fullscreen-close {
            transition: all 0.3s;
            display: none;
        }

        .exit-btn.active .fullscreen-close {
            display: block;
        }

        .widget_select {
            padding: 3px 3px;
            font-size: 13px;
        }

        .exit-btn.active .fullscreen-open {
            display: none;
        }

        .info-badge {
            top: 40%;
            left: -21px;
            background-color: #5352ed;
            color: #fff;
            font-size: 13px;
            width: 92px;
            height: 22px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            transform: translateY(10px);
        }

        .info-badge-two {
            top: 40%;
            left: -21px;
            background-color: #5352ed;
            color: #fff;
            font-size: 13px;
            width: 150px;
            height: 22px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            transform: translateY(10px);
            margin-bottom: 25px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>

    <script>
        "use strict";

        // Demo data for charts
        const demoChartData = {
            today: {
                labels: ['9 AM', '12 PM', '3 PM', '6 PM', '9 PM'],
                data: [120, 190, 300, 500, 250],
                total: 1360
            },
            week: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                data: [850, 720, 950, 680, 920, 750, 600],
                total: 5470
            },
            month: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                data: [1250, 980, 1520, 1100],
                total: 4850
            },
            year: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                data: [950, 820, 1100, 980, 1250, 1400, 1350, 1520, 1280, 1450, 1600, 1750],
                total: 14500
            }
        };

        function orderGraph() {
            var time = $('[name=order_statistics] option:selected').val();
            var text = $('[name=order_statistics] option:selected').text();
            var chartData = demoChartData[time] || demoChartData.month;

            $('.time_text').text(text);
            $('.total_orders').text(chartData.total ? chartData.total.toFixed(2) : 0);

            $('.order_canvas').html(
                '<canvas height="162" id="order_chart" class="mt-4"></canvas>'
            )

            var ctx = document.getElementById('order_chart');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: [
                            '#6c5ce7', '#a29bfe', '#fd79a8', '#00cec9', '#74b9ff',
                            '#55efc4', '#81ecec', '#dfe6e9', '#ffeaa7', '#fab1a0',
                            '#e17055', '#d63031'
                        ],
                        borderColor: [
                            'rgba(231, 80, 90, 0.75)'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    aspectRatio: 1,
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0 // disables bezier curves
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: true
                        }],
                        yAxes: [{
                            display: true
                        }]
                    },
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        callbacks: {
                            label: (tooltipItem, data) => data.datasets[0].data[tooltipItem.index] + ' {{ gs("cur_text") }}'
                        }
                    }
                }
            });
        }

        // Initialize chart on page load
        orderGraph();

        $('[name=order_statistics], [name=order_status]').on('change', function() {
            orderGraph();
        });

        var elems = document.querySelector(".full-view");
        $('.exit-btn').on('click', function() {
            $(this).toggleClass('active');
        });

        function openFullscreen() {
            if (elems.requestFullscreen) {
                elems.requestFullscreen();
            } else if (elems.mozRequestFullScreen) {
                /* Firefox */
                elems.mozRequestFullScreen();
            } else if (elems.webkitRequestFullscreen) {
                /* Chrome, Safari & Opera */
                elems.webkitRequestFullscreen();
            } else if (elems.msRequestFullscreen) { 
                /* IE/Edge */
                elems.msRequestFullscreen();
            }
        }

        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                /* Firefox */
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                /* Chrome, Safari and Opera */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                /* IE/Edge */
                document.msExitFullscreen();
            }
        }
        
    </script>
@endpush