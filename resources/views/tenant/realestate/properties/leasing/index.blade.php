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
                    {{-- Tabs --}}
                    <ul class="nav nav-tabs" id="unitTabs">
                        @php
                            $tab = request()->get('tab', 'unleashed');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'unleashed' ? 'active' : '' }}"
                                href="{{ route('owner.realestate.properties.lease.index') }}?tab=unleashed">Unleashed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'leasing' ? 'active' : '' }}"
                                href="{{ route('owner.realestate.properties.lease.index') }}?tab=leasing">Leasing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'cancelled' ? 'active' : '' }}"
                                href="{{ route('owner.realestate.properties.lease.index') }}?tab=cancelled">Leasing
                                Cancelled</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'in-hold' ? 'active' : '' }}"
                                href="{{ route('owner.realestate.properties.lease.index') }}?tab=in-hold">In hold</a>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content p-4 border border-top-0 rounded-bottom">
                        @if ($tab == 'unleashed')
                            @include('owner.realestate.properties.leasing.partials._unleashed', [
                                'leasing_units' => $leasing_units,
                            ])
                        @elseif($tab == 'leasing')
                            @include('owner.realestate.properties.leasing.partials._leasing', [
                                'unleashed_units' => $unleashed_units,
                            ])
                        @elseif($tab == 'cancelled')
                            @include('owner.realestate.properties.leasing.partials._cancelled', [
                                'leasing_cancelled_units' => $leasing_cancelled_units,
                            ])
                        @elseif($tab == 'in-hold')
                            @include('owner.realestate.properties.leasing.partials._in_hold', [
                                'in_hold_units' => $in_hold_units,
                            ])
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
