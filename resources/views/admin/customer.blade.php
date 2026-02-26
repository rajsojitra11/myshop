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
                        <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-sm btn-info" title="Download PDF">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <button type="button" 
                                class="btn btn-sm btn-success" 
                                onclick='sendWhatsAppInvoice(@json($invoice->id))'
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
function sendWhatsAppInvoice(invoiceId) {
    // Show loading message
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    btn.disabled = true;

    // Send request to backend
    fetch('{{ route("invoice.whatsapp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            invoice_id: invoiceId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Notify user about PDF generation
            alert('Invoice PDF generated successfully!\n\nA WhatsApp window will open. You can attach the PDF file manually or copy-paste the link from the PDF button.');
            
            // Open WhatsApp with message
            window.open(data.whatsapp_url, '_blank');
            
            // Open PDF in new tab for user to download/share
            setTimeout(() => {
                window.open(data.pdf_url, '_blank');
            }, 500);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the invoice.');
    })
    .finally(() => {
        // Restore button
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
}
</script>
@endsection
