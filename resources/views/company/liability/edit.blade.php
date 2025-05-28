@extends('layouts.company')
@section('page-title')
    {{ __('Liabilities') }}
@endsection
<style>
    .text-danger {
        color: red;
        /* Set the color of the asterisk to red */
        margin-left: 0.25em;
        /* Optional: adds space between the label and asterisk */
        font-weight: bold;
        /* Make the asterisk bold */
        font-size: 1.2em;
        /* Increase the size of the asterisk */
    }
</style>
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>



    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({

                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({

                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                    $(this).slideDown();

                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                    }
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }
        }
    </script>
@endpush
<style>
    .readonly-field {
        background-color: #f0f0f0;
        cursor: not-allowed;
        /* Optional, to show it's not editable */
    }
</style>

@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('company.liabilities.index') }}">{{ __('Liability') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Edit') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    @can('edit a asset')
        {{ Form::model($liability, ['route' => ['company.liabilities.update', $liability->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="info-group">
                            <dv class="row">
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
                                    {{ Form::select('property_id', $property, null, ['class' => 'form-control hidesearch', 'placeholder' => __('Select Property')]) }}
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
                {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'invoice-submit']) }}
            </div>
        </div>
        </div>
        {{ Form::close() }}
    @endcan
@endsection
