<h2>New Plan Request</h2>

<p>A new plan request has been submitted by {{ $planRequest->user->name }} ({{ $planRequest->user->email }}).</p>

<p><strong>Requested Plan:</strong> {{ $planRequest->plan_name }}</p>
<p><strong>Message:</strong> {{ $planRequest->message ?? 'No additional message.' }}</p>
