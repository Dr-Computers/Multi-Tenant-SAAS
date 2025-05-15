<h2>Hello {{ $user->name ?? 'User' }},</h2>

<p>A new <strong>Letter Pad Template</strong> has been successfully created in your account.</p>

<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="padding: 8px; border: 1px solid #ccc;"><strong>Template Name:</strong></td>
        <td style="padding: 8px; border: 1px solid #ccc;">{{ $letterPad->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ccc;"><strong>Created At:</strong></td>
        <td style="padding: 8px; border: 1px solid #ccc;">{{ $letterPad->created_at->format('d M Y, h:i A') ?? 'N/A' }}</td>
    </tr>
</table>

<p>You can now start using this template for your letterheads and correspondence.</p>

<p>If you need assistance, please visit our <a href="{{ url('/support') }}">Support Center</a>.</p>

<p style="margin-top: 20px;">Best regards,<br>The {{ config('app.name') }} Team</p>
