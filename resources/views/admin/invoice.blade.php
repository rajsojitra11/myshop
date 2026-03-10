@extends('admin.index')
@section('title', 'Invoice')
@section('page-title', 'Invoice')
@section('page', 'Invoice')

@section('content')
<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
<div class="container">
  <form action="{{ route('invoice.store') }}" method="POST">
      @csrf

      <div class="invoice p-2 mb-2" style="max-width: 95%; margin: 0 auto;">
          <!-- Title Row -->
          <div class="row g-0">
              <div class="col-12">
                  <h5 class="text-center mb-1"><i class="fas fa-globe"></i> My Shop</h5>    
                  <span class="float-right font-medium" style="font-size: 0.9rem;">Date: {{ date('d/m/Y') }}</span>
                  <hr style="margin: 0.5rem 0;">

                </div>
                
          </div>

        <!-- Info Row -->
<div class="row invoice-info g-2">
    <!-- Customer Details -->
    <div class="col-md-6">
        <h6 class="mb-2"><strong>Customer Information</strong></h6>

        <div class="mb-2">
            <label for="to_name" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Name</label>
            <input type="text" name="to_name" id="to_name" class="form-control form-control-sm" placeholder="Customer Name" required>
        </div>

        <div class="mb-2">
            <label for="to_address" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Address</label>
            <input type="text" name="to_address" id="to_address" class="form-control form-control-sm" placeholder="Address" required>
        </div>

        <div class="mb-2">
            <label for="to_email" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Email</label>
            <input type="email" name="to_email" id="to_email" class="form-control form-control-sm" placeholder="Email" required>
        </div>

        <div class="mb-2">
            <label for="mobile_no" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Mobile No.</label>
            <input type="text" name="mobile_no" id="mobile_no" class="form-control form-control-sm" placeholder="Mobile No." required>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="col-md-6">
        <h6 class="mb-2"><strong>Invoice Details</strong></h6>

        <div class="mb-2">
            <label for="bill_no" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Bill No.</label>
            <input type="text" name="bill_no" id="bill_no" class="form-control form-control-sm" readonly>           
        </div>

        <div class="mb-2">
            <label for="invoice_number" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Invoice No.</label>
            <input type="text" name="invoice_number" id="invoice_number" class="form-control form-control-sm" readonly>

        </div>

        <div class="mb-2">
            <label for="order_id" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Order ID</label>
            <input type="text" name="order_id" id="order_id" class="form-control form-control-sm" readonly>

        </div>

        <div class="mb-2">
            <label for="account_number" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Account</label>
            <input type="text" name="account_number" id="account_number" class="form-control form-control-sm" placeholder="Account No." required>
        </div>
    </div>
