@extends('layouts.company')
@section('page-title')
    {{ __('Manage Roles') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Role') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">
        @can('create role')
            <a href="#" data-size="xl" data-url="{{ route('company.hrms.roles.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    @can('role listing')
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Role') }} </th>
                                    <th>{{ __('Permissions') }} </th>
                                    <th width="150">{{ __('Action') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr class="font-style">
                                        <td class="Role text-capitalize">{{ $role->name }}</td>
                                        <td class="Permission">
                                            @for ($j = 0; $j < count($role->permissions()->pluck('name')); $j++)
                                                <span
                                                    class="badge rounded-pill bg-primary text-capitalize">{{ $role->permissions()->pluck('name')[$j] }}</span>
                                            @endfor
                                        </td>
                                        <td class="Action">
                                            <span>
                                                <div class="action-btn me-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                        data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                        data-url="{{ route('company.hrms.roles.show', $role->id) }}"
                                                        data-size="xl" data-ajax-popup="true"
                                                        data-original-title="{{ __('Edit') }}">
                                                        <span> <i class="ti ti-eye text-white"></i></span>
                                                    </a>
                                                </div>
                                                @can('edit role')
                                                    @if ($role->is_editable)
                                                        <div class="action-btn me-2">
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-url="{{ route('company.hrms.roles.edit', $role->id) }}"
                                                                data-size="xl" data-ajax-popup="true"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <span> <i class="ti ti-pencil text-white"></i></span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endcan

                                                @can('delete role')
                                                    @if ($role->is_deletable)
                                                        <div class="action-btn">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['company.hrms.roles.destroy', $role->id],
                                                                'id' => 'delete-form-' . $role->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash text-white text-white"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endif
                                                @endcan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
