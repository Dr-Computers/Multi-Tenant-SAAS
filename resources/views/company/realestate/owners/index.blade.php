@extends('layouts.company')
@section('page-title')
    {{ __('Owners') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Owners') }}</li>
@endsection
@section('action-btn')
    @can('create owner user')
        <div class="d-flex">
            <a href="#" data-size="md" data-url="{{ route('company.realestate.owners.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New Owner') }}" class="btn btn-sm btn-primary me-2">
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
                    @can('owner user listing')
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Mobile No') }}</th>
                                        <th>{{ __('Properties') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($owners ?? [] as $key => $owner)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('company.realestate.owners.show', $owner->id) }}">
                                                    <img src="{{ asset('storage/' . $owner->avatar_url) }}"
                                                        class="h-10 w-auto border mb-1 rounded-circle">
                                                    <span class="mt-1 text-capitalize small">{{ $owner->name }}</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $owner->email }}">{{ $owner->email }}</a>
                                            </td>
                                            <td>
                                                <a href="tel:{{ $owner->mobile }}">{{ $owner->mobile }}</a>
                                            </td>
                                            <td>
                                                0
                                            </td>
                                            <td>
                                                @if ($owner->is_active == '1')
                                                    <span class="badge bg-success   py-1 px-2 rounded">
                                                        {{ ucfirst('Enabled') }}</span>
                                                @else
                                                    <span class="badge bg-danger py-1 px-2 rounded">
                                                        {{ ucfirst('Disabled') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @can('edit owner user')
                                                        <button href="#" class="dropdown-item" data-bs-toggle="tooltip"
                                                            title="{{ __('Reset Password') }}"
                                                            data-url="{{ route('company.realestate.owners.reset.form', $owner->id) }}"
                                                            data-size="xl" data-ajax-popup="true"
                                                            data-original-title="{{ __('Reset Password') }}">
                                                            <span> <i class="ti ti-lock text-dark"></i>
                                                                {{ __('Reset Password') }}</span>
                                                        </button>
                                                        @endcan
                                                        @can('owner user details')
                                                        <a href="{{ route('company.realestate.owners.show', $owner->id) }}"
                                                            class="dropdown-item" data-bs-toggle="tooltip"
                                                            title="{{ __('View') }}"
                                                            data-original-title="{{ __('Show') }}">
                                                            <span> <i class="ti ti-eye text-dark"></i>
                                                                {{ __('Show') }}</span>
                                                        </a>
                                                        @endcan
                                                        @can('edit owner user')
                                                        <button href="#" class="dropdown-item" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}"
                                                            data-url="{{ route('company.realestate.owners.edit', $owner->id) }}"
                                                            data-size="xl" data-ajax-popup="true"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <span> <i class="ti ti-pencil text-dark"></i>
                                                                {{ __('Edit') }}</span>
                                                        </button>
                                                        @endcan
                                                        @can('delete owner user')
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['company.realestate.owners.destroy', $owner->id],
                                                            'id' => 'delete-form-' . $owner->id,
                                                        ]) !!}
                                                        <a href="#" class="dropdown-item" data-bs-toggle="tooltip"
                                                            title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash text-dark  "></i>
                                                            {{ __('Delete') }}</a>

                                                        {!! Form::close() !!}
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h6>No owners found..!</h6>
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
