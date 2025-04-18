<h5 class="mb-3">Owned Properties</h5>
@if($tenant->properties)
    <ul class="list-group">
        @foreach($tenant->properties as $property)
            <li class="list-group-item">
                <strong>{{ $property->title }}</strong><br>
                Location: {{ $property->location }}<br>
                Status: {{ $property->status }}
            </li>
        @endforeach
    </ul>
@else
    <p>No properties found.</p>
@endif
