@extends('layouts.app')
@section('page-title')
    {{__('Invoice')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Liabilities')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @can('create liability')
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('liabilities.create') }}"> <i
                class="ti-plus mr-5"></i>{{__('Create Liability')}}</a>
    @endcan
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display table table-bordered table-striped cell-border">
                        <thead>
                            <tr>
                                <th>{{ __('Liability Name') }}</th>
                                <th>{{ __('Liability Type') }}</th>
                                <th>{{ __('Property ID') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if(Gate::check('edit liability') || Gate::check('delete liability') )
                                <th class="text-right">{{__('Action')}}</th>
                            @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($liabilities as $liability)
                                <tr>
                                    <td>{{ $liability->name }}</td>
                                    <td>{{ $liability->type }}</td>
                                    <td>{{ $liability->property ?$liability->property->name : 'N/A'  }}</td>
                                    <td>{{ priceFormat($liability->amount) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($liability->due_date)->format('Y-m-d') }}</td>
                                    <td>{{ ucfirst($liability->status) }}</td>
                                 
                                       
                                        @if(Gate::check('edit liability') || Gate::check('delete liability') )
                                        <td class="text-right">
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['liabilities.destroy', $liability->id]]) !!}
                                              
                                                @can('edit asset')
                                                <a class="text-success" href="{{ route('liabilities.edit',$liability->id) }}"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @can('delete liability')
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                            @endcan
                                                {!! Form::close() !!}
                                            </div>
    
                                        </td>
                                    @endif
                                    
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
                </div>
            </div>
        </div>
    </div>
@endsection

