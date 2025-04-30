@extends('layouts.company')
@section('page-title')
    {{ __('Invoice Payments') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Pyaments') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.realestate.invoice.payments.create', 0) }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip" title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection


<style>
    /* Force badge styles (add to your CSS file) */
    /* ----- Custom Badge Styles (Replace Bootstrap) ----- */
    .statusbadge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        color: #fff !important;
        /* White text */
    }

    /* Color Variants */
    .statusbadge-primary {
        background-color: #0d6efd;
        /* Blue */
    }

    .statusbadge-success {
        background-color: #198754;
        /* Green */
    }

    .statusbadge-warning {
        background-color: #ffc107;
        /* Yellow */
        color: #000 !important;
        /* Black text for better contrast */
    }
</style>

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Payment For') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Invoice') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Note') }}</th>
                                    @if (Gate::check('edit invoice payment') || Gate::check('delete invoice payment') || Gate::check('show invoice payment'))
                                        <th class="text-right">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr role="row">
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                        <td>{{ ucwords(str_replace('-', ' ', $payment->payment_for)) }}</td>
                                        <td>{{ priceFormat($payment->amount) }}</td>
                                        <td>
                                            @if ($payment->payment_type == 'cash')
                                                <span class="badge badge-success">Cash ({{ str_replace('_', ' ', $payment->payment_for) }})</span>
                                            @elseif($payment->payment_type == 'cheque')
                                                <span class="badge badge-info">Cheque ({{ str_replace('_', ' ', $payment->payment_for) }})</span>
                                            @elseif($payment->payment_type == 'bank_transfer')
                                                <span class="badge badge-secondary">Bank Transfer ({{ str_replace('_', ' ', $payment->payment_for) }})</span>
                                            @else
                                                <span class="badge badge-light text-dark">{{ $payment->payment_type ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ optional(optional($payment->invoice)->properties)->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->invoice_id ? invoicePrefix() . $payment->invoice->invoice_id : 'N/A' }}</td>
                                        <td>{{ optional(optional($payment->invoice)->units)->name ?? 'N/A' }}</td>
                                        <td>{{ optional(optional(optional($payment->invoice)->units)->tenants())->user->first_name ?? 'N/A' }}</td>
                                        <td>{{ $payment->notes ?? 'N/A' }}</td>
                        
                                        @if (Gate::check('edit invoice') || Gate::check('delete invoice payment') || Gate::check('show invoice'))
                                            <td class="text-right">
                                                <div class="cart-action">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => $payment->invoice_id
                                                            ? ['invoice.payment.destroy', $payment->id, $payment->invoice_id]
                                                            : ['invoice.payment.destroy', $payment->id, 0],
                                                    ]) !!}
                                                    @can('edit invoice')
                                                        <a class="text-success" href="{{ route('payment.edit', $payment->id) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
                                                            <i data-feather="edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete invoice payment')
                                                        <a class="text-danger confirm_dialog" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}" href="#">
                                                            <i data-feather="trash-2"></i>
                                                        </a>
                                                    @endcan
                                                    @can('edit invoice')
                                                        <a class="text-primary" href="{{ route('payment.download', $payment->id) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Download Receipt') }}">
                                                            <i data-feather="download"></i>
                                                        </a>
                                                    @endcan
                                                    {!! Form::close() !!}
                                                </div>
                                            </td>
                                        @endif
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




@push('script-page')
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);


            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush
