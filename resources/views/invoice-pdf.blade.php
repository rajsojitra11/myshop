<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->bill_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-block {
            flex: 1;
            margin-right: 20px;
        }
        .info-block h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #667eea;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-block p {
            margin: 5px 0;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount-column {
            text-align: right;
        }
        .summary {
            float: right;
            width: 40%;
            margin-top: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 12px;
            border-bottom: 1px solid #ddd;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 14px;
            background-color: #667eea;
            color: white;
            padding: 10px;
            border: none;
        }
        .footer {
            clear: both;
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>MY SHOP</h1>
            <p>Invoice</p>
            <p>Date: {{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="info-block">
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> {{ $invoice->to_name }}</p>
                <p><strong>Address:</strong> {{ $invoice->to_address }}</p>
                <p><strong>Email:</strong> {{ $invoice->to_email }}</p>
                <p><strong>Mobile:</strong> {{ $invoice->mobile_no }}</p>
            </div>
            <div class="info-block">
                <h3>Invoice Details</h3>
                <p><strong>Bill No:</strong> {{ $invoice->bill_no }}</p>
                <p><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Order ID:</strong> {{ $invoice->order_id }}</p>
                <p><strong>Account:</strong> {{ $invoice->account_number }}</p>
                <p><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
            </div>
        </div>

        <!-- Products Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th style="text-align: right;">Qty</th>
                    <th style="text-align: right;">Rate</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->products as $product)
                <tr>
                    <td>{{ $product->serial ?? '-' }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description ?? '-' }}</td>
                    <td class="amount-column">{{ $product->qty }}</td>
                    <td class="amount-column">₹{{ number_format($product->rate, 2) }}</td>
                    <td class="amount-column">₹{{ number_format($product->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₹{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Tax (9.3%):</span>
                <span>₹{{ number_format($invoice->tax, 2) }}</span>
            </div>
            @if($invoice->shipping)
            <div class="summary-row">
                <span>Shipping:</span>
                <span>₹{{ number_format($invoice->shipping, 2) }}</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>TOTAL:</span>
                <span>₹{{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated document and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
