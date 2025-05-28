@can('manage category request')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead></thead>
                        <tbody>
                            <tr>
                                <td>
                                    Requested Category
                                </td>
                                <td>
                                    {{ $category->request_for }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Notes
                                </td>
                                <td>
                                    {{ $category->notes }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Status
                                </td>
                                <td>
                                    @if ($category->status == '1')
                                        <span class="badge bg-success p-1 px-3 rounded">
                                            {{ ucfirst('Approved') }}</span>
                                    @elseif($category->status == '2')
                                        <span class="badge bg-danger p-1 px-3 rounded">
                                            {{ ucfirst('Rejected') }}</span>
                                    @elseif($category->status == '0')
                                        <span class="badge bg-warning p-1 px-3 rounded">
                                            {{ ucfirst('Pending') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcan
