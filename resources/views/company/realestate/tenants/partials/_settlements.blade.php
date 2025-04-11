<h5 class="mb-3">Settlement History</h5>
@if($tenant->settlements)
    <ul class="list-group">
        @foreach($tenant->settlements as $settlement)
            <li class="list-group-item">
                Amount: ₹{{ number_format($settlement->amount, 2) }}<br>
                Date: {{ \Carbon\Carbon::parse($settlement->date)->format('d M Y') }}<br>
                Status: {{ $settlement->status }}
            </li>
        @endforeach
    </ul>
@else
    <p>No settlements found.</p>
@endif
