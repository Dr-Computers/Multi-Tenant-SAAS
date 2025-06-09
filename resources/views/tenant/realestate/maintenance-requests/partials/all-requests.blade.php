<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bUsers-style">
                <div class="table-responsive">

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Property') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Issue') }}</th>
                                <th>{{ __('Maintainer') }}</th>
                                <th>{{ __('Requested/Solved') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allRequests ?? [] as $key => $req)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $req->property ? $req->property->name : '---' }}</td>
                                    <td>{{ $req->unit && $req->unit ? $req->unit->name : '---' }}</td>
                                    <td>{{ $req->issue ? $req->issue->name : '' }}</td>
                                    <td>{{ $req->maintainer ? $req->maintainer->name : '---' }}</td>
                                    <td>{{ dateTimeFormat($req->request_date) }}</td>
                                    <td>
                                        @if ($req->status == 'completed')
                                            <span class="badge bg-success p-1 px-3 rounded">
                                                {{ ucfirst('completed') }}</span>
                                        @elseif($req->status == 'rejected')
                                            <span class="badge bg-danger p-1 px-3 rounded">
                                                {{ ucfirst('Rejected') }}</span>
                                        @elseif($req->status == 'pending')
                                            <span class="badge bg-warning p-1 px-3 rounded">
                                                {{ ucfirst('Pending') }}</span>
                                        @elseif($req->status == 'inprogress')
                                        <span class="badge bg-info p-1 px-3 rounded">
                                            {{ ucfirst('in progress') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group card-option">

                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                
                                                @if ($req->invoice)
                                                    <a class="dropdown-item" data-size="lg"
                                                        data-url="{{ route('company.realestate.maintenance-requests.download-invoice', $req->id) }}"
                                                        data-ajax-popup2="true" data-bs-toggle="tooltip"
                                                        title="{{ __('Download Invoice ') }}">
                                                        <span> <i class="ti ti-download text-dark"></i>
                                                            {{ __('Download Invoice') }}</span>
                                                    </a>
                                                @endif
                                                <a class="dropdown-item" data-size="lg"
                                                    data-url="{{ route('company.realestate.maintenance-requests.show', $req->id) }}"
                                                    data-ajax-popup2="true" data-bs-toggle="tooltip"
                                                    title="{{ __('View Request') }}">
                                                    <span> <i class="ti ti-eye text-dark"></i>
                                                        {{ __('View') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6>No Requests found..!</h6>
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
