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
        body {
            font-family: DejaVu Sans, sans-serif;
            /* Compatible font */
            font-size: 12px;
        }

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
                    <img src="{{ public_path('storage/uploads/logo/logo-dark.png') }}" style="width: 50px;">
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
                    <p>Dr Computers</p>
                    <p>Al Mullah Building</p>
                    <p>42 - Bur Dubai - Dubai</p>
                    <p>United Arab Emirates</p>
                </td>
                <td width="50%" valign="top">
                    <h2>Billing Address:</h2>
                    <p>ZAIN ABULAH KHAN</p>
                    <p>#305, Gulnaz Javeed Manor, Wheeler Road, Cooke Town, United Arab Emirates.</p>
                </td>
            </tr>
        </table>


        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="50%" valign="top">
                    <p>Phone: +971 56 123 1093</p>
                    <p>Email: info@drcomputers.ae</p>
                    <p>VAT Registration No: 29AARPK5605J1Z8</p>
                    <p>Website : https://www.drcomputers.ae</p>
                </td>
                <td width="50%" valign="top">
                    <p>Invoice Date: 12.05.2025</p>
                    <p>Invoice Number: 1001</p>
                    <p>Transaction No: 2345234540439943</p>
                    <p>Order Number: Not Generated</p>
                    <p>Order Date: 13.04.2025</p>
                </td>
            </tr>
        </table>

        @yield('content')
    </div>
</body>

</html>
