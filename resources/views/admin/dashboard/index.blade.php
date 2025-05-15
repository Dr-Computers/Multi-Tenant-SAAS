@extends('layouts.admin')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@push('theme-script')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
@endpush

@push('script-page')
    <script>
        (function() {
            var chartBarOptions = {
                series: [{
                    name: '{{ __('Order') }}',
                    data: {!! json_encode($chartData['data']) !!},

                }, ],

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
                    categories: {!! json_encode($chartData['label']) !!},
                    title: {
                        text: ''
                    }
                },
                colors: ['#6fd944', '#6fd944'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    title: {
                        text: ''
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();
    </script>
@endpush

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li> --}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                <div class="col-lg-3 col-6 dashboard-card">
                    <div class="card">
                        <div class="card-body"> <a href="{{ route('admin.company.index') }}" class="text-primary">
                                <div class="d-flex flex-row gap-2">

                                    <div class="theme-avtar bg-primary mb-3 badge">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <div class="d-flex flex-column ">
                                        <h6 class="mb-3 fw-bold">{{ __('Total Companies') }} : <span
                                                class="mb-0">{{ $user['total_companies'] }}</span></h6>
                                        <p class="text-muted text-sm mb-2">{{ __('Active Companies') }} : <span
                                                class="text-dark">{{ $user['active_companies'] }}</span></p>
                                        <p class="text-muted text-sm mb-2">{{ __('Inactive Companies') }} : <span
                                                class="text-dark">{{ $user['inactive_companies'] }}</span></p>
                                    </div>

                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 dashboard-card">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.order.index') }}" class="text-info">
                                <div class="d-flex flex-row gap-2">
                                    <div class="theme-avtar bg-info mb-3">
                                        <i class="ti ti-trophy "></i>
                                    </div>
                                    <div class="d-flex flex-column ">
                                        <h6 class="mb-3 fw-bold">{{ __('Total Amount') }} : <span
                                                class="mb-0">{{ $user['total_amount'] }}</span>
                                        </h6>
                                        <p class="text-muted text-sm mb-2 "> {{ __('Pending Amount') }} : <span
                                                class="text-dark">{{ $user['pending_amount'] }}</span>
                                        </p>
                                        <p class="text-muted text-sm mb-2 "> {{ __('Due Amount') }} : <span
                                                class="text-dark">{{ $user['due_amount'] }}</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 dashboard-card">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.plans.index') }}" class="text-danger">
                                <div class="d-flex flex-row gap-2">
                                    <div class="theme-avtar bg-danger mb-3">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                    </div>
                                    <div class="d-flex flex-column ">
                                        <h6 class="mb-3 fw-bold">{{ __('Total Orders') }} : <span
                                                class="mb-0">{{ $user['total_orders'] }}</span></h6>
                                        <p class="text-muted text-sm mb-2">{{ __('Pending Invoices') }} : <span
                                                class="text-dark">{{ $user['pending_invoices'] }}</span></p>
                                        <p class="text-muted text-sm mb-2">{{ __('Due Invoices') }} : <span
                                                class="text-dark">{{ $user['due_invoices'] }}</span></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 dashboard-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row gap-2">
                                <div class="theme-avtar bg-secondary mb-3">
                                    <i class="ti ti-ticket"></i>
                                </div>
                                <div class="d-flex flex-column ">
                                    <h6 class="mb-3 fw-bold"><a href="{{ route('admin.plans.index') }}"
                                            class="text-secondary">{{ __('Total Tickets') }} : <span
                                                class="mb-0">{{ $user['total_ticket'] }}</span></a></h6>
                                    <p class="text-muted text-sm mb-2">{{ __('Opened Tickets') }} : <span
                                            class="text-dark">{{ $user['open_ticket'] }}</span></p>
                                    <p class="text-muted text-sm mb-2">{{ __('Closed Tickets') }} : <span
                                            class="text-dark">{{ $user['close_ticket'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="h4 font-weight-400">{{ __('Recent Order') }}</h4>
                    <h6 class="last-day-text">Last 14 Days</h6>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="chart-sales" data-color="primary" data-height="280" class="p-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
