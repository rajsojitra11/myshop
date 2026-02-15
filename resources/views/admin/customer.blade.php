@extends('admin.index')
@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page', 'Customers')

@section('content')
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Mobile No.</th>
                <th>Bill No.</th>
                <th>Payment Method</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->to_name }}</td>
                    <td>{{ $invoice->mobile_no }}</td>
                    <td>{{ $invoice->bill_no }}</td>
                    <td>{{ $invoice->payment_method }}</td>
                    <td>₹ {{ number_format($invoice->total, 2) }}</td>
                    <td>
                        <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-sm btn-primary">View</a>
                        <button type="button" 
                                class="btn btn-sm btn-success" 
                                onclick='sendWhatsApp(@json($invoice))'
                                title="Send via WhatsApp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
function sendWhatsApp(invoice) {
    // Remove any spaces or special characters from mobile number
    let cleanMobile = invoice.mobile_no.replace(/\D/g, '');
    
    // Add country code if not present (assuming India +91)
    if (!cleanMobile.startsWith('91') && cleanMobile.length === 10) {
        cleanMobile = '91' + cleanMobile;
    }
    
    // Format date
    const date = new Date(invoice.created_at);
    const formattedDate = date.toLocaleDateString('en-GB');
    
    // Build products list
    let productsText = '';
    if (invoice.products && invoice.products.length > 0) {
        invoice.products.forEach((product, index) => {
            productsText += `\n\n*Item ${index + 1}:* ${product.name}`;
            productsText += `\n   Qty: ${product.qty} | Rate: ₹${parseFloat(product.rate).toLocaleString('en-IN')} | Amount: ₹${parseFloat(product.amount).toLocaleString('en-IN')}`;
        });
    }
    
    // Create formatted message
    const message = `*MY SHOP - BILL SUMMARY*

*Date:* ${formattedDate}
*Customer:* ${invoice.to_name}
*Bill No:* ${invoice.bill_no}
${productsText}

--------------------------------
*TOTAL AMOUNT*
--------------------------------
Subtotal: ₹${parseFloat(invoice.subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
Tax: ₹${parseFloat(invoice.tax).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
Shipping: ₹${parseFloat(invoice.shipping || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
--------------------------------
*TOTAL: ₹${parseFloat(invoice.total).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}*

*Payment Method:* ${invoice.payment_method}

Thank you for your business!
- My Shop`;

    // Encode message for URL
    const encodedMessage = encodeURIComponent(message);
    
    // Create WhatsApp URL
    const whatsappUrl = `https://wa.me/${cleanMobile}?text=${encodedMessage}`;
    
    // Open WhatsApp in new tab
    window.open(whatsappUrl, '_blank');
}
</script>
@endsection
