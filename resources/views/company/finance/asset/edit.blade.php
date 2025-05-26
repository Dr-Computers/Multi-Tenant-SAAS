@extends('layouts.app')
@section('page-title')
    {{ __('Invoice') }}
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
    cursor: not-allowed; /* Optional, to show it's not editable */
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
            <a href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Edit') }}</a>
        </li>
    </ul>
@endsection

@section('content')


    {{ Form::model($asset, ['route' => ['assets.update', $asset->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="info-group">
                        <dv class="row">
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('name', __('Asset Name/Description'), ['class' => 'form-label']) }}
                                <span class="text-danger">*</span>
                                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Asset Name')]) }}
                            </div>
                            
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('type', __('Asset Type'), ['class' => 'form-label', 'escape' => false]) }}
                                <span class="text-danger">*</span>
                               {{ Form::select('type', [
                                    'fixed_asset' => __('Fixed Asset'), 
                                    'current_asset' => __('Current Asset'), 
                                    'bank' => __('Bank')
                                ], null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Asset Type')]) }}
                            </div>
                            
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('property_id', __('Property ID'), ['class' => 'form-label']) }}
                                {{ Form::select('property_id', $property, null, ['class' => 'form-control hidesearch', 'placeholder' => __('Select Property')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('location', __('Location'), ['class' => 'form-label']) }}
                                {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => __('Enter Location')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label']) }}
                                {{ Form::date('purchase_date', null, ['class' => 'form-control']) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('purchase_price', __('Purchase Price'), ['class' => 'form-label']) }}
                                {{ Form::number('purchase_price', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Purchase Price')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('vendor_name', __('Vendor Name'), ['class' => 'form-label']) }}
                                {{ Form::text('vendor_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Vendor Name')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('initial_value', __('Initial Value '), ['class' => 'form-label']) }}
                                <span class="text-danger">*</span>
                                {{ Form::number('initial_value', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01', 'placeholder' => __('Enter Initial Value')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('current_market_value', __('Current Market Value'), ['class' => 'form-label']) }}
                                {{ Form::number('current_market_value', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Current Market Value')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('accumulated_depreciation', __('Accumulated Depreciation'), ['class' => 'form-label']) }}
                                {{ Form::number('accumulated_depreciation', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Accumulated Depreciation')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('owner_name', __('Owner Name'), ['class' => 'form-label']) }}
                                {{ Form::text('owner_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Owner Name')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('title_deed_number', __('Title Deed Number'), ['class' => 'form-label']) }}
                                {{ Form::text('title_deed_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Title Deed Number')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('condition', __('Asset Condition'), ['class' => 'form-label']) }}
                                {{ Form::text('condition', null, ['class' => 'form-control', 'placeholder' => __('Enter Asset Condition')]) }}
                            </div>
    
                            <div class="form-group col-md-6 col-lg-4">
                                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                {{ Form::select('status', ['active' => 'Active', 'sold' => 'Sold', 'under_maintenance' => 'Under Maintenance'], null, ['class' => 'form-control hidesearch', 'placeholder' => __('Select Status')]) }}
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
@endsection
