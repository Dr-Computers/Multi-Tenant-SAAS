@extends('layouts.admin')
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
                    @can('orders listing')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order Id') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Plan Name') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Payment Type') }}</th>
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
                                            <td>{{ $order->user_name }}</td>
                                            <td>{{ $order->plan_name }}</td>
                                            <td class="fw-bolder">{{ $order->price . ' ' . $currency_symbol }}</td>
                                            <td class="fw-bold">{{ $order->payment_type }}</td>
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
                                                        @can('invoice sent to mail')
                                                            <a class="dropdown-item" data-size="md" role="button"
                                                                data-url="{{ route('admin.order.send.email', $order->id) }}"
                                                                data-ajax-popup2="true" data-bs-toggle="tooltip"
                                                                title="{{ __('Send to Email') }}">
                                                                <span> <i class="ti ti-mail text-dark"></i>
                                                                    {{ __('Send to Email') }}</span>
                                                            </a>
                                                        @endcan
                                                        @can('invoice download')
                                                            <a class="dropdown-item" role="button"
                                                                href="{{ route('admin.order.download.invoice', $order->id) }}">
                                                                <span> <i class="ti ti-download text-dark"></i>
                                                                    {{ __('Invoice Download') }}</span>
                                                            </a>
                                                        @endcan
                                                        @if ($order->payment_status === 'pending')
                                                            @can('mark as paid')
                                                                {!! Form::open([
                                                                    'method' => 'POST',
                                                                    'route' => ['admin.order.mark.as.payment', $order->id],
                                                                    'id' => 'paid-form-' . $order->id,
                                                                ]) !!}
                                                                <button type="submit" class="dropdown-item"
                                                                    onclick="return confirm('Are you sure you want to mark this as paid?')">
                                                                    <i class="ti ti-check text-dark"></i> {{ __('Mark as Paid') }}
                                                                </button>
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        @endif

                                                        @if ($order->payment_status == 'pending' && $order->is_refund == 0)
                                                            @can('delete order')
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['admin.order.destroy', $order->id],
                                                                    'id' => 'delete-form-' . $order->id,
                                                                ]) !!}
                                                                <a href="#" class="dropdown-item bs-pass-para " role="button"
                                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash text-dark "></i> {{ __('Delete') }}</a>

                                                                {!! Form::close() !!}
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- <td> --}}
                                            {{-- @if ($order->payment_status == 'pending' && $order->payment_type == 'Bank Transfer') --}}
                                            {{-- <div class="action-btn bg-warning ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="modal" data-size="lg" data-ajax-popup="true"
                                                        data-url="{{ route('banktransfer.show', [$order->id]) }}"
                                                        data-title="{{ __('Payment Status') }}" data-size="lg">
                                                        <span class="text-white"> <i class="ti ti-caret-right text-white"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Payment Status') }}"></i></span></a>
                                                </div> --}}
                                            {{-- @endif --}}
                                            @php
                                                // $user = App\Models\User::find($order->user_id);
                                            @endphp
                                            {{-- <div class="action-btn">
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['banktransfer.destroy', $order->id],
                                                    'id' => 'delete-form-' . $order->id,
                                                ]) !!}
                                                <a href="#"
                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                        class="ti ti-trash text-white text-white "></i></a>

                                                {!! Form::close() !!}
                                            </div> --}}

                                            {{-- @foreach ($userOrders as $userOrder)
                                                    @if ($user->plan == $order->plan_id && $order->order_id == $userOrder->order_id && $order->is_refund == 0)
                                                        <div class="badge bg-warning  p-2 px-3 ms-2">
                                                            <a href="{{ route('admin.order.refund', [$order->id, $order->user_id]) }}"
                                                                class="mx-3 align-items-center" data-bs-toggle="tooltip"
                                                                title="{{ __('Delete') }}"
                                                                data-original-title="{{ __('Delete') }}">
                                                                <span class ="text-white">{{ __('Refund') }}</span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
