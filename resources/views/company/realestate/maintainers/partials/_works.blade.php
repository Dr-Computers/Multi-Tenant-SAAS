<h5 class="mb-3">Requests for Approval</h5>
@if($maintainer->works)
    <ul class="list-group">
        @foreach($maintainer->works as $req)
            <li class="list-group-item">
                <strong>{{ $req->name }}</strong> has requested approval for property: 
                <em>{{ $req->property->title ?? 'N/A' }}</em>
            </li>
        @endforeach
    </ul>
@else
    <p>No requests pending.</p>
@endif
