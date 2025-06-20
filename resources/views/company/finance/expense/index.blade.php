@extends('layouts.company')
@section('page-title')
    {{ __('Expense') }}
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            var oldProperty = "{{ old('property', request('property')) }}";
            var oldUnit = "{{ old('unit', request('unit')) }}";

            $('select[name="property"]').on('change', function() {
                var propertyId = $(this).val();
                $('select[name="unit"]').html('<option value="">Select Unit</option>');
                var url = '{{ route('company.realestate.maintenance-requests.units', ':id') }}';
                url = url.replace(':id', propertyId);
                if (propertyId) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            // $.each(data, function(key, value) {
                            //     $('select[name="unit"]').append('<option value="' +
                            //         value['id'] + '">' + value['name'] +
                            //         '</option>');
                            // });
                            $.each(data, function(index, unit) {
                                $('select[name="unit"]').append('<option value="' + unit
                                    .id + '">' +
                                    unit.name + '</option>');
                            });

                            // Set old unit if exists
                            if (oldUnit) {
                                $('select[name="unit"]').val(oldUnit);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Expense') }}</li>
@endsection
@section('action-btn')
    @can('create a expense')
        <div class="d-flex">
            <a data-size="lg" data-url="{{ route('company.finance.expense.create') }}" data-ajax-popup2="true"
                data-bs-toggle="tooltip" title="{{ __('Create Expense') }}" class="btn btn-sm btn-primary text-light"
                data-bs-toggle="tooltip" title="{{ __('Create Expense') }}">
                <i class="ti ti-plus"></i> {{ __('Create Expense') }}
            </a>
        </div>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @can('expense listing')
                        <form method="GET" action="{{ route('company.finance.expense.index') }}" class="mb-5">
                            <div class="d-flex justify-content-end">
                                <!-- Property Filter -->
                                <div class="me-2">
                                    <select name="property" class="form-control form-control-sm" style="width: 150px;">
                                        <option value="">Select Property</option>
                                        @foreach ($filterProperty as $property)
                                            <option value="{{ $property->id }}"
                                                {{ request('property') == $property->id ? 'selected' : '' }}>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Unit Filter -->
                                <div class="me-2">
                                    <select name="unit" class="form-control form-control-sm" style="width: 150px;">
                                        <option value="">Select Unit</option>
                                        @foreach ($filterUnit as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="me-2">
                                    <select name="bank" class="form-control form-control-sm" style="width: 150px;">
                                        <option value="">Select Bank</option>
                                        @foreach ($filterBank as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ request('bank') == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->holder_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Search Input -->
                                <div class="d-flex align-items-center">
                                    <input type="text" name="searchInput" class="form-control form-control-sm me-2"
                                        placeholder="Search Expense..." style="width: 200px;"
                                        value="{{ request('searchInput') }}">
                                    <button type="submit" class="btn btn-primary btn-sm me-2">
                                        {{ __('Search') }}
                                    </button>

                                    <a href="{{ route('company.finance.expense.index') }}" class="btn btn-secondary btn-sm">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="card-body table-bUsers-style">

                            <div class="table-responsive" style="min-height: 80vh">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Expense') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Reference No') }}</th>
                                            <th>{{ __('Vender') }}</th>
                                            <th>{{ __('Property') }}</th>
                                            <th>{{ __('Unit') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('VAT Amount') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th>{{ __('Bank Account') }}</th>
                                            <th class="text-right">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($expenses as $expense)
                                            <tr role="row">
                                                <td>{{ expensePrefix() . $expense->expense_id }} </td>
                                                <td> {{ $expense->title }} </td>
                                                <td> {{ $expense->reference_no }} </td>
                                                <td> {{ $expense->vendor }} </td>
                                                <td> {{ !empty($expense->property) ? $expense->property->name : '-' }}
                                                </td>
                                                <td> {{ !empty($expense->unit) ? $expense->unit->name : '-' }} </td>
                                                <td>
                                                    {{ !empty($expense->types) && $expense->types->id !== 0 ? $expense->types->title : 'Property' }}
                                                </td>
                                                <td> {{ dateFormat($expense->date) }} </td>
                                                <td> {{ \Auth::user()->priceFormat($expense->base_amount) }} </td>
                                                <td> {{ \Auth::user()->priceFormat($expense->vat_amount) }} </td>
                                                <td> {{ \Auth::user()->priceFormat($expense->amount) }} </td>
                                                <td>
                                                    {{ !empty($expense->account) ? $expense->account->holder_name : '-' }}
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group card-option">

                                                        <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-end">

                                                            @can('expense details')
                                                                <a class="dropdown-item" role="button" data-size="lg" data-ajax-popup="true"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('View') }}" href="#"
                                                                    data-url="{{ route('company.finance.expense.show', $expense->id) }}"
                                                                    data-title="{{ __('Expense Details') }}">
                                                                    <span> <i class="ti ti-eye text-dark"></i>
                                                                        {{ __('Expense Details') }}</span>
                                                                </a>
                                                            @endcan
                                                            @can('edit a expense')
                                                                <a class="dropdown-item" role="button" data-size="lg"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Edit') }}" href="#" data-ajax-popup2="true"
                                                                    data-url="{{ route('company.finance.expense.edit', $expense->id) }}"
                                                                    data-title="{{ __('Edit Expense') }}">
                                                                    <i class="ti ti-pencil text-dark"></i>
                                                                    {{ __('Edit Expense') }}
                                                                </a>
                                                            @endcan
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['company.finance.expense.destroy', $expense->id]]) !!}
                                                            @can('delete a expense')
                                                                <a class="dropdown-item  bs-pass-para" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Delete') }}" href="#">
                                                                    <i class="ti ti-trash text-dark"></i>
                                                                    {{ __('Expense Delete') }}
                                                                </a>
                                                            @endcan
                                                            {!! Form::close() !!}

                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12">
                                                    <div class="text-center py-4">
                                                        <span>No Data Found</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3">
                                    {!! $expenses->onEachSide(2)->links() !!}
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
