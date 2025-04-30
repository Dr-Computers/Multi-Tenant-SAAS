<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-blogs-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Date and Time') }}</th>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('IP') }}</th>
                                <th class="text-center">{{ __('Browser') }}</th>
                                <th>{{ __('Action') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activity_logs ??  [] as $key => $log)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ dateTimeFormat($log->created_at) }}</span>
                                    </td>
                                    <td> {{ $log->action }}</td>
                                    <td><span class="fw-bold text-primary">{{ $log->ip_address }}</span></td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ $log->user_agent }}</span>
                                    </td>
                                    <td>
                                        
                                        <div class="action-btn me-2">
                                            <a class="mx-3 btn btn-sm d-inline-flex align-items-center bg-info"
                                                href="#">
                                                <span> <i class="ti ti-eye text-white"></i></span>
                                            </a>
                                        </div>
                                   
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6>No activity logs found..!</h6>
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
