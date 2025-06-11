@extends('layouts.company')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@push('footer')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            var chartBarOptions = {
                series: [{
                        name: "{{ __('Income') }}",
                        data: {!! json_encode($incExpLineChartData['income'] ?? 0) !!}
                    },
                    {
                        name: "{{ __('Expense') }}",
                        data: {!! json_encode($incExpLineChartData['expense'] ?? 0) !!}
                    }
                ],

                chart: {
                    height: 300,
                    type: 'area',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode($incExpLineChartData['day'] ?? 0) !!},
                    title: {
                        text: '{{ __('Date') }}'
                    }
                },
                colors: ['#ffa21d', '#FF3A6E'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    title: {
                        text: '{{ __('Amount') }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#cash-flow"), chartBarOptions);
            arChart.render();
        })();

        (function() {
            var options = {
                chart: {
                    height: 300,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: "{{ __('Income') }}",
                    data: {!! json_encode($incExpBarChartData['income'] ?? 0) !!}
                }, {
                    name: "{{ __('Expense') }}",
                    data: {!! json_encode($incExpBarChartData['expense'] ?? 0) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($incExpBarChartData['month'] ?? 0) !!},
                },
                colors: ['#3ec9d6', '#FF3A6E'],
                fill: {
                    type: 'solid',
                },
                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                },
                markers: {
                    size: 4,
                    colors: ['#3ec9d6', '#FF3A6E', ],
                    opacity: 0.9,
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
            chart.render();
        })();

        (function() {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($expenseCatAmount ?? 0) !!},
                colors: {!! json_encode($expenseCategoryColor ?? 0) !!},
                labels: {!! json_encode($expenseCategory ?? 0) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#expenseByCategory"), options);
            chart.render();
        })();

        (function() {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($incomeCatAmount ?? 0) !!},
                colors: {!! json_encode($incomeCategoryColor ?? 0) !!},
                labels: {!! json_encode($incomeCategory ?? 0) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#incomeByCategory"), options);
            chart.render();
        })();


        (function() {
            var options = {
                series: [{{ $totalStorage > 0 ? $usedStorage / $totalStorage : 0 }}],
                chart: {
                    height: 260,
                    type: 'radialBar',
                    offsetY: 0,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                        },
                        dataLabels: {
                            name: {
                                show: true
                            },
                            value: {
                                offsetY: -50,
                                fontSize: '20px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                colors: ["#6FD943"],
                labels: ['Used'],
            };
            var chart = new ApexCharts(document.querySelector("#storage-chart"), options);
            chart.render();
        })();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertId = 'company-expiry-alert';
            const today = new Date().toISOString().split('T')[0];
            const closedDate = localStorage.getItem(alertId);

            // Show the alert if not already closed today
            if (closedDate !== today) {
                const alertBox = document.getElementById('daily-alert');
                if (alertBox) {
                    alertBox.style.display = 'block';
                }
            }

            // Handle close click (event delegation like jQuery .on)
            document.body.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'close-daily-alert') {
                    alert(1);
                    localStorage.setItem(alertId, today);
                    const alertBox = document.getElementById('daily-alert');
                    if (alertBox) {
                        alertBox.style.display = 'none';
                    }
                }
            });
        });
    </script>
@endpush

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li> --}}
@endsection
<style type="text/css">
    .apexcharts-legend {
        display: flex;
        overflow: auto;
        padding: 0 10px;
    }

    .apexcharts-legend.apx-legend-position-bottom,
    .apexcharts-legend.apx-legend-position-top {
        flex-wrap: wrap
    }

    .apexcharts-legend.apx-legend-position-right,
    .apexcharts-legend.apx-legend-position-left {
        flex-direction: column;
        bottom: 0;
    }

    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
    .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
    .apexcharts-legend.apx-legend-position-right,
    .apexcharts-legend.apx-legend-position-left {
        justify-content: flex-start;
    }

    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
    .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
        justify-content: center;
    }

    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
    .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
        justify-content: flex-end;
    }

    .apexcharts-legend-series {
        cursor: pointer;
        line-height: normal;
    }

    .apexcharts-legend.apx-legend-position-bottom .apexcharts-legend-series,
    .apexcharts-legend.apx-legend-position-top .apexcharts-legend-series {
        display: flex;
        align-items: center;
    }

    .apexcharts-legend-text {
        position: relative;
        font-size: 14px;
    }

    .apexcharts-legend-text *,
    .apexcharts-legend-marker * {
        pointer-events: none;
    }

    .apexcharts-legend-marker {
        position: relative;
        display: inline-block;
        cursor: pointer;
        margin-right: 3px;
        border-style: solid;
    }

    .apexcharts-legend.apexcharts-align-right .apexcharts-legend-series,
    .apexcharts-legend.apexcharts-align-left .apexcharts-legend-series {
        display: inline-block;
    }

    .apexcharts-legend-series.apexcharts-no-click {
        cursor: auto;
    }

    .apexcharts-legend .apexcharts-hidden-zero-series,
    .apexcharts-legend .apexcharts-hidden-null-series {
        display: none !important;
    }

    .apexcharts-inactive-legend {
        opacity: 0.45;
    }
