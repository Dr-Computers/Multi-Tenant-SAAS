<h2>New Real Estate Setup Request</h2>

<p>The following data has been requested for addition:</p>

<ul>
@foreach($newRequest as $type => $items)
    <li><strong>{{ ucfirst($type) }}</strong>:
        <ul>
        @foreach($items as $item)
            <li>{{ $item }}</li>
        @endforeach
        </ul>
    </li>
@endforeach
</ul>
