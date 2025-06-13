@extends('layouts.owner')
@section('page-title')
    {{ __('Invoices') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection
@section('action-btn')
   
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
                    @can('invoice listing')
                        <div class="table-responsive">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>{{ __('Invoice') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Sub Total') }}</th>
                                        <th>{{ _('Tax Amount') }}</th>
                                        <th>{{ _('Total Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr role="row">
                                            <td class="text-md">
                                                {{ (optional($invoice->properties)->invoice_prefix ?: invoicePrefix()) . $invoice->invoice_id }}
                                            </td>
                                            <td class="text-md">{{ dateFormat($invoice->end_date) }}</td>

                                            <td class="text-md">{{ \Auth::user()->priceFormat($invoice->sub_total) }}</td>
                                            <td class="text-md">{{ \Auth::user()->priceFormat($invoice->total_tax) }}</td>
                                            <td class="text-md">{{ \Auth::user()->priceFormat($invoice->grand_total) }} </td>
                                            <td>
                                                @if ($invoice->status == 'open')
                                                    <span
                                                        class="statusbadge statusbadge-primary text-light">{{ \App\Models\RealestateInvoice::$status[$invoice->status] }}</span>
                                                @elseif($invoice->status == 'paid')
                                                    <span
                                                        class="statusbadge statusbadge-success text-light">{{ \App\Models\RealestateInvoice::$status[$invoice->status] }}</span>
                                                @elseif($invoice->status == 'partial_paid')
                                                    <span
                                                        class="statusbadge statusbadge-warning text-light">{{ \App\Models\RealestateInvoice::$status[$invoice->status] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                               

                                                <div class="action-btn me-2">
                                                    <a href="{{ route('owner.finance.realestate.invoices.show', $invoice->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info">
                                                        <span><i class="ti ti-download text-white"></i></span>
                                                    </a>
                                                </div>

                                            </td>



                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h6>No Invoices found..!</h6>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcan
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
