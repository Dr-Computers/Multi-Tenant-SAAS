@extends('layouts.admin')
@section('page-title')
    {{ __('Permissions Management') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Permissions Management') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        @can('create permission')
            <a href="#" data-size="md" data-url="{{ route('admin.permissions.create') }}" data-ajax-popup2="true"
                data-bs-toggle="tooltip" title="{{ __('Import Permissions') }}" class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i> {{ __('Import Permissions') }}
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    @can('permission listing')
                        <div class="table-responsive">
                            @foreach ($permissions->groupBy('section') as $section => $group)
                                <div class="card mb-4">
                                    <div class="card-header bg-info text-white">
                                        <strong class="text-light">{{ $section ?? 'Others' }}</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-center">Admin</th>
                                                    <th class="text-center">Company</th>
                                                    <th class="text-center">Owner</th>
                                                    <th class="text-center">Customer</th>
                                                    <th class="text-center">Tenant</th>
                                                    <th class="text-center">Agent</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($group as $permission)
                                                    <tr id="row-{{ $permission->id }}">
                                                        <td class="text-capitalize">{{ $permission->name }}</td>
                                                        @if (request('edit_id') == $permission->id)
                                                            <form
                                                                action="{{ route('admin.permissions.update', $permission->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                @foreach (['is_admin', 'is_company', 'is_owner', 'is_customer', 'is_tenant', 'is_agent'] as $field)
                                                                    <td>
                                                                        <select name="{{ $field }}"
                                                                            class="form-select form-select-sm">
                                                                            <option value="1"
                                                                                {{ $permission->$field ? 'selected' : '' }}>
                                                                                Yes</option>
                                                                            <option value="0"
                                                                                {{ !$permission->$field ? 'selected' : '' }}>
                                                                                No</option>
                                                                        </select>
                                                                    </td>
                                                                @endforeach
                                                                <td>
                                                                    <button class="btn btn-sm btn-success"
                                                                        type="submit">Save</button>
                                                                    <a href="{{ route('admin.permissions.index') }}"
                                                                        class="btn btn-sm btn-secondary">Cancel</a>
                                                                </td>
                                                            </form>
                                                        @else
                                                            <td class="text-center">{{ $permission->is_admin ? '✅' : '❌' }}
                                                            </td>
                                                            <td class="text-center">{{ $permission->is_company ? '✅' : '❌' }}
                                                            </td>
                                                            <td class="text-center">{{ $permission->is_owner ? '✅' : '❌' }}
                                                            </td>
                                                            <td class="text-center">{{ $permission->is_customer ? '✅' : '❌' }}
                                                            </td>
                                                            <td class="text-center">{{ $permission->is_tenant ? '✅' : '❌' }}
                                                            </td>
                                                            <td class="text-center">{{ $permission->is_agent ? '✅' : '❌' }}
                                                            </td>
                                                            <td>
                                                                @can('edit permission')
                                                                    <a href="{{ route('admin.permissions.index', ['edit_id' => $permission->id]) }}"
                                                                        class="btn btn-sm btn-primary">Edit</a>
                                                                @endcan
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
