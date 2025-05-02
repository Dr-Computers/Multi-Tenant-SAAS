@extends('layouts.company')
@section('page-title')
    {{ __('Payments Payables') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Payments Payables') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.finance.realestate.payments.payables.create', 0) }}" class="btn btn-sm btn-primary"
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
                                    <th>{{ __('Date ') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('For/Reason ') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Bank') }}</th>
                                    <th>{{ __('Note') }}</th>

                                    <th class="text-right">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($payments) && count($payments))
                                    @foreach ($payments as $payment)
                                        <tr role="row">
                                            <td>{{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}</td>
                                            <td>{{ optional($payment->user)->name }}</td>
                                            <td>{{ ucwords(str_replace('-', ' ', $payment->for_reason)) }}</td>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->bankAccount->account_name }}</td>
                                            <td>{{ $payment->notes ?? 'N/A' }}</td>

                                            <td class="text-right ">

                                                <div class="action-btn">

                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['company.finance.realestate.payments.payables.destroy', $payment->id],
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
                                @else
                                    <tr>
                                        <td colspan="7">No data Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




@push('script-page')
@endpush
