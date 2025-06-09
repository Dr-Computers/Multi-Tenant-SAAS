<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bUsers-style">
                <div class="table-responsive" style="min-height: 50vh;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th>{{ __('Tenant') }}</th>
                                <th class="text-center">{{ __('Rent Duration') }}</th>
                                <th class="text-center">{{ __('Deposit') }}</th>
                                <th class="text-center">{{ __('Rent') }}</th>
                                <th class="text-center">{{ __('No:of Payments') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leasing_units ?? [] as $key => $unit)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        Property : {{ $unit->property->name }} <br>
                                        Unit : {{ $unit->name }} <br>
                                        Reg no : {{ $unit->registration_no }}
                                    </td>
                                    <td>{{ $unit->property->owner->name }}</td>
                                    <td>{{ $unit->lease->tenant->name }}</td>
                                    <td class="text-center">{{ $unit->rent_duration }}</td>
                                    <td class="text-end">{{ $unit->deposite_amount }}/ {{ $unit->deposite_type }}</td>
                                    <td class="text-end">{{ $unit->price }}/ {{ $unit->rent_type }}</td>
                                    <td class="text-center">{{ $unit->lease->no_of_payments }}</td>

                                    <td>
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">

                                                <a class="dropdown-item" data-bs-toggle="tooltip"
                                                    title="{{ __('Unit Detailed View') }}" target="_blank"
                                                    href="{{ route('company.realestate.property.units.show', ['property_id' => $unit->property_id, 'unit' => $unit->id]) }}"
                                                    data-original-title="{{ __('Unit Detailed View') }}"
                                                    href="#">
                                                    <span> <i class="ti ti-eye text-dark"></i>
                                                        {{ __('Unit View') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6>No units found..!</h6>
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
