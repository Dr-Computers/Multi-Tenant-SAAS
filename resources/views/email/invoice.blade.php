<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        h1 {
            font-size: 24px;
        }
    </style>
</head>

<body>
    <h1>Invoice #{{ $order->id }}</h1>
    <p>Company: {{ $order->user->name }}</p>
    <p>Total: ${{ $order->price }}</p>
    <p>Date: {{ $order->created_at->format('d M Y') }}</p>
</body>

</html>
