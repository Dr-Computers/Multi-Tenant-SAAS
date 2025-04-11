<h5 class="mb-3">Tenant Information</h5>
<ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Name:</strong> {{ $tenant->name }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $tenant->email }}</li>
    <li class="list-group-item"><strong>Mobile:</strong> {{ $tenant->mobile }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ $tenant->is_active ? 'Active' : 'Inactive' }}</li>
</ul>
