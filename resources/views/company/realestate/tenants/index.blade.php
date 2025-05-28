@extends('layouts.company')
@section('page-title')
    {{ __('Tenants') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Tenants') }}</li>
@endsection
@section('action-btn')
    @can('create tenant user')
        <div class="d-flex">
            <a href="#" data-size="md" data-url="{{ route('company.realestate.tenants.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New user') }}" class="btn btn-sm btn-primary me-2">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-bUsers-style">
                    <div class="table-responsive">
                        @can('tenant user listing')
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Mobile No') }}</th>
                                        <th>{{ __('Properties/Units') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tenants ?? [] as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('company.realestate.tenants.show', $user->id) }}">
                                                    <img src="{{ asset('storage/' . $user->avatar_url) }}"
                                                        class="h-10 w-auto border mb-1 rounded-circle">
                                                    <span class="mt-1 text-capitalize small">{{ $user->name }}</span>
                                                </a>
                                            </td>
                                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                            <td><a href="tel:{{ $user->mobile }}">{{ $user->mobile }}</a></td>
                                            <td>0</td>
                                            <td>
                                                @if ($user->is_active == '1')
                                                    <span class="badge bg-success py-1 px-2 rounded">
                                                        {{ ucfirst('Enabled') }}</span>
                                                @else
                                                    <span class="badge bg-danger py-1 px-2 rounded">
                                                        {{ ucfirst('Disabled') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="action-btn me-2">
                                                    <button href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-dark"
                                                        data-bs-toggle="tooltip" title="{{ __('Reset Password') }}"
                                                        data-url="{{ route('company.realestate.tenants.reset.form', $user->id) }}"
                                                        data-size="xl" data-ajax-popup="true"
                                                        data-original-title="{{ __('Reset Password') }}">
                                                        <span> <i class="ti ti-lock text-white"></i></span>
                                                    </button>
                                                </div>
                                                <div class="action-btn me-2">
                                                    <a href="{{ route('company.realestate.tenants.show', $user->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                        data-bs-toggle="tooltip" title="{{ __('Show') }}"
                                                        data-original-title="{{ __('Show') }}">
                                                        <span> <i class="ti ti-eye text-white"></i></span>
                                                    </a>
                                                </div>

                                                <div class="action-btn me-2">
                                                    <button href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-warning"
                                                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                        data-url="{{ route('company.realestate.tenants.edit', $user->id) }}"
                                                        data-size="xl" data-ajax-popup="true"
                                                        data-original-title="{{ __('Edit') }}">
                                                        <span> <i class="ti ti-pencil text-white"></i></span>
                                                    </button>
                                                </div>

                                                <div class="action-btn">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['company.realestate.tenants.destroy', $user->id],
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
                                                <h6>No tenants found..!</h6>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endcan
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
