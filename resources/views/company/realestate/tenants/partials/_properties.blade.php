<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bUsers-style">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th class="text-center">{{ __('Rent Duration') }}</th>
                                <th class="text-center">{{ __('Deposit') }}</th>
                                <th class="text-center">{{ __('Rent') }}</th>
                                <th class="text-center">{{ __('No:of Payments') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tenant->unitLeases ?? [] as $key => $lease)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        Property : {{ $lease->unitDetails->property->name }} <br>
                                        Unit : {{ $lease->unitDetails->name }} <br>
                                        Reg no : {{ $lease->unitDetails->property->registration_no }}
                                    </td>
                                    <td>{{ $lease->unitDetails->property->owner->name }}</td>
                                    <td class="text-center">{{ $lease->unitDetails->rent_duration }}</td>
                                    <td class="text-end">{{ $lease->unitDetails->deposite_amount }}/
                                        {{ $lease->unitDetails->deposite_type }}</td>
                                    <td class="text-end">{{ $lease->unitDetails->price }}/
                                        {{ $lease->unitDetails->rent_type }}</td>
                                    <td class="text-center">{{ $lease->unitDetails->lease->no_of_payments }}</td>
                                    <td class="text-center">{{ $lease->unitDetails->lease->status }}</td>
                                    <td>
                                        <a class="dropdown-item" data-bs-toggle="tooltip"
                                            title="{{ __('Unit Detailed View') }}" target="_blank"
                                            href="{{ route('company.realestate.property.units.show', ['property_id' => $lease->unitDetails->property_id, 'unit' => $lease->unitDetails->id]) }}"
                                            data-original-title="{{ __('Unit Detailed View') }}" href="#">
                                            <span> <i class="ti ti-eye text-dark"></i>
                                                {{ __('Unit View') }}</span>
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
