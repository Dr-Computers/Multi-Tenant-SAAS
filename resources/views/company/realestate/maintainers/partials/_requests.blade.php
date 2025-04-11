<h5 class="mb-3">Requests for Approval</h5>
@if($maintainer->tenants)
    <ul class="list-group">
        @foreach($maintainer->tenants as $tenant)
            <li class="list-group-item">
                <strong>{{ $maintainer->name }}</strong> has requested approval for property: 
                <em>{{ $maintainer->property->title ?? 'N/A' }}</em>
            </li>
        @endforeach
    </ul>
@else
    <p>No requests pending.</p>
@endif