</div>

          <!-- Product Table -->
          <div class="row mt-2">
              <div class="col-12">
                  <table class="table table-bordered table-sm" id="invoiceTable" style="font-size: 0.9rem;">
                      <thead class="table-light">
                          <tr style="font-size: 0.85rem; padding: 0.25rem;">
                              <th style="padding: 0.35rem;">Serial</th>
                              <th style="padding: 0.35rem;">Product</th>
                              <th style="padding: 0.35rem;">Description</th>
                              <th style="padding: 0.35rem;">Qty</th>
                              <th style="padding: 0.35rem;">Rate</th>
                              <th style="padding: 0.35rem;">Amount</th>
                              <th style="padding: 0.35rem;">Action</th>
                          </tr>
                      </thead>
                      <tbody id="productRows">
                          <tr>
                              <td style="padding: 0.25rem;"><input type="text" name="products[0][serial]" class="form-control form-control-sm"></td>
                              <td style="padding: 0.25rem;"><input type="text" name="products[0][name]" class="form-control form-control-sm" required></td>
                              <td style="padding: 0.25rem;"><input type="text" name="products[0][description]" class="form-control form-control-sm"></td>
                              <td style="padding: 0.25rem;"><input type="number" name="products[0][qty]" class="form-control form-control-sm qty" required></td>
                              <td style="padding: 0.25rem;"><input type="number" name="products[0][rate]" class="form-control form-control-sm subtotal" required></td>
                              <td style="padding: 0.25rem;"><input type="number" name="products[0][amount]" class="form-control form-control-sm subtotal" required></td>
                              <td style="padding: 0.25rem;"><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                          </tr>
                      </tbody>
                  </table>
                  <button type="button" class="btn btn-primary btn-sm" id="addRow">+ Add Product</button>
              </div>
          </div>

          <!-- Payment Summary -->
          <div class="row mt-2 g-2">
            <div class="col-md-6">
                <p style="font-size: 0.9rem; margin-bottom: 0.5rem;"><strong>Payment Method</strong></p>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <img src="../../dist/img/credit/credit-card.png" alt="Credit Card" class="pay-icon" id="credit_card" style="width: 40px; height: 40px; cursor: pointer;">
                    <img src="../../dist/img/credit/paypal2.png" alt="PayPal" class="pay-icon" id="paypal" style="width: 40px; height: 40px; cursor: pointer;">
                    <img src="../../dist/img/credit/bank-transfer.png" alt="Bank Transfer" class="pay-icon" id="bank_transfer" style="width: 40px; height: 40px; cursor: pointer;">
                    <img src="../../dist/img/credit/money-stack.png" alt="Cash" class="pay-icon" id="cash" style="width: 40px; height: 40px; cursor: pointer;">
                </div>
                <select id="paymentMethod" name="paymentMethod" class="form-control form-control-sm">
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
        
            <div class="col-md-6">
                <p style="font-size: 0.9rem; margin-bottom: 0.5rem;"><strong>Amount Due</strong></p>
                <div class="table-responsive">
                    <table class="table table-sm" style="font-size: 0.85rem; margin: 0;">
                        <tr>
                            <th style="padding: 0.25rem;">Subtotal:</th>
                            <td style="padding: 0.25rem;"><input type="number" name="subtotal" id="subtotal" class="form-control form-control-sm" readonly></td>
                        </tr>
                        <tr>
                            <th style="padding: 0.25rem;">Tax (9.3%):</th>
                            <td style="padding: 0.25rem;"><input type="number" name="tax" id="tax" class="form-control form-control-sm" readonly></td>
                        </tr>
                        <tr>
                            <th style="padding: 0.25rem;">Shipping:</th>
                            <td style="padding: 0.25rem;"><input type="number" name="shipping" id="shipping" class="form-control form-control-sm"></td>
                        </tr>
                        <tr class="table-active">
                            <th style="padding: 0.35rem;">Total:</th>
                            <td style="padding: 0.35rem;"><input type="number" name="total" id="total" class="form-control form-control-sm fw-bold" readonly></td>
                        </tr>
                    </table>
                </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row mt-2">
            <div class="col-6">
                <button type="button" class="btn btn-secondary btn-sm" id="printInvoice">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <div class="col-6 text-end">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="far fa-credit-card"></i> Submit
                </button>
            </div>
        </div>
        
      </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
      let rowIndex = 0; // Start from 0, as the first row is removed
  
      
      $("#productRows").empty();
  
     
      function addProductRow() {
    let newRow = `
        <tr>
            <td style="padding: 0.25rem;"><input type="text" name="products[${rowIndex}][serial]" class="form-control form-control-sm"></td>
            <td style="padding: 0.25rem;">
                <select name="products[${rowIndex}][name]" class="form-control form-control-sm product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->name }}" data-description="{{ $product->description }}" data-rate="{{ $product->price }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td style="padding: 0.25rem;"><input type="text" name="products[${rowIndex}][description]" class="form-control form-control-sm"></td>
            <td style="padding: 0.25rem;"><input type="number" name="products[${rowIndex}][qty]" class="form-control form-control-sm qty" required></td>
            <td style="padding: 0.25rem;"><input type="number" name="products[${rowIndex}][rate]" class="form-control form-control-sm rate" required></td>
            <td style="padding: 0.25rem;"><input type="number" name="products[${rowIndex}][amount]" class="form-control form-control-sm amount" readonly></td>
            <td style="padding: 0.25rem;"><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
        </tr>
    `;
    $("#productRows").append(newRow);
    rowIndex++;
}
  
      // Add product row when "Add Product" button is clicked
      $("#addRow").click(function() {
          addProductRow();
      });
  
      // Remove product row when "X" button is clicked
      $(document).on("click", ".removeRow", function() {
          $(this).closest("tr").remove();
          updateTotals(); // Recalculate totals when row is removed
      });
  
      // Calculate the amount (Rate * Quantity) and update the totals
      $(document).on("input", ".rate, .qty", function() {
          const row = $(this).closest('tr');
          const rate = parseFloat(row.find('.rate').val()) || 0;
          const qty = parseFloat(row.find('.qty').val()) || 0;
          const amount = rate * qty;
          row.find('.amount').val(amount.toFixed(2));
  
          updateTotals(); // Recalculate totals whenever rate or quantity changes
      });
  
      // Update the shipping value and total when the user enters a shipping amount
      $(document).on("input", "#shipping", function() {
          updateTotals(); // Recalculate totals whenever shipping value changes
      });
  
      // Function to update the subtotal, tax, shipping, and total
      function updateTotals() {
          let subtotal = 0;
  
          // Calculate the subtotal by summing all the amounts
          $(".amount").each(function() {
              subtotal += parseFloat($(this).val()) || 0;
          });
  
          // Update the subtotal field
          $("#subtotal").val(subtotal.toFixed(2));
  
          // Calculate tax (9.3%)
          const tax = subtotal * 0.093;
          $("#tax").val(tax.toFixed(2));
  
          // Get the shipping amount (ensure it's included)
          const shipping = parseFloat($("#shipping").val()) || 0;
  
          // Calculate final total: Subtotal + Tax + Shipping
          const total = subtotal + tax + shipping;
          $("#total").val(total.toFixed(2));
      }
  
      // Update the selected payment method when the user selects a new one
      $(document).on("change", "#paymentMethod", function() {
          const selectedMethod = $(this).val();
          $("#paymentMethodDisplay").text(selectedMethod);  // Update the display
      });
  
      // When printing, show the selected payment method in the print layout
      document.getElementById("printInvoice").addEventListener("click", function () {
          const selectedMethod = $("#paymentMethod").val();
          $("#paymentMethodDisplay").text(selectedMethod);  // Update the display for printing
          window.print();  // Trigger print
      });
  
      // Ensure total updates correctly on page load
      updateTotals();
    });

    document.addEventListener("DOMContentLoaded", function () {
    function generateBillNo() {
        // Retrieve the last bill number from localStorage
        let lastBillNo = localStorage.getItem("lastBillNo");

        // Extract the numeric part and increment it
        let newNumber = lastBillNo ? parseInt(lastBillNo.split("-")[1]) + 1 : 1000;

        // Format the new bill number
        let newBillNo = `B-${newNumber}`;

        // Store the new bill number in localStorage
        localStorage.setItem("lastBillNo", newBillNo);

        return newBillNo;
    }

    // Set the generated bill number in the input field
    let billInput = document.getElementById("bill_no");
    if (billInput) {
        billInput.value = generateBillNo();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    function generateOrderId() {
        const timestamp = new Date().getTime().toString().slice(-6); // Last 6 digits of timestamp
        const randomNum = Math.floor(1000 + Math.random() * 9000); // Random 4-digit number
        return `${timestamp}${randomNum}`;
    }

    document.getElementById("order_id").value = generateOrderId();
});
document.addEventListener("DOMContentLoaded", function () {
    function generateInvoiceNumber() {
        const prefix = "INV"; // Invoice prefix
        const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let randomLetters = "";
        
        // Generate 4 random letters
        for (let i = 0; i < 4; i++) {
            randomLetters += letters.charAt(Math.floor(Math.random() * letters.length));
        }

        // Generate a 4-digit random number
        const randomNumber = Math.floor(1000 + Math.random() * 9000);

        return `${prefix}-${randomLetters}${randomNumber}`;
    }

    // Set the generated invoice number in the input field
    document.getElementById("invoice_number").value = generateInvoiceNumber();
});


document.addEventListener("DOMContentLoaded", function () {
    const paymentSelect = document.getElementById("paymentMethod");
    const paymentImages = document.querySelectorAll(".pay-icon");

    // Function to update selection and highlight the image
    function updatePaymentSelection(selectedMethod) {
        // Update dropdown
        paymentSelect.value = selectedMethod;

        // Highlight selected image, remove highlight from others
        paymentImages.forEach(img => {
            if (img.id === selectedMethod.replace(" ", "_").toLowerCase()) {
                img.style.border = "3px solid blue";
                img.style.borderRadius = "5px";
            } else {
                img.style.border = "none";
            }
        });
    }

    // When an image is clicked, update the dropdown and highlight selection
    paymentImages.forEach(img => {
        img.addEventListener("click", function () {
            const selectedMethod = img.alt; // Get method from alt text
            updatePaymentSelection(selectedMethod);
        });
    });

    // When the dropdown changes, update the highlight
    paymentSelect.addEventListener("change", function () {
        updatePaymentSelection(paymentSelect.value);
    });
});


  </script>
  <script>
    $(document).ready(function() {
      setTimeout(function() {
        $(".alert").alert('close');
      }, 3000); // 3 seconds
      $(document).on('change', '.product-select', function() {
    const row = $(this).closest('tr');
    const selectedOption = $(this).find('option:selected');
    const description = selectedOption.data('description');
    const rate = selectedOption.data('rate');
    
    row.find('input[name*="[description]"]').val(description);
    row.find('.rate').val(rate);
    
    // Trigger calculation
    row.find('.rate').trigger('input');
});
    });
  </script>
  
  
@endsection