</style>
<style>
    .dashboard-card {
        position: relative;
        height: 100%;
        margin-bottom: 0;
        background-color: #032636;
        border-radius: 10px;
        z-index: 1;
    }

    .dashboard-card-layer {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .dashboard-card .card-inner {
        position: relative;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        border-radius: 10px;
        height: 100%;
        color: #fff;
        gap: 20px;
    }

    .dashboard-card .card-inner .card-content {
        max-width: 70%;
        width: 100%;
    }

    .dashboard-card .card-inner .card-content h2 {
        color: #ffffff;
        text-transform: capitalize;
    }

    .dashboard-card .card-inner .card-content p {
        font-size: 14px;
        max-width: 90%;
        width: 100%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .dashboard-card .card-inner .card-content .btn {
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        transition: all ease-in-out 500ms 0s;
    }

    @media screen and (max-width: 1440px) {
        .dashboard-card .card-inner .card-icon {
            padding: 20px;
        }
    }

    .dashboard-card .card-inner .card-icon {
        position: relative;
        background: #1C3B4A;
        border-radius: 50%;
        padding: 25px;
        z-index: 1;
    }

    .dashboard-card .card-inner .card-icon::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 80%;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: -1;
    }

    @media screen and (max-width: 1440px) {
        .dashboard-card .card-inner .card-icon svg {
            width: 70px;
            height: 70px;
        }
    }

    .dashboard-card .card-inner::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background-color: rgba(12, 175, 96, 0.4);
        border-radius: 80% 0 10px;
    }

    .card {
        margin-bottom: 30px;
        border: 0px;
        border-radius: 0.625rem;
        box-shadow: 6px 11px 41px -28px #a99de7;
    }

    .gradient-1 {
        color: #fff !important;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
    }

    .gradient-1,
    .dropdown-mega-menu .ext-link.link-1 a,
    .morris-hover,
    .datamaps-hoverover {
        background-image: linear-gradient(230deg, #759bff, #843cf6);
    }

    .card .card-body {
        padding: 1.88rem 1.81rem;
    }

    .card-title {
        font-size: 16px;
        font-weight: 500;
        line-height: 18px;
    }

    .gradient-2,
    .dropdown-mega-menu .ext-link.link-3 a {
        background-image: linear-gradient(230deg, #fc5286, #fbaaa2);
    }

    .gradient-3,
    .dropdown-mega-menu .ext-link.link-2 a,
    .header-right .icons .user-img .activity {
        background-image: linear-gradient(230deg, #ffc480, #ff763b);
    }

    .gradient-4,
    .sidebar-right .nav-tabs .nav-item .nav-link.active::after,
    .sidebar-right .nav-tabs .nav-item .nav-link.active span i::before {
        background-image: linear-gradient(230deg, #0e4cfd, #6a8eff);
    }
</style>
@section('content')
    <div class="row row-gap mb-4 ">
       
        @if ($showExpiryAlert)
            <div class="col-xl-12 col-12" id="daily-alert" style="display: none;">
                <div class="dashboard-card position-relative">
                    <img src="{{ asset('assets/images/layer.png') }}" class="dashboard-card-layer" alt="layer">
                    {{-- <button id="close-daily-alert"
                        class="btn btn-sm btn-light position-absolute top-0 end-0 m-2">&times;</button> --}}
                    <div class="card-inner">
                        <div class="card-content">
                            <h2>Plan Expiring Soon</h2>
                            <p class="my-2">
                                Your subscription plan will expire on
                                <strong>{{ $planExpiryDate->format('d M, Y') }}</strong>. Please renew to avoid service
                                interruption.
                            </p>
                            <div class="btn-wrp d-flex gap-3">
                                <a href="{{ route('company.plan.upgrade') }}"
                                    class="btn btn-primary d-flex align-items-center gap-1 cp_link">
                                    <i class="ti ti-link text-white"></i>
                                    <span>Renew Subscription</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mt-4">
            <div class="col-lg-9">
                <div class="row mt-4">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-1">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Staff Users</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $total_staff_users }}</h2>
                                    </div>
                                </div>

                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-2">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Owners</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white"> {{ $total_owners }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-3">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Tenants</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $total_tenants }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-4">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Properties</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $total_propeties }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-4">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Support Tickets</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $total_ticket }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-4">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Bank Accounts </h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $bankAccountBalance->count() }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-1">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Expense</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ \Auth::user()->priceFormat($totalExpenses) }}</h2>
                                    </div>
                                </div>

                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-2">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Deposit</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white"> {{ \Auth::user()->priceFormat($totalDeposits) }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-3">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Account Balance</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">
                                            {{ \Auth::user()->priceFormat($bankAccountBalance->sum('current_balance')) }}
                                        </h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-3">
                <div class="row mt-4">

                    <div class="col-lg-12">

                        <div class="card" style="height: 360px">
                            <div class="card-header">
                                <h5>{{ __('Storage Status') }} </h5>
                                <small class="mt-2">({{ $usedStorage . 'MB' }}
                                    /
                                    {{ $totalStorage . 'MB' }})</small>
                            </div>
                            <div class="card-body">
                                <div id="storage-chart"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-xxl-6">
            <div class="card" style="height: 415px">
                <div class="card-header">
                    <h5 class="mt-1 mb-0">{{ __('Latest Income') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Amount Due') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestIncome ?? [] as $income)
                                    <tr>
                                        <td>{{ \Auth::user()->dateFormat($income->date) }}</td>
                                        <td>{{ !empty($income->customer) ? $income->customer->name : '-' }}
                                        </td>
                                        <td>{{ \Auth::user()->priceFormat($income->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center">
                                                <h6>{{ __('there is no latest income') }}</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xxl-6">
            <div class=" @if (\Auth::user()->type == 'company' || !empty($plan)) col-xxl-12 @else col-xxl-12 @endif ">
                <div class="card" style="height: 415px">
                    <div class="card-header">
                        <h5 class="mt-1 mb-0">{{ __('Latest Expense') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Tenant/Owner') }}</th>
                                        <th>{{ __('Amount Due') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestExpense ?? [] as $expense)
                                        <tr>
                                            <td>{{ \Auth::user()->dateFormat($expense->date) }}</td>
                                            <td>{{ !empty($expense->customer) ? $expense->customer->name : '-' }}
                                            </td>
                                            <td>{{ \Auth::user()->priceFormat($expense->amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center">
                                                    <h6>{{ __('there is no latest expense') }}</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 mb-0">{{ __('Recent Invoices') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Issue Date') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoice ?? [] as $invoice)
                                    <tr>
                                        <td>{{ \Auth::user()->invoiceNumberFormat($invoice->invoice_id) }}</td>
                                        <td>{{ !empty($invoice->customer) ? $invoice->customer->name : '' }}
                                        </td>
                                        <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                        <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                        <td>{{ \Auth::user()->priceFormat($invoice->getTotal()) }}</td>
                                        <td>
                                            @if ($invoice->status == 0)
                                                <span
                                                    class="p-2 px-3 fix_badges badge bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 1)
                                                <span
                                                    class="p-2 px-3 fix_badges badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 2)
                                                <span
                                                    class="p-2 px-3 fix_badges badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 3)
                                                <span
                                                    class="p-2 px-3 fix_badges badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 4)
                                                <span
                                                    class="p-2 px-3 fix_badges badge bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="text-center">
                                                <h6>{{ __('there is no recent invoice') }}</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 mb-0">{{ __('Recent Bills') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Bill Date') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBill ?? [] as $bill)
                                    <tr>
                                        <td>{{ \Auth::user()->billNumberFormat($bill->bill_id) }}</td>
                                        <td>{{ !empty($bill->vender) ? $bill->vender->name : '' }} </td>
                                        <td>{{ Auth::user()->dateFormat($bill->bill_date) }}</td>
                                        <td>{{ Auth::user()->dateFormat($bill->due_date) }}</td>
                                        <td>{{ \Auth::user()->priceFormat($bill->getTotal()) }}</td>
                                        <td>
                                            @if ($bill->status == 0)
                                                <span
                                                    class="p-2 px-3 fix_badge badge bg-secondary">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 1)
                                                <span
                                                    class="p-2 px-3 fix_badge badge bg-warning">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 2)
                                                <span
                                                    class="p-2 px-3 fix_badge badge bg-danger">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 3)
                                                <span
                                                    class="p-2 px-3 fix_badge badge bg-info">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 4)
                                                <span
                                                    class="p-2 px-3 fix_badge badge bg-success">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="text-center">
                                                <h6>{{ __('there is no recent bill') }}</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 mb-0">{{ __('Account Balance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Bank') }}</th>
                                    <th>{{ __('Holder Name') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bankAccountDetail ?? [] as $bankAccount)
                                    <tr class="font-style">
                                        <td>{{ $bankAccount->bank_name }}</td>
                                        <td>{{ $bankAccount->holder_name }}</td>
                                        <td>{{ \Auth::user()->priceFormat($bankAccount->opening_balance) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center">
                                                <h6>{{ __('there is no account balance') }}</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection
