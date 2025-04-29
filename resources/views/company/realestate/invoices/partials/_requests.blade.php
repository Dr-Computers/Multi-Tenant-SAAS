<h5 class="mb-3">Requests for Approval</h5>
@if($tenant->tenants)
    <ul class="list-group">
        @foreach($tenant->tenants as $tenant)
            <li class="list-group-item">
                <strong>{{ $tenant->name }}</strong> has requested approval for property: 
                <em>{{ $tenant->property->title ?? 'N/A' }}</em>
            </li>
        @endforeach
    </ul>
@else
    <p>No requests pending.</p>
@endif
