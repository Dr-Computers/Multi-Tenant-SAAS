@extends('layouts.company')
@section('page-title')
    {{ __('Other Payments') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Pyaments') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.realestate.other.payments.create', 0) }}" class="btn btn-sm btn-primary"
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
                                    <th>{{ __('Payment Date ') }}</th>
                                    <th>{{ __('Payment For ') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Tenant') }}</th>
                                    <th>{{ __('Reference No') }}</th>
                                    <th>{{ __('Account') }}</th>

                                    <th style="width: 30%;">{{ __('Note') }}</th>

                                    <th class="text-right">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr role="row">



                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                        <td>{{ ucwords(str_replace('-', ' ', $payment->paymentFor->title)) }}</td>

                                        <td>{{ $payment->amount }}</td>
                                        @php $tenant = App\Models\User::find($payment->tenant_id)  @endphp

                                        <td>{{ !empty($tenant->name) ? $tenant->name : 'N/A' }}</td>


                                        <td>{{ $payment->reference_no ?? 'N/A' }}</td>

                                        <td>{{ $payment->account->holder_name ?? 'N/A' }}</td>



                                        <td style="width: 30%;">{{ $payment->notes ?? 'N/A' }}</td>


                                        <td>


                                            <div class="action-btn">
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => $payment->invoice_id
                                                        ? ['company.finance.realestate.other.payments.destroy', $payment->id, $payment->invoice_id]
                                                        : ['company.finance.realestate.other.payments.destroy', $payment->id, 0], // Pass both parameters for payment.destroy
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
