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

                                    <th class="text-right">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr role="row">
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $payment->payment_for)) }}</td>
                                        <td>{{ $payment->amount }}</td>

                                        <td>
                                            @if ($payment->payment_type == 'cash')
                                                <span class="badge bg-success">Cash
                                                    ({{ str_replace('_', ' ', $payment->payment_for) }})</span>
                                            @elseif($payment->payment_type == 'cheque')
                                                <span class="badge bg-info">Cheque
                                                    ({{ str_replace('_', ' ', $payment->payment_for) }})</span>
                                            @elseif($payment->payment_type == 'bank_transfer')
                                                <span class="badge bg-secondary">Bank Transfer
                                                    ({{ str_replace('_', ' ', $payment->payment_for) }})</span>
                                            @else
                                                <span
                                                    class="badge bg-light text-dark">{{ $payment->payment_type ?? 'N/A' }}</span>
                                            @endif
                                        </td>

                                        <td>{{ optional(optional($payment->invoice)->properties)->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->invoice_id ? invoicePrefix() . $payment->invoice->invoice_id : 'N/A' }}
                                        </td>
                                        <td>{{ optional(optional($payment->invoice)->units)->name ?? 'N/A' }}</td>
                                        
                                        <td>{{ optional(optional(optional($payment->invoice)->units)->tenants())->name ?? 'N/A' }}
                                        </td>
                                        <td>{{ $payment->notes ?? 'N/A' }}</td>


                                        {{-- <td class="text-right">
                                                <div class="cart-action">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => $payment->invoice_id
                                                            ? ['invoice.payment.destroy', $payment->id, $payment->invoice_id]
                                                            : ['invoice.payment.destroy', $payment->id, 0],
                                                    ]) !!}
                                                   
                                                        <a class="text-success" href="{{ route('payment.edit', $payment->id) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
                                                            <i data-feather="edit"></i>
                                                        </a>
                                                   
                                                   
                                                        <a class="text-danger confirm_dialog" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}" href="#">
                                                            <i data-feather="trash-2"></i>
                                                        </a>
                                                   
                                                   
                                                        <a class="text-primary" href="" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Download Receipt') }}">
                                                            <i data-feather="download"></i>
                                                        </a>
                                                  
                                                    {!! Form::close() !!}
                                                </div>
                                            </td> --}}
                                        <td>
                                            <div class="action-btn me-2">

                                                <a href="{{ route('company.finance.realestate.invoices.show', $payment->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                    data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                    data-original-title="{{ __('Download') }}">
                                                    <span><i class="ti ti-download text-white"></i></span>
                                                </a>
                                            </div>

                                            <div class="action-btn me-2">

                                                <a href="{{ route('company.finance.realestate.invoice.payments.edit', $payment->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                    data-original-title="{{ __('Edit') }}">
                                                    <span><i class="ti ti-pencil text-white"></i></span>
                                                </a>

                                            </div>

                                            <div class="action-btn">

                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['company.finance.realestate.invoices.destroy', $payment->id],
                                                    'id' => 'delete-form-' . $payment->id,
                                                ]) !!}
                                                <a href="#"
                                                    class="mx-4 btn btn-sm align-items-center bs-pass-para bg-danger"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}

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
