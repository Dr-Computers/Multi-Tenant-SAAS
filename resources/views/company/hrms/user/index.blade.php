@extends('layouts.company')
@section('page-title')
    {{ __('Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="#" data-size="md" data-url="{{ route('company.hrms.users.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create New User') }}" class="btn btn-sm btn-primary me-2">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Mobile No') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $key => $user)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
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
                                            <div class="action-btn me-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-dark"
                                                    data-bs-toggle="tooltip" title="{{ __('Reset Password') }}"
                                                    data-url="{{ route('company.hrms.users.reset.form', $user->id) }}"
                                                    data-size="xl" data-ajax-popup="true"
                                                    data-original-title="{{ __('Reset Password') }}">
                                                    <span> <i class="ti ti-lock text-white"></i></span>
                                                </a>
                                            </div>
                                            <div class="action-btn me-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                    data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                    data-url="{{ route('company.hrms.users.show', $user->id) }}"
                                                    data-size="xl" data-ajax-popup="true"
                                                    data-original-title="{{ __('Edit') }}">
                                                    <span> <i class="ti ti-eye text-white"></i></span>
                                                </a>
                                            </div>
                                            <div class="action-btn me-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                    data-url="{{ route('company.hrms.users.edit', $user->id) }}"
                                                    data-size="xl" data-ajax-popup="true"
                                                    data-original-title="{{ __('Edit') }}">
                                                    <span> <i class="ti ti-pencil text-white"></i></span>
                                                </a>
                                            </div>

                                            <div class="action-btn">
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['company.hrms.users.destroy', $user->id],
                                                    'id' => 'delete-form-' . $user->id,
                                                ]) !!}
                                                <a href="#"
                                                    class="mx-4 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash text-white text-white "></i></a>

                                                {!! Form::close() !!}
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
