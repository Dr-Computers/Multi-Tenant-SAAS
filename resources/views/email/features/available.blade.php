<h2>New Features Have Been Released!</h2>

<ul>
@foreach($features as $feature)
    <li>{{ $feature }}</li>
@endforeach
</ul>

@if($coupon)
<p><strong>Coupon for Early Access:</strong> {{ $coupon }}</p>
@endif
