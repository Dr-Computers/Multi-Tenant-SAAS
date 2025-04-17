@extends('layouts.company')
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Properties') }}</li>
@endsection
@section('action-btn')
    <div class="d-flex">
        <a href="{{ route('company.realestate.properties.create') }}"
   title="{{ __('Create New Property') }}" class="btn btn-sm btn-primary me-2">
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
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($properties ?? [] as $key => $property)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $property->name }}</td>
                                        <td>{{ $property->categories->pluck('name')->first() }}</td>
                                        <td>{{ $property->purpose_type }}</td>
                                        <td>
                                            @if ($property->is_enable_login == '1')
                                                <i class="badge text-success p-2 px-3 rounded"></i>
                                                {{ ucfirst('Enabled') }}
                                            @else
                                                <i class="badge text-danger p-2 px-3 rounded"></i>
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
                                                    <a class="dropdown-item"
                                                        href="{{ route('company.realestate.property.units.index', $property->id) }}"
                                                    >
                                                        <span> <i class="ti ti-plus text-dark"></i> {{ __('Units') }}</span>
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('company.realestate.properties.show', $property->id) }}"
                                                    >
                                                        <span> <i class="ti ti-eye text-dark"></i> {{ __('View') }}</span>
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('company.realestate.properties.edit', $property->id) }}"
                                                    >
                                                        <span> <i class="ti ti-pencil text-dark"></i> {{ __('Edit') }}</span>
                                                    </a>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['company.realestate.properties.destroy', $property->id],
                                                        'id' => 'delete-form-' . $property->id,
                                                    ]) !!}
                                                    <a href="#"
                                                        class="dropdown-item bs-pass-para "
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash text-dark "></i> {{ __('Delete') }}</a>
    
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                      


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <h6>No maintainers found..!</h6>
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
