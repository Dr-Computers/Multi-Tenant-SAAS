@extends('layouts.company')
@section('page-title')
    {{ __('Maintainer') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Maintainers Report') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">

        <form method="GET" action="{{ route('company.report.maintainers.index') }}">
            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; margin: 20px;">

                <label for="start_month" style="font-size: 14px; font-weight: bold;">Month Of:</label>
                <input type="month" id="start_month" name="start_month"
                    value="{{ request('start_month') ? request('start_month') : '' }}"
                    style="padding: 5px; font-size: 14px;">

                <label for="end_month" style="font-size: 14px; font-weight: bold;">To:</label>
                <input type="month" id="end_month" name="end_month"
                    value="{{ request('end_month') ? request('end_month') : '' }}" style="padding: 5px; font-size: 14px;">

                <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                    {{ __('Filter') }}
                </button>

                <a href="{{ route('company.report.maintainers.index') }}" class="btn btn-secondary btn-sm">
                    {{ __('Clear') }}
                </a>
            </div>
        </form>

        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Profile Picture') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Created Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($maintainers))
                                    @foreach ($maintainers as $maintainer)
                                        <tr class="table-row">
                                            <td class="text-center">
                                                <img src="{{ asset('storage/' . $maintainer->avatar_url) }}"
                                                    class="h-10 w-auto border mb-1 img-fluid rounded-circle">
                                            </td>
                                            <td>
                                                {{ ucfirst($maintainer->name) }}
                                            </td>
                                            <td>{{ $maintainer->email }}</td>
                                            <td>{{ $maintainer->mobile }}
                                            </td>
                                            <td>{{ !empty($maintainer->types) ? $maintainer->types->title : '-' }}</td>
                                            <td>{{ dateFormat($maintainer->created_at) }}</td>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{-- <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {!! $maintainers->onEachSide(2)->links() !!}
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
