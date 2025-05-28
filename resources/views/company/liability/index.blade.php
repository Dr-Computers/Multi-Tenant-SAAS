@extends('layouts.company')
@section('page-title')
    {{ __('Liabilities') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Liabilities') }}</a>
        </li>
    </ul>
@endsection
@section('action-btn')
    @can('create a liability')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('company.liabilities.create') }}"> <i
                class="ti-plus ti"></i>{{ __('Create Liability') }}</a>
    @endcan
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @can('liabilities listing')
                        <table class="display table table-bordered table-striped cell-border">
                            <thead>
                                <tr>
                                    <th>{{ __('Liability Name') }}</th>
                                    <th>{{ __('Liability Type') }}</th>
                                    <th>{{ __('Property ID') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    {{-- @if (Gate::check('edit liability') || Gate::check('delete liability')) --}}
                                    <th class="text-right">{{ __('Action') }}</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($liabilities as $liability)
                                    <tr>
                                        <td>{{ $liability->name }}</td>
                                        <td>{{ $liability->type }}</td>
                                        <td>{{ $liability->property ? $liability->property->name : 'N/A' }}</td>
                                        <td>{{ priceFormat($liability->amount) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($liability->due_date)->format('Y-m-d') }}</td>
                                        <td>{{ ucfirst($liability->status) }}</td>


                                        <td class="text-right">
                                            <div class="cart-action">

                                                @can('edit a asset')
                                                    <a class="text-success"
                                                        href="{{ route('company.liabilities.edit', $liability->id) }}"
                                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}"> <i
                                                            data-feather="edit"></i></a>
                                                @endcan
                                                @can('delete a liability')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['company.liabilities.destroy', $liability->id]]) !!}

                                                    <a class=" text-danger bs-pass-para" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <i
                                                            data-feather="trash-2"></i></a>

                                                    {!! Form::close() !!}
                                                @endcan
                                            </div>

                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">{{ __('No liabilities found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                        <div class="d-flex justify-content-end" style="margin-top: 10px;">
                            {!! $liabilities->onEachSide(2)->links() !!}
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
