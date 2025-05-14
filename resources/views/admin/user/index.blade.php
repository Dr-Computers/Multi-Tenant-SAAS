@extends('layouts.admin')
@section('page-title')
    {{ __('Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        @can('create staff user')
            <a href="#" data-size="md" data-url="{{ route('admin.users.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New User') }}" class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        @can('staff user listing')
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body table-bUsers-style">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Users Id') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Mobile No') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        @if (\Auth::user()->type == 'super admin')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>{{ $user->getRoleNames()->first() }}</td>
                                            <td>
                                                @if ($user->is_enable_login == '1')
                                                    <i class="badge bg-success p-2 px-3 rounded"></i>
                                                    {{ ucfirst('Enabled') }}
                                                @else
                                                    <i class="badge bg-danger p-2 px-3 rounded"></i>
                                                    {{ ucfirst('Disabled') }}
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group card-option">

                                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @can('edit staff user')
                                                            <a href="#" class="dropdown-item" data-bs-toggle="tooltip"
                                                                title="{{ __('Edit') }}"
                                                                data-url="{{ route('admin.users.edit', $user->id) }}"
                                                                data-size="xl" data-ajax-popup="true"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <span> <i
                                                                        class="ti ti-pencil text-dark"></i>{{ __('Edit') }}</span>
                                                            </a>
                                                        @endcan
                                                        @can('delete staff user')
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['admin.users.destroy', $user->id],
                                                                'id' => 'delete-form-' . $user->id,
                                                            ]) !!}
                                                            <a href="#" class="bs-pass-para dropdown-item"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash text-dark "></i>{{ __('Delete') }}</a>
                                                            {!! Form::close() !!}
                                                        @endcan
                                                    </div>
                                                </div>


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h6>No users found..!</h6>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection


@push('script-page')
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush
