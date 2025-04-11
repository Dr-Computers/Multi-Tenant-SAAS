<div class="modal-body">
    <h5 class="mb-3 fw-bold text-primary">User Information</h5>

    <table class="table table-bordered align-middle">
        <tbody>
            <tr>
                <th style="width: 30%;">Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ $user->mobile }}</td>
            </tr>
            <tr>
                <th>Role</th>
                <td>{{ $user->getRoleNames()->first() }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($user->is_enable_login)
                        <span class="badge bg-success">Enabled</span>
                    @else
                        <span class="badge bg-danger">Disabled</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>

