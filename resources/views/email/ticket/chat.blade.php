<h2>Support Ticket: #{{ $ticket->ticket_id }}</h2>
<p><strong>Subject:</strong> {{ $ticket->subject }}</p>
<p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>

<h3>Conversation History:</h3>
@foreach($chats as $chat)
    <div style="margin-bottom: 10px;">
        <strong>{{ $chat->user->name ?? 'System' }}:</strong><br>
        <p>{{ $chat->message }}</p>
        <small>{{ $chat->created_at->format('d M Y, h:i A') }}</small>
    </div>
@endforeach
