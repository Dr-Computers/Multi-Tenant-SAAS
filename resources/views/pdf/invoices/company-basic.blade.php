<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .invoice {
            border: 1px solid #000;
            padding: 20px;
            width: 100%;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-img {
            width: 150px;
        }

        .invoice-head {
            text-align: center;
            width: 100%;
        }

        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .details div {
            width: 45%;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items th,
        .items td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items th {
            background-color: #f5f5f5;
        }

        .totals {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .totals p {
            margin: 0;
            padding: 0;
        }

        footer {
            margin-top: 30px;
        }

        .payment-info {
            padding: 10px;
            border: 1px solid black;
        }

        .company-signature {
            margin-top: 50px;
            text-align: right;
        }

        .center-text {
            text-align: center;
            font-size: 11px;
            margin-top: 30px;
        }
    </style>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            /* border: 1px solid #000; */
            padding: 6px;
        }

        h1,
        h2 {
            font-size: 14px;
            margin: 4px 0;
        }

        p {
            margin: 2px 0;
        }
    </style>

</head>

<body>
    <div class="invoice">
        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="50%">
                    <img src="{{ asset('storage/uploads/logo/logo-dark.png') }}" style="width: 50px;">
                </td>
                <td width="50%" style="text-align: right;">
                    <span style="margin: 0;">Tax Invoice/Bill of Supply/Cash Memo</span>
                    <p style="margin: 0;">(Original for Recipient)</p>
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="50%" valign="top">
                    <h2>From:</h2>
                    <p>{{ $invoice->company->bussiness_name }}</p>
                    <p>{{ $invoice->company->company->address }}</p>
                    <p>{{ $invoice->company->company->landmark }}- {{ $invoice->company->company->city }}
                        - {{ $invoice->company->company->postalcode }}</p>
                    <p>{{ $invoice->company->company->country }}</p>
                </td>
                <td width="50%" valign="top">
                    <h2>Billing Address:</h2>
                    <p>{{ $invoice->user->name }}</p>
                    <p>{{ $invoice->user->personal->address . ', ' }}</p>
                    {{ $invoice->user->personal->city . ', ' . $invoice->user->personal->postal_code . ', ' }}</p>
                    <p>{{ $invoice->user->personal->state . ', ' . $invoice->user->personal->country }}</p>
                </td>
            </tr>
        </table>


        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="50%" valign="top">
                    <p>Phone: {{ $invoice->company->mobile }}</p>
                    <p>Email:{{ $invoice->company->email }}</p>
                </td>
                <td width="50%" valign="top">
                    <p>Order Date: {{ dateTimeFormat($invoice->created_at) }}</p>
                    <p>Order Number: {{ $invoice->order_id }}</p>
                </td>
            </tr>
        </table>

        @yield('content')
    </div>
</body>

</html>
