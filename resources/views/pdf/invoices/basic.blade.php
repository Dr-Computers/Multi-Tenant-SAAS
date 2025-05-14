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

{{-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Invoice</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

.invoice {
    border: 1px solid #000;
    padding: 20px;
}

header {
 display: flex;
  justify-content: space-between;

    margin-bottom: 20px;
}
.logo-img{
	width:200px;
}

.invoice-head{
	flot:right;
	text-aligh:center;
}

.details {
    display: flex;
    justify-content: space-between;
}

.details div {
    width: 30%;
}

.items {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.items th, .items td {
    border: 1px solid #000;
    padding: 10px;
    text-align: left;
}
footer .payment-info{

    padding: 10px;
	border:1px solid black;
}

footer .company-signature{
    margin-top: 20px;
    text-align: right;
}

</style>
</head>
<body>
    <div class="invoice">
        <header>
        	<div class="logo">
            		<img src="http://127.0.0.1:8000/storage/uploads/logo/logo-dark.png?1747211993" class="logo-img">
            </div>
            <div class="invoice-head">
                 	<h1>Tax Invoice/Bill of Supply/Cash Memo</h1>
            		<p>(Original for Recipient)</p>
            </div>
       
        </header>

        <section class="details">
            <div class="sold-by">
                <h2>From :</h2>
                <p>Dr Computers</p>
                <p>Al Mullah Building </p>
                <p>42 - Bur Dubai - Dubai</p>
                <p>United Arab Emirates</p>
            </div>

            <div class="billing-address">
                <h2>Billing Address:</h2>
                <p>ZAIN ABULAH KHAN</p>
                <p>#305, Gulnaz Javeed Manor, Wheeler Road, Cooke Town, United Arab Emirates.</p>
            </div>
</section>
<hr>
   <section class="details">
          
            <div class="invoice-info">
            
                <p>Phone: +971 56 123 1093</p>
                <p>Email: info@drcomputers.ae</p>
                <p>VAT Registration No: 29AARPK5605J1Z8</p>
                <p>Website : https://www.drcomputers.ae</p>
                
            </div>
            <div class="shipping-address">
             	 <p>Invoice Date: 12.05.2025</p>
                <p>Invoice Number: 1001</p>
                <p>Transation No: 2345234540439943</p>
                <p>Order Number: Not Generated</p>
                <p>Order Date: 13.04.2025</p>
             </div>
         </section>
                

        <table class="items">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Product/Service.</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Amount (AED)</th>
                    <th>VAT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Basic Plan</td>
                    <td>Plan Purchased</td>
                    <td>1 Month</td>
                    <td>10.00</td>
                    <td>1.91</td>
                </tr>
            </tbody>
            <tfooter>
            	<tr >
                	<th  colspan="10" >
                    <div style="display:flex;justify-content: space-between;">
                    <p>
                    TOTAL:
                    <br>
                    Total invoice value (In words):
                    </p>
                      <p style="text-align:right"> AED 11.91/- <br>
                         Eleven and ninety-one fils 
                      </p>
                      </div>
                    </th>
                </tr>
               
            </tfooter>
        </table>

        <footer>
             <div class="payment-info">
                  <span  >
                  Mode of Payment: Prepaid
                  </span>

                </div>
               <div class="company-signature">
               
            <p>for DR computers</p><br><br><br><br>
            <p>Authorised Signatory</p>
            <br><br><br><br><br>
            </div>
        </footer>
        <div style="text-align:center">
        <p>Declaration: We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
        <p>This is a Computer Generated Invoice</p>
        </div>
    </div>
</body>
</html> --}}
