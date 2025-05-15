<h2>Hello {{ $user->name ?? 'User' }},</h2>

<p>A new <strong>Estimate Template</strong> has been successfully created in your account.</p>

<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="padding: 8px; border: 1px solid #ccc;"><strong>Template Name:</strong></td>
        <td style="padding: 8px; border: 1px solid #ccc;">{{ $estimateTemplate->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ccc;"><strong>Created At:</strong></td>
        <td style="padding: 8px; border: 1px solid #ccc;">{{ $estimateTemplate->created_at->format('d M Y, h:i A') ?? 'N/A' }}</td>
    </tr>
</table>

<p>You can now use this template for your estimates from the estimate management panel.</p>

<p>Thank you for using our service!</p>

<p style="margin-top: 20px;">Regards,<br>The {{ config('app.name') }} Team</p>
