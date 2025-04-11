<h5 class="mb-3">Maintainer Information</h5>
<ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Name:</strong> {{ $maintainer->name }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $maintainer->email }}</li>
    <li class="list-group-item"><strong>Mobile:</strong> {{ $maintainer->mobile }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ $maintainer->is_active ? 'Active' : 'Inactive' }}</li>
</ul>
