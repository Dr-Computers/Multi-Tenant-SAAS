<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-bUsers-style">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Property') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th>{{ __('Issue') }}</th>
                            <th>{{ __('Requested/Solved') }}</th>
                            @if (isset($status))
                                <th>{{ __('Status') }}</th>
                            @endif
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($maintainer->maintainceWorks ?? [] as $key => $req)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $req->property ? $req->property->name : '---' }}</td>
                                <td>{{ $req->unit && $req->unit ? $req->unit->name : '---' }}</td>
                                <td>{{ $req->issue ? $req->issue->name : '' }}</td>
                                <td>{{ dateTimeFormat($req->request_date) }}</td>
                                @if (isset($status))
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
                                @endif
                                <td>
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">

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
                                    <h6>No Works found..!</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
