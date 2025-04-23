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

      <div class="invoice p-3 mb-3">
          <!-- Title Row -->
          <div class="row">
              <div class="col-12">
                  <h4  class="text-center"><i class="fas fa-globe"></i>  My Shop</h4>    
                  <span class="float-right font-medium">Date: {{ date('d/m/Y') }}</span>
                  <br>             
                  <hr>

                </div>
                
          </div>

        <!-- Info Row -->
<div class="row invoice-info">
    <!-- Customer Details -->
    <div class="col-md-6">
        <h5 class="mb-3"><strong>Customer Information</strong></h5>

        <div class="mb-3">
            <label for="to_name" class="form-label">Name</label>
            <input type="text" name="to_name" id="to_name" class="form-control" placeholder="Customer Name" required>
        </div>

        <div class="mb-3">
            <label for="to_address" class="form-label">Address</label>
            <input type="text" name="to_address" id="to_address" class="form-control" placeholder="Address" required>
        </div>

        <div class="mb-3">
            <label for="to_email" class="form-label">Email</label>
            <input type="email" name="to_email" id="to_email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-3">
            <label for="mobile_no" class="form-label">Mobile No.</label>
            <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Mobile No." required>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="col-md-6">
        <h5 class="mb-3"><strong>Invoice Details</strong></h5>

        <div class="mb-3">
            <label for="bill_no" class="form-label">Bill No.</label>
            <input type="text" name="bill_no" id="bill_no" class="form-control" readonly>           
        </div>

        <div class="mb-3">
            <label for="invoice_number" class="form-label">Invoice No.</label>
            <input type="text" name="invoice_number" id="invoice_number" class="form-control" readonly>

        </div>

        <div class="mb-3">
            <label for="order_id" class="form-label">Order ID</label>
            <input type="text" name="order_id" id="order_id" class="form-control" readonly>

        </div>

        <div class="mb-3">
            <label for="account_number" class="form-label">Account</label>
            <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Account No." required>
        </div>
    </div>
</div>

          <!-- Product Table -->
          <div class="row mt-4">
              <div class="col-12">
                  <table class="table table-bordered" id="invoiceTable">
                      <thead>
                          <tr>
                              <th>Serial </th>
                              <th>Product</th>
                              <th>Description</th>
                              <th>Qty</th>
                              <th>Rate</th>
                              <th>Amount</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody id="productRows">
                          <tr>
                              <td><input type="text" name="products[0][serial]" class="form-control"></td>
                              <td><input type="text" name="products[0][name]" class="form-control" required></td>
                              <td><input type="text" name="products[0][description]" class="form-control"></td>
                              <td><input type="number" name="products[0][qty]" class="form-control qty" required></td>
                              <td><input type="number" name="products[0][rate]" class="form-control subtotal" required></td>
                              <td><input type="number" name="products[0][amount]" class="form-control subtotal" required></td>
                              <td><button type="button" class="btn btn-danger removeRow">X</button></td>
                          </tr>
                      </tbody>
                  </table>
                  <button type="button" class="btn btn-primary" id="addRow">+ Add Product</button>
              </div>
          </div>

          <!-- Payment Summary -->
          <div class="row mt-4">
            <div class="col-6">
                <p><strong>Selected Payment Method: </strong></p>
                <div class="col-6">
                    <img src="../../dist/img/credit/credit-card.png" alt="Credit Card" class="pay-icon" id="credit_card">
                    <img src="../../dist/img/credit/paypal2.png" alt="PayPal" class="pay-icon" id="paypal">
                    <img src="../../dist/img/credit/bank-transfer.png" alt="Bank Transfer" class="pay-icon" id="bank_transfer">
                    <img src="../../dist/img/credit/money-stack.png" alt="Cash" class="pay-icon" id="cash">
                    
                  </div><br>
                <b class="lead">Payment Methods:</b>
                <select id="paymentMethod" name="paymentMethod" class="form-control">
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
        
            <div class="col-6">
                
                <p class="lead">Amount Due</p>
                <div class="table-responsive">
                    <table class="table">
                    
                        <tr>
                            <th>Subtotal:</th>
                            <td><input type="number" name="subtotal" id="subtotal" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th>Tax (9.3%):</th>
                            <td><input type="number" name="tax" id="tax" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td><input type="number" name="shipping" id="shipping" class="form-control"></td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td><input type="number" name="total" id="total" class="form-control" readonly></td>
                        </tr>
                    </table>
                </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row mt-4">
            <div class="col-6">
                <button type="button" class="btn btn-secondary" id="printInvoice">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
            </div>
            <div class="col-6 text-right">
                <button type="submit" class="btn btn-success">
                    <i class="far fa-credit-card"></i> Submit Invoice
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
                  <td><input type="text" name="products[${rowIndex}][serial]" class="form-control"></td>
                  <td><input type="text" name="products[${rowIndex}][name]" class="form-control" required></td>
                  <td><input type="text" name="products[${rowIndex}][description]" class="form-control"></td>
                  <td><input type="number" name="products[${rowIndex}][qty]" class="form-control qty" required></td>
                  <td><input type="number" name="products[${rowIndex}][rate]" class="form-control rate" required></td>
                  <td><input type="number" name="products[${rowIndex}][amount]" class="form-control amount" readonly></td>
                  <td><button type="button" class="btn btn-danger removeRow">X</button></td>
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
    });
  </script>
  
  
@endsection
