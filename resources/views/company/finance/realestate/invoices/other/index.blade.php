@extends('layouts.company')
@section('page-title')
    {{ __('Other Invoices') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoices') }}</li>
@endsection
@section('action-btn')
    @can('create a invoice')
        <div class="d-flex">
            <a href="{{ route('company.finance.realestate.invoice-other.create', 0) }}" class="btn btn-sm btn-primary"
                data-bs-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
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
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ _('Due Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>



                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr role="row">
                                        <td>{{ invoicePrefixOther() . $invoice->invoice_id }} </td>


                                        <td>{{ $invoice->tenant->name }} </td>




                                        <td>{{ \Carbon\Carbon::parse($invoice->end_date)->format('d/m/Y') }} </td>
                                        <td>{{ $invoice->getInvoiceSubTotalAmount() }}</td>

                                        <td>{{ $invoice->getInvoiceDueAmount() }} </td>

                                        <td>
                                            @if ($invoice->status == 'open')
                                                <span
                                                    class="statusbadge statusbadge-primary">{{ \App\Models\RealestateInvoice::$status[$invoice->status] }}</span>
                                            @elseif($invoice->status == 'paid')
                                                <span
                                                    class="statusbadge statusbadge-success">{{ \App\Models\RealestateInvoice::$status[$invoice->status] }}</span>
                                            @elseif($invoice->status == 'partial_paid')
                                                <span
                                                    class="statusbadge statusbadge-warning">{{ \App\Models\RealestateInvoice::$status[$invoice->status] }}</span>
                                            @endif
                                        </td>


                                        <td>
                                            <div class="action-btn me-2">

                                                <a href="{{ route('company.finance.realestate.invoice-other.show', $invoice->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                    data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                    data-original-title="{{ __('View') }}">
                                                    <span><i class="ti ti-eye text-white"></i></span>
                                                </a>

                                            </div>

                                            <div class="action-btn me-2">

                                                <a href="{{ route('company.finance.realestate.invoice-other.edit', $invoice->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                    data-original-title="{{ __('Edit') }}">
                                                    <span><i class="ti ti-pencil text-white"></i></span>
                                                </a>

                                            </div>

                                            <div class="action-btn">

                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['company.finance.realestate.invoice-other.destroy', $invoice->id],
                                                    'id' => 'delete-form-' . $invoice->id,
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
