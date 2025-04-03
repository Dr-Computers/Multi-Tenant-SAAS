@extends('layouts.company')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@push('theme-script')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
@endpush

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
               Company Dashboard
            </div>
        </div>

        <div class="col-xxl-12">

        </div>
    </div>
@endsection
