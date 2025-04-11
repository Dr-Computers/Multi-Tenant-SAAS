@extends('layouts.company')

@section('page-title', 'maintainer Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.realestate.maintainers.index') }}">Owners</a></li>
    <li class="breadcrumb-item active">{{ $maintainer->name }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a  href="{{ route('company.realestate.maintainers.index') }}"  title="{{ __('Back to maintainers') }}" class="btn btn-sm btn-primary me-2">
            <i class="ti ti-arrow-left"></i>
        </a>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                   

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs" id="maintainerTabs">
                        @php
                            $tab = request()->get('tab', 'overview');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'overview' ? 'active' : '' }}"
                                href="{{ route('company.realestate.maintainers.show', $maintainer->id) }}?tab=overview">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'properties' ? 'active' : '' }}"
                                href="{{ route('company.realestate.maintainers.show', $maintainer->id) }}?tab=properties">Properties</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'requests' ? 'active' : '' }}"
                                href="{{ route('company.realestate.maintainers.show', $maintainer->id) }}?tab=requests">Requests for
                                Approval</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'settlements' ? 'active' : '' }}"
                                href="{{ route('company.realestate.maintainers.show', $maintainer->id) }}?tab=settlements">Settlement</a>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content p-4 border border-top-0 rounded-bottom">
                        @if ($tab == 'overview')
                            @include('company.realestate.maintainers.partials._overview', ['maintainer' => $maintainer])
                        @elseif($tab == 'properties')
                            @include('company.realestate.maintainers.partials._properties', ['maintainer' => $maintainer])
                        @elseif($tab == 'requests')
                            @include('company.realestate.maintainers.partials._requests', ['maintainer' => $maintainer])
                        @elseif($tab == 'settlements')
                            @include('company.realestate.maintainers.partials._settlements', [
                                'maintainer' => $maintainer,
                            ])
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
