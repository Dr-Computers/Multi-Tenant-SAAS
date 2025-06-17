@extends('layouts.owner')

@section('page-title', 'Owner Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('owner.realestate.properties.index') }}">Properties</a></li>
    <li class="breadcrumb-item">Leasing Properties</li>
@endsection
@section('action-btn')

@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @include('tenant.realestate.properties.leasing.partials._unleashed', [
                        'leasing_units' => $leasing_units,
                    ])
                </div>
            </div>

        </div>
    </div>
@endsection
