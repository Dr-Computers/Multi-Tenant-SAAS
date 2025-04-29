<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td>
                                Requested furnishing
                            </td>
                            <td>
                                {{ $furnishing->request_for }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Notes
                            </td>
                            <td>
                                {{ $furnishing->notes }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Status
                            </td>
                            <td>
                                @if ($furnishing->status == '1')
                                <span class="badge bg-success p-1 px-3 rounded">
                                    {{ ucfirst('Approved') }}</span>
                            @elseif($furnishing->status == '2')
                                <span class="badge bg-danger p-1 px-3 rounded">
                                    {{ ucfirst('Rejected') }}</span>
                            @elseif($furnishing->status == '0')
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