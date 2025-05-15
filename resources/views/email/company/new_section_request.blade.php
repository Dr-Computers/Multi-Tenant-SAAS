<h2>New Section Request</h2>

<p>{{ $sectionRequest->user->name }} has requested a new section.</p>

<p><strong>Section Name:</strong> {{ $sectionRequest->section_name }}</p>
<p><strong>Details:</strong> {{ $sectionRequest->details ?? 'No additional information provided.' }}</p>
