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
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($amenities ?? [] as $key => $amenity)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $amenity->request_for }}</td>
                                    <td>
                                        @if ($amenity->status == '1')
                                            <span class="badge bg-success p-1 px-3 rounded">
                                                {{ ucfirst('Approved') }}</span>
                                        @elseif($amenity->status == '2')
                                            <span class="badge bg-danger p-1 px-3 rounded">
                                                {{ ucfirst('Rejected') }}</span>
                                        @elseif($amenity->status == '0')
                                            <span class="badge bg-warning p-1 px-3 rounded">
                                                {{ ucfirst('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-btn me-2">
                                            <button href="#" data-size="md"
                                                data-url="{{ route('company.realestate.amenities.edit', $amenity->id) }}"
                                                data-ajax-popup2="true" data-bs-toggle="tooltip"
                                                title="{{ __('Requested amenities list') }}"
                                                class="btn btn-sm btn-secondary me-2">
                                                <span class="ti ti-eye text-white text-center"></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6>No requests found..!</h6>
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
