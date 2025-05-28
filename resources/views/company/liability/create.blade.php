@extends('layouts.company')
@section('page-title')
    {{ __('Asset') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('company.assets.index') }}">{{ __('Liabilities') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Create') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    @can('create a liability')
        {{ Form::open(['url' => route('company.liabilities.store'), 'method' => 'post', 'id' => 'liability_form', 'enctype' => 'multipart/form-data', 'files' => true]) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="info-group">
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('liability_name', __('Liability Name/Description '), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Liability Name')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('liability_type', __('Liability Type '), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::text('type', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Liability Type')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('property_id', __('Property ID'), ['class' => 'form-label']) }}
                                    {{ Form::select('property_id', $properties, null, ['class' => 'form-control hidesearch', 'placeholder' => __('Select Property')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('amount', __('Amount '), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::number('amount', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01', 'placeholder' => __('Enter Amount')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('due_date', __('Due Date '), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::date('due_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('vendor_name', __('Vendor Name'), ['class' => 'form-label']) }}
                                    {{ Form::text('vendor_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Vendor Name')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('interest_rate', __('Interest Rate (%)'), ['class' => 'form-label']) }}
                                    {{ Form::number('interest_rate', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Interest Rate')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('payment_terms', __('Payment Terms'), ['class' => 'form-label']) }}
                                    {{ Form::text('payment_terms', null, ['class' => 'form-control', 'placeholder' => __('Enter Payment Terms')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('status', __('Status '), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::select('status', ['active' => 'Active', 'paid' => 'Paid', 'overdue' => 'Overdue'], null, ['class' => 'form-control hidesearch', 'required' => 'required', 'placeholder' => __('Select Status')]) }}
                                </div>

                                <div class="form-group col-md-6 col-lg-4">
                                    {{ Form::label('notes', __('Additional Notes'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Additional Notes')]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="group-button text-end">
                    {{ Form::submit(__('Create Liability'), ['class' => 'btn btn-primary btn-rounded']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    @endcan
@endsection
