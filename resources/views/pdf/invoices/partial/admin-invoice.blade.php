@extends($adminTemplate && $adminTemplate->templateData->file_path ? $adminTemplate->templateData->file_path : 'pdf.base')

@section('content')

    @php
        $total = 0;
        $currency = 'AED';

    @endphp

    <table class="items">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Description</th>
                {{-- <th>VAT ({{ adminPrice() }})</th> --}}
                <th>Amount ({{ adminPrice() }})</th>
            </tr>
        </thead>
        <tbody>

            @if ($invoice->plan && $invoice->plan->count() > 0)
                @foreach ($invoice->plan ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        {{-- <td>{{ number_format($item->tax, 2) }}</td> --}}
                        <td>{{ adminPrice() }} {{ number_format($item->price, 2) }}</td>
                    </tr>
                    @php
                        $total = $total + (number_format($item->price, 2) + number_format($item->tax, 2));
                    @endphp
                @endforeach
            @else
                @if ($invoice->order_items && $invoice->order_items->count() > 0)
                    @foreach ($invoice->order_items ?? [] as $index => $sections)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucfirst($sections->name) }}</td>
                            <td>{{ 0 }}</td>
                            <td>{{ number_format($sections->price, 2) }}</td>
                        </tr>
                        @php
                            $total = $total + number_format($sections->price, 2);
                        @endphp
                    @endforeach
                @endif

            @endif
        </tbody>
    </table>

    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p><strong>Sub Total:</strong></p>
                <p><strong>Discount Total:</strong></p>
                <p><strong>Tax:</strong></p>
                <p><strong>Grand Total:</strong></p>
                <p><strong>Total (in words):</strong></p>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top; text-transform: capitalize;">
                <p><strong> {{ adminPrice() }} {{ number_format($invoice->subtotal) }}</strong></p>
                <p><strong> {{ adminPrice() }} {{ number_format($invoice->discount) }}</strong></p>
                <p><strong> {{ adminPrice() }} {{ number_format($invoice->tax) }}</strong></p>
                <p><strong> {{ adminPrice() }} {{ number_format($invoice->price) }}</strong></p>
                <p>{{ numberToWords($invoice->price) }} United Arab Emirates dirhams</p>
            </td>
        </tr>
    </table>

    <footer>
        {{-- <div class="payment-info">
            <p><strong>Mode of Payment:</strong> Prepaid</p>
        </div> --}}

        <div class="company-signature">
            <p>for DR Computers</p><br><br><br>
            <p>Authorised Signatory</p>
        </div>
    </footer>

    <div class="center-text">
        <p><strong>Declaration:</strong> We declare that this invoice shows the actual price of the goods described and that
            all particulars are true and correct.</p>
        <p>This is a Computer Generated Invoice</p>
    </div>

@endsection
