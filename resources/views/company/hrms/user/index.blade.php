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
        @can('create staff user')
            <button href="#" data-size="md" data-url="{{ route('company.hrms.users.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New User') }}" class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i> {{ __('Create New User') }}
            </button>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    @can('staff user listing')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Mobile No') }}</th>
                                        <th class="text-center">{{ __('Role') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users ?? [] as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('company.hrms.users.show', $user->id) }}">
                                                    <img src="{{ asset('storage/' . $user->avatar_url) }}"
                                                        class="h-10 w-auto border mb-1 img-fluid rounded-circle">
                                                    <span class="mt-1 text-capitalize small text-dark fw-bold truncate"
                                                        title="{{ $user->name }}">{{ $user->name }}</span>
                                                </a>
                                            </td>
                                            <td><a class="text-dark truncate" title="{{ $user->email }}"
                                                    href="mailto:{{ $user->email }}"> {{ $user->email }}</a></td>
                                            <td><a class="text-dark truncate" title="{{ $user->mobile }}"
                                                    href="tel:{{ $user->mobile }}">{{ $user->mobile }}</a< /td>
                                            <td class="text-center">
                                                <span class="fw-bold text-primary">{{ $user->getRoleNames()->first() }}</span>
                                            </td>
                                            <td>
                                                @if ($user->is_active == 1)
                                                    <span class="badge bg-success   p-1 px-2 rounded">
                                                        {{ ucfirst('Enabled') }}</span>
                                                @else
                                                    <span class="badge bg-danger p-1 px-2 rounded">
                                                        {{ ucfirst('Disabled') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @can('edit staff user')
                                                    <div class="action-btn me-2">
                                                        <button href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bg-dark"
                                                            data-bs-toggle="tooltip" title="{{ __('Reset Password') }}"
                                                            data-url="{{ route('company.hrms.users.reset.form', $user->id) }}"
                                                            data-size="xl" data-ajax-popup="true"
                                                            data-original-title="{{ __('Reset Password') }}">
                                                            <span> <i class="ti ti-lock text-white"></i></span>
                                                        </button>
                                                    </div>
                                                @endcan
                                                @can('staff user details')
                                                    <div class="action-btn me-2">
                                                        <a class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                            href="{{ route('company.hrms.users.show', $user->id) }}">
                                                            <span> <i class="ti ti-eye text-white"></i></span>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('edit staff user')
                                                    <div class="action-btn me-2">
                                                        <button href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                            data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                            data-url="{{ route('company.hrms.users.edit', $user->id) }}"
                                                            data-size="xl" data-ajax-popup="true"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <span> <i class="ti ti-pencil text-white"></i></span>
                                                        </button>
                                                    </div>
                                                @endcan
                                                @can('delete staff user')
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
                                                @endcan
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
                    @endcan
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
