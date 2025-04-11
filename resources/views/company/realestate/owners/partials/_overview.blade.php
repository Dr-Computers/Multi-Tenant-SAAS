<h5 class="mb-3">Owner Information</h5>
<ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Name:</strong> {{ $owner->name }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $owner->email }}</li>
    <li class="list-group-item"><strong>Mobile:</strong> {{ $owner->mobile }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ $owner->is_active ? 'Active' : 'Inactive' }}</li>
</ul>
