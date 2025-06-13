<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bUsers-style">
                <div class="table-responsive" style="min-height: 50vh;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Property Name') }}</th>
                                <th>{{ __('Unit Name') }}</th>
                                <th>{{ __('Reg No') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Deposit') }}</th>
                                <th>{{ __('Rent') }}</th>
                                <th>{{ __('Rent Duration') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($unleashed_units ?? [] as $key => $unit)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $unit->property ? $unit->property->name : 'unknown' }}</td>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->registration_no }}</td>
                                    <td>{{ $unit->property->owner->name }}</td>
                                    <td class="text-capitalize">
                                        {{ $unit->property->categories->pluck('name')->first() ?? '--' }}/{{ $unit->property->mode }}
                                    </td>
                                    <td class="text-capitalize">{{ $unit->deposite_amount }}/
                                        {{ $unit->deposite_type }}</td>
                                    <td>{{ $unit->price }}/ {{ $unit->rent_type }}</td>
                                    <td>{{ $unit->rent_duration }}</td>
                                    <td>{{ $unit->lease ? $unit->lease->status : $unit->status }}</td>
                                    <td>
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">

                                                <a class="dropdown-item" data-bs-toggle="tooltip"
                                                    title="{{ __('Unit Detailed View') }}" target="_blank"
                                                    href="{{ route('owner.realestate.property.units.show', ['property_id' => $unit->property_id, 'unit' => $unit->id]) }}"
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
