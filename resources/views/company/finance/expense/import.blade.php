@extends('layouts.company')
@section('page-title')
    {{ __('Tenants') }}
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
            <a href="{{ route('expense.index') }}">{{ __('Expense') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Import') }}</a>
        </li>
    </ul>
@endsection


@section('content')
{{ Form::open(['url' => route('expense.import'), 'method' => 'post', 'id' => 'invoice_form', 'enctype' => 'multipart/form-data', 'files' => true]) }}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="info-group">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-4">
                            {{ Form::label('file', __('Choose Excel File'), ['class' => 'form-label']) }}
                            {{ Form::file('file', ['class' => 'form-control']) }}  <!-- Changed 'import' to 'file' -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="hidden-check-details"></div>
    <div class="col-lg-12">
        <div class="group-button text-end">
            {{ Form::submit(__('Import'), ['class' => 'btn btn-primary btn-rounded', 'id' => 'expense-submit']) }}
        </div>
    </div>
</div>
{{ Form::close() }}

@endsection
