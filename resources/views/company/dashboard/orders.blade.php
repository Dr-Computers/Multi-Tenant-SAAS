@extends('layouts.company')
@section('page-title')
    {{ __('Subscription Order') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Subscription Order') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">

                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order Id') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Plan Name') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th class="text-center">{{ __('Payment Status') }}</th>
                                        {{-- <th>{{ __('Coupon Code') }}</th> --}}
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $path = \App\Models\Utility::get_file('/uploads/bank_receipt');
                                        $admin = \App\Models\Utility::getAdminPaymentSetting();
                                        $currency_symbol = $admin['currency_symbol'] ?? 'AED';
                                    @endphp
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->order_id }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>{{ $order->plan_name }}</td>
                                            <td class="fw-bolder">{{ $order->price . ' ' . $currency_symbol }}</td>
                                            <td class="text-center">
                                                @if ($order->payment_status == 'completed')
                                                    <span class="badge bg-success py-1 px-2 rounded">
                                                        {{ ucfirst($order->payment_status) }}</span>
                                                @elseif ($order->payment_status == 'pending')
                                                    <span class="badge bg-warning py-1 px-2 rounded">
                                                        {{ ucfirst($order->payment_status) }}</span>
                                                @else
                                                    <span class="badge bg-danger py-1 px-2 rounded">
                                                        {{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </td>

                                            {{-- <td>{{ !empty($order->total_coupon_used) ? (!empty($order->total_coupon_used->coupon_detail) ? $order->total_coupon_used->coupon_detail->code : '-') : '-' }}</td> --}}
                                            <td>
                                                <div class="btn-group card-option">

                                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                       
                                                        {{-- @can('invoice download') --}}
                                                            <a class="dropdown-item" role="button"
                                                                href="{{ route('company.subcriptions.order.download.invoice', $order->id) }}">
                                                                <span> <i class="ti ti-download text-dark"></i>
                                                                    {{ __('Invoice Download') }}</span>
                                                            </a>
                                                        {{-- @endcan --}}
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
             
                </div>
            </div>
        </div>
    </div>
@endsection
